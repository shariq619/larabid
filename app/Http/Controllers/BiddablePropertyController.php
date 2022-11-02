<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Jobs\GlobalEmailJob;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class BiddablePropertyController extends Controller
{
    protected $data = [];

    public function index()
    {
        $this->data['properties'] = Ad::all();
        return view('biddable-properties', $this->data);
    }

    public function showPropertyDetail(Request $request)
    {
        $property = Ad::find($request->property);
        $user = User::where('user_type', 'admin')->first();

        // Sending Email

        $content = "A user has filled the form. Below are the details: ";
        $content .= '<br><br>PROPERTY SELECTED : ' . $property->title;
        $content .= '<br>EMAIL : ' . $request->email;
        $content .= '<br>CONTACT : ' . $request->contact;


        GlobalEmailJob::dispatch($content, 'Buyer filled the form', $property->seller_email);
        GlobalEmailJob::dispatch($content, 'Buyer filled the form', $user->email);


        $content_for_buyer = 'Your details have been mailed to owner<br><br><br>';
        $content_for_buyer .= 'Below are the details of your selected property:<br><br>';
        $content_for_buyer .= 'Property Name: ' . ($property->title ?? '') . '<br>';
        $content_for_buyer .= 'Property Description: ' . ($property->description ?? '') . '<br>';
        $content_for_buyer .= 'Category: ' . ($property->sub_category->category_name ?? '') . '<br>';
        $content_for_buyer .= 'Owner Name: ' . ($property->seller_name ?? '') . '<br>';
        $content_for_buyer .= 'Owner Email: ' . ($property->seller_email ?? '') . '<br>';
        $content_for_buyer .= 'Owner Phone: ' . ($property->seller_phone ?? '') . '<br>';
        $content_for_buyer .= 'Expire On: ' . ($property->expired_at ?? '') . '<br>';

        GlobalEmailJob::dispatch($content_for_buyer, 'Detail has been sent to owner', $request->email);

        return response()->json(['success' => true, 'data' => ['slug' => $property->slug]]);
    }

    public function showSelectedPropertyDetail($property_slug)
    {
        $this->data['property'] = Ad::where('slug', $property_slug)->first();
        return view('biddable-property-detail', $this->data);
    }


//    public function registerBroker(Request $request)
//    {
//        $this->validator($request->all())->validate();
//        $user = $this->create($request->all());
////        event(new Registered($user));
//        $user->sendEmailVerificationNotification();
////        $user->sendEmailVerificationNotification();
//        return response()->json(['success' => true], 200);
//    }

//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
////            'name' => 'required|string|max:255',
//            'contact' => 'required',
//            'email' => 'required|string|email|max:255|unique:users',
////            'password' => 'required|string|min:6|confirmed',
//            'password' => 'required|string|min:6',
//        ]);
//    }

//    protected function create(array $data)
//    {
//        return User::create([
//            'name' => "abc",
//            'email' => $data['email'],
//            'password' => bcrypt($data['password']),
//            'user_type' => 'user',
//            'active_status' => '1'
//        ]);
//    }
}
//ATBBWp8Z54RcwwgTkKsYAvunGASHC629D4DF