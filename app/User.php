<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @param int $s
     * @param string $d
     * @param string $r
     * @param bool $img
     * @param array $atts
     * @return string
     */
    public function get_gravatar($s = 40, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
        $parse_url = parse_url($this->photo);
        $url = '';
        $email = $this->email;

        if (!empty($parse_url['scheme'])) {
            $url = $this->photo;
        } else {
            $url = 'http://www.gravatar.com/avatar/';
            $url .= md5(strtolower(trim($email)));
            $url .= "?s=$s&d=$d&r=$r";

            if (!empty($this->photo)) {
                $url = avatar_img_url($this->photo, $this->photo_storage);
            }

            if ($img) {
                $url = '<img src="' . $url . '"';
                foreach ($atts as $key => $val)
                    $url .= ' ' . $key . '="' . $val . '"';
                $url .= ' />';
            }
        }
        return $url;
    }

    public function get_address()
    {
        $address = '';

        if ($this->country) {
            $address .= $this->country->country_name . ', ';
        }
        if (!empty($this->address)) {
            $address .= $this->address;
        }
        return $address;
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function favourite_ads()
    {
        return $this->belongsToMany(Ad::class, 'favorites');
    }

    public function signed_up_datetime()
    {
        $created_date_time = false;
        if ($this->created_at) {
            $created_date_time = $this->created_at->timezone(get_option('default_timezone'))->format(get_option('date_format_custom') . ' ' . get_option('time_format_custom'));
        }
        return $created_date_time;
    }

    public function status_context()
    {
        $status = $this->active_status;

        $context = '';
        switch ($status) {
            case '0':
                $context = 'Pending';
                break;
            case '1':
                $context = 'Active';
                break;
            case '2':
                $context = 'Block';
                break;
        }
        return $context;
    }

    public function is_admin()
    {
        if ($this->user_type == 'admin') {
            return true;
        }
        return false;
    }


    public function is_broker()
    {
        if ($this->user_type == 'broker') {
            return true;
        }
        return false;
    }

    public function is_user()
    {
        if ($this->user_type == 'user') {
            return true;
        }
        return false;
    }

    public function broker_detail()
    {
        return $this->hasOne(BrokerDetail::class);
    }

    public function property(){
        return $this->belongsTo(Ad::class,'property_id');
    }

}
