<?php

namespace App\Http\Controllers;

use App\Mail\NotifMail;
use App\Models\BadgeSidebar;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\TransactionBalance;
use App\Jobs\SendTopupNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BadgeSidebarController;


class TransactionBalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'topup')->delete();
        return view('pages.transactions.reseller.topup-request');
    }

    public function datatable(){
        $data = TransactionBalance::with('users')->where('submission','Top Up')->orderBy('created_at', 'desc')->get();

      return Datatables::of($data)
        ->addColumn('action', function ($data) {
            if($data->status != 'Pending'){
                return  '<div class="btn-group">' .
                '<a href="javascript::void(0)" class="deleteRequest ml-1 btn btn-danger btn-lg" data-id="'.$data->id.'" title="delete">' .
                '<label class="fa fa-trash"></label> Delete</a>' .
                '</div>';
            }else{
                return  '<div class="btn-group">' .
                '<a href="javascript::void(0)" data-id="'.$data->id.'" class="accRequest btn btn-info btn-lg mr-1">'.
                '<label class="fa fa-check"></label> Acc</a>' .
                '<a href="javascript::void(0)" data-id="'.$data->id.'" class="rejectRequest btn btn-warning btn-lg" title="reject">' .
                '<label class="fa fa-times"></label> Reject</a>' .
                '<a href="javascript::void(0)" class="deleteRequest ml-1 btn btn-danger btn-lg" data-id="'.$data->id.'" title="delete">' .
                '<label class="fa fa-trash"></label> Delete</a>' .
                '</div>';
            }

        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function destroy($id){
        TransactionBalance::destroy($id);

        return response()->json(['message' => 'Transaction balance deleted successfully'], 200);
    }

    public function accRequest($id){
        $data = TransactionBalance::with('users')->where('id',$id)->first();
        $data->status = 'Success';
        $data->users->balance = $data->users->balance + $data->total;
        $data->save();
        $data->users->save();

        $details = [
            'title' => 'Balance top-up request successful',
            'body' => 'Congratulations, your request to top-up your balance of $'.$data->total.' has been successfully accepted.',
        ];
        Mail::to($data->users->email)->send(new NotifMail($details));

        return response()->json(['success' => 'Data']);
    }

    public function rejectRequest($id){
        $data = TransactionBalance::with('users')->find($id);
        $data->update(['status' => 'Failure']);

        $details = [
            'title' => 'Request to top up balance rejected',
            'body' => 'There may be an error in the transaction information, please resubmit the request',
        ];
        Mail::to($data->users->email)->send(new NotifMail($details));

        return response()->json(['success' => 'Data']);
    }


    public function indextopup()
    {
        return view('pages.transactions.topup-request');
    }

    public function datatabletopup(){
        $data = TransactionBalance::where('user_id',Auth::user()->id)->where('submission','Top Up')->orderBy('created_at', 'desc')->get();

      return Datatables::of($data)
        ->addIndexColumn()
        ->make(true);
    }

    public function storetopup(Request $request)
    {
        $path = $request->file('proof')->store('proof-transaction','public');
        $file = 'storage/'.$path;

        TransactionBalance::create([
            'user_id' => Auth::user()->id,
            'total' => $request->total,
            'proof' =>  $file,
            'message' => $request->message,
            'submission' => 'Top Up',
            'status' => 'Pending',
        ]);

        $details = [
            'title' => 'There is a balance top-up request',
            'body' => Auth::user()->email.' make a request to top-up a balance of $'.$request->total.' see more details on the Esellexpress website and acc to top-up the balance',
            'url' => 'esellexpress.com/elxadmin?next=https://esellexpress.com/topup-request'
        ];

        BadgeSidebarController::send('topup');

        Mail::to("cs@esellexpress.com")->send(new NotifMail($details));

        return back()->with('toast_success','Sent successfully, wait until the balance increases');
    }


    public function indexwithdraw()
    {
        return view('pages.transactions.withdraw-request');
    }

    public function datatablewithdraw(){
        $data = TransactionBalance::where('user_id',Auth::user()->id)->where('submission','Withdraw')->get();

      return Datatables::of($data)
        ->addIndexColumn()
        ->make(true);
    }


    public function storewithdraw(Request $request)
    {
        $nominal = Auth::user()->balance - $request->total;
        if($nominal < 0){
            return back()->with('toast_error','Your balance is not enough');
        }

        $password = decrypt(Auth::user()->password);

        if($request->password != $password){
            return back()->with('toast_error','Confirm your password is incorrect');
        }

        TransactionBalance::create([
            'user_id' => Auth::user()->id,
            'total' => $request->total,
            'bank_account' => $request->bank_account,
            'number' => $request->number,
            'message' => $request->message,
            'submission' => 'Withdraw',
            'status' => 'Pending',
        ]);

        $details = [
            'title' => 'There is a balance withdraw request',
            'body' => Auth::user()->email.' make a request to withdraw a balance of $'.$request->total.' see more details on the Esellexpress website and acc to withdraw the balance',
            'url' => 'esellexpress.com/elxadmin?next=https://esellexpress.com/withdraw-request'
        ];

        BadgeSidebarController::send('withdraw');

        Mail::to("cs@esellexpress.com")->send(new NotifMail($details));

        return back()->with('toast_success','Sent Successfully, Wait a maximum of 1x24 hours');
    }


    //  admin withdraw
    public function withdraw()
    {
        BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'withdraw')->delete();
        return view('pages.transactions.reseller.withdraw-request');
    }

    public function datatableWithdrawRequest(){
        $data = TransactionBalance::with('users')->where('submission','Withdraw')->orderBy('created_at', 'desc')->get();

      return Datatables::of($data)
        ->addColumn('action', function ($data) {
            if($data->status != 'Pending'){
                return  '<div class="btn-group">' .
                '<a href="javascript::void(0)" class="deleteRequest ml-1 btn btn-danger btn-lg" data-id="'.$data->id.'" title="delete">' .
                '<label class="fa fa-trash"></label> Delete</a>' .
                '</div>';
            }else{
                return  '<div class="btn-group">' .
                '<a href="javascript::void(0)" data-id="'.$data->id.'" data-nominal="'.$data->nominal.'" data-bank_account="'.$data->bank_account.'" data-number="'.$data->number.'"  class="accRequest btn btn-info btn-lg mr-1">'.
                '<label class="fa fa-check"></label> Acc</a>' .
                '<a href="javascript::void(0)" data-id="'.$data->id.'" class="rejectRequest btn btn-warning btn-lg" title="reject">' .
                '<label class="fa fa-times"></label> Reject</a>' .
                '<a href="javascript::void(0)" class="deleteRequest ml-1 btn btn-danger btn-lg" data-id="'.$data->id.'" title="delete">' .
                '<label class="fa fa-trash"></label> Delete</a>' .
                '</div>';
            }

        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function accRequestWithdaw($id){
        $data = TransactionBalance::with('users')->where('id',$id)->first();
        $data->status = 'Success';
        $data->users->balance = $data->users->balance - $data->total;
        $data->save();
        $data->users->save();

        $details = [
            'title' => 'Balance withdraw request successful',
            'body' => 'Congratulations, your request to withdraw your balance of $'.$data->total.' has been successfully accepted.',
        ];
        Mail::to($data->users->email)->send(new NotifMail($details));

        return response()->json(['success' => 'Data']);
    }

    public function rejectRequestWithdaw($id){
        $data = TransactionBalance::with('users')->find($id);
        $data->update(['status' => 'Failure']);

        $details = [
            'title' => 'Request to withdraw balance rejected',
            'body' => 'There may be an error in the transaction information, please resubmit the request',
        ];
        Mail::to($data->users->email)->send(new NotifMail($details));

        return response()->json(['success' => 'Data']);
    }
}
