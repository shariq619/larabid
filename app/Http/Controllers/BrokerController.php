<?php

namespace App\Http\Controllers;

use App\Ad;
use App\BrokerDetail;
use App\Jobs\GlobalEmailJob;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BrokerController extends Controller
{
    protected $data = [];

    public function index()
    {
        dd("admin");
    }


    public function biddingDetail()
    {

        if ($detail = BrokerDetail::where('user_id', Auth::user()->id)->exists()) {
            $this->data['detail'] = $detail = BrokerDetail::where('user_id', Auth::user()->id)->first();
        }
        $this->data['ad'] = Ad::where('id', Auth::user()->property_id)->first();


        return view('broker.bidding-detail', $this->data);
    }


    public function store(Request $request)
    {

        if ($detail = BrokerDetail::where('user_id', Auth::user()->id)->exists()) {
            Validator::make($request->all(), [
                'bidding_property_address' => 'required',
                'name' => 'required',
                'cell_num' => 'required',
                'brokerage_name' => 'required',
                'brokerage_address' => 'required',
                'buyer_1_name' => 'required',
                'buyer_2_name' => 'required',
            ])->validate();
        } else {
            Validator::make($request->all(), [
                'bidding_property_address' => 'required',
                'name' => 'required',
                'cell_num' => 'required',
                'brokerage_name' => 'required',
                'brokerage_address' => 'required',
                'buyer_1_name' => 'required',
                'buyer_2_name' => 'required',
                'offer_to_purchase' => 'required',
                'proof_of_fund' => 'required',
            ])->validate();
        }


        $attachments = [];
        $data = [
            'bidding_property_address' => $request->bidding_property_address,
            'name' => $request->name,
            'cell_num' => $request->cell_num,
            'brokerage_name' => $request->brokerage_name,
            'brokerage_address' => $request->brokerage_address,
            'buyer_1_name' => $request->buyer_1_name,
            'buyer_2_name' => $request->buyer_2_name,
        ];
        if ($request->file('offer_to_purchase')) {
            $offer_to_purchase_name = time() . '.' . $request->offer_to_purchase->extension();
            $path = $request->offer_to_purchase->move(public_path('documents'), $offer_to_purchase_name);
            $data['offer_to_purchase'] = 'documents/' . $offer_to_purchase_name;

        }

        if ($request->file('proof_of_fund')) {
            $proof_of_fund_name = time() . '.' . $request->proof_of_fund->extension();
            $request->proof_of_fund->move(public_path('documents'), $proof_of_fund_name);
            $data['proof_of_fund'] = 'documents/' . $proof_of_fund_name;
        }

        if ($request->file('mls_copy')) {
            $mls_copy_name = time() . '.' . $request->mls_copy->extension();
            $request->mls_copy->move(public_path('documents'), $mls_copy_name);
            $data['mls_copy'] = 'documents/' . $mls_copy_name;
        }

        if ($request->file('seller_disclosure')) {
            $seller_disclosure_name = time() . '.' . $request->seller_disclosure->extension();
            $request->seller_disclosure->move(public_path('documents'), $seller_disclosure_name);
            $data['seller_disclosure'] = 'documents/' . $seller_disclosure_name;
        }

        if ($request->file('other_document_1')) {
            $other_document_1_name = time() . '.' . $request->other_document_1->extension();
            $request->other_document_1->move(public_path('documents'), $other_document_1_name);
            $data['other_document_1'] = 'documents/' . $other_document_1_name;
        }

        if ($request->file('other_document_2')) {
            $other_document_2_name = time() . '.' . $request->other_document_2->extension();
            $request->other_document_2->move(public_path('documents'), $other_document_2_name);
            $data['other_document_2'] = 'documents/' . $other_document_2_name;
        }

        if ($request->file('other_document_3')) {
            $other_document_3_name = time() . '.' . $request->other_document_3->extension();
            $request->other_document_3->move(public_path('documents'), $other_document_3_name);
            $data['other_document_3'] = 'documents/' . $other_document_3_name;
        }

        if ($request->file('other_document_4')) {
            $other_document_4_name = time() . '.' . $request->other_document_4->extension();
            $request->other_document_4->move(public_path('documents'), $other_document_4_name);
            $data['other_document_4'] = 'documents/' . $other_document_4_name;
        }

        $data['user_id'] = Auth::user()->id;
        if (BrokerDetail::where('user_id', Auth::user()->id)->exists()) {
            BrokerDetail::where('user_id', Auth::user()->id)->update($data);
        } else {
            BrokerDetail::create($data);
        }

        $broker_detail = BrokerDetail::where('user_id', Auth::user()->id)->first();
        if (isset($broker_detail->offer_to_purchase)) {
            $attachments[] = public_path($broker_detail->offer_to_purchase);
        }
        if (isset($broker_detail->proof_of_fund)) {
            $attachments[] = public_path($broker_detail->proof_of_fund);
        }
        if (isset($broker_detail->mls_copy)) {
            $attachments[] = public_path($broker_detail->mls_copy);
        }
        if (isset($broker_detail->seller_disclosure)) {
            $attachments[] = public_path($broker_detail->seller_disclosure);
        }
        if (isset($broker_detail->other_document_1)) {
            $attachments[] = public_path($broker_detail->other_document_1);
        }
        if (isset($broker_detail->other_document_2)) {
            $attachments[] = public_path($broker_detail->other_document_2);
        }
        if (isset($broker_detail->other_document_3)) {
            $attachments[] = public_path($broker_detail->other_document_3);
        }
        if (isset($broker_detail->other_document_4)) {
            $attachments[] = public_path($broker_detail->other_document_4);
        }

        $email_attach = $attachments;
        $property_detail = Ad::where('id', Auth::user()->property_id)->first();

        $admin = User::where('user_type', 'admin')->first();
        $me = Auth::user()->email;


        $content_admin = 'A new broker has submitted the documents';
        $content = 'Your document has been submitted';
        $content_property_owner = $content_admin . "<br><br>";
        $content_property_owner .= '<a href="' . url('approve_documents') . '/' . $broker_detail->id . '"><button>Approve Documents</button></a><br><br>';

        $detail_content = "<br><br>Below are the detail:<br><br>";
        $detail_content .= "Bidding Property Address: " . ($request->bidding_property_address ?? '-') . "<br>";
        $detail_content .= "Name: " . ($request->name ?? '-') . "<br>";
        $detail_content .= "Cell Num: " . ($request->cell_num ?? '-') . "<br>";
        $detail_content .= "Brokerage Name: " . ($request->brokerage_name ?? '-') . "<br>";
        $detail_content .= "Brokerage Address: " . ($request->brokerage_address ?? '-') . "<br>";
        $detail_content .= "Buyer 1 Name: " . ($request->buyer_1_name ?? '-') . "<br>";
        $detail_content .= "Buyer 2 Name: " . ($request->buyer_1_name ?? '-') . "<br>";

        $content_admin .= $detail_content;
        $content .= $detail_content;
        $content_property_owner .= $detail_content;

        GlobalEmailJob::dispatch($content_admin, 'broker has been filled the bidding detail', $admin->email, $email_attach);
        GlobalEmailJob::dispatch($content, 'broker has been filled the bidding detail', $me, $email_attach);
        GlobalEmailJob::dispatch($content_property_owner, 'broker has been filled the bidding detail', $property_detail->seller_email, $email_attach);

        $message = 'Your detail has been saved. Owner will review your documents.';
        return view('broker.property_detail', compact('message', 'property_detail'));


    }

    public function approveDocuments($bidding_detail_id)
    {
        $broker_detail = BrokerDetail::where('id', $bidding_detail_id)->first();
        $message = '';
        if ($broker_detail->approved == 1) {
            $message = 'Documents are already approved';
        } else {
            BrokerDetail::where('id', $bidding_detail_id)->update(['approved' => 1]);
            $message = 'Documents have been approved';

            GlobalEmailJob::dispatch('Congratulations. your documents have been approved. <a href="' . route('login') . '">CLICK HERE</a> if you are interested to participate the auction', 'Documents have been approved', $broker_detail->user->email, []);

        }
        return view('approved_document_screen', compact('message'));


    }
}
