<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\ShippingFee;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Url;
use DB;
use App\Models\User;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Carbon;
use App\Mail\NotifMail;
use Illuminate\Support\Facades\Mail;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 1){
            return view('pages.transactions.incoming-orders');
        }else{
            $store = Store::where('user_id', Auth::id())->first();
    
            $hold = Transaction::where('store_id', $store->id)->where('is_confirmed', 'Y')->where('status','!=', 'The customer has received the order')->sum('profit');
        
            $taken = Transaction::where('store_id', $store->id)->where('is_confirmed', 'Y')
                ->where('status', 'The customer has received the order')->sum('profit');

            $profit = $store->profit;
        
            return view('pages.transactions.incoming-orders', compact('hold', 'taken','profit'));
        }
    }

    public function datatable(){
        if (Auth::user()->role == 1) {
            $data = Transaction::where('is_confirmed', 'Y')->orderBy('id', 'desc')->get();
        } else {
            $stores = Store::where('user_id', Auth::user()->id)->first();
            $data = Transaction::where('store_id', $stores->id)->orderBy('id', 'desc')->get();
        }

      return Datatables::of($data)
        ->addColumn('action', function ($data) {

            $aksi = '<div class="btn-group">' .
            '<a href="#mymodal" data-remote="'. route('transaction.show',$data->id) .'" data-toggle="modal" data-target="#mymodal" data-title="Detail Transaksi <b>'. $data->uuid .'</b>" class="btn btn-main"><i class="fa fa-eye text-white"></i></a>';
            if($data->status=='Waiting process'){
                $aksi .= '<a href="'.route('transactions.status',['id' => $data->id, 'status' => 'Process']) . '"" class="btn btn-success"><i class="fa fa-check"></i></a>';
            }
            $aksi .= '</div>';
            return $aksi;
        })
        ->addColumn('created_at',function($data){
                return Carbon::parse($data->created_at)->format('d F Y');
        })
        ->addColumn('profit',function($data){
            if( $data->status == 'The customer has received the order'){
                return '<span style="color: #3ce786;">$'.$data->profit.'</span>';
            }
            
            if($data->is_confirmed == 'Y'){
                return '<span style="color: #bbbbbb;">$'.$data->profit.'</span>';
            }
       
            return '<span style="color: #e7ab3c;">$'.$data->profit.'</span>';
    })
        ->rawColumns(['profit','created_at','action'])
        ->addIndexColumn()
        ->make(true);
    }

    public function shoppingCart(Request $request){
        if(Auth::check()){
            $store = DB::table('stores')->where('slug','=', $request->segment(1))->first();
            $shipping = ShippingFee::first();
            return view('pages.penjual.shopping-card',compact('store','shipping'));
        }else{
            $fullUrl = Url::full();
            return redirect('login?next='.$fullUrl);
        }
    }

    public function checkout(Request $request){
        $user = Auth::user();
        if($user->balance > $request->transaction_total){
            
            $data = $request->except('products');
            $data['uuid'] = 'INV-' . date('Ymd') . '-' . sprintf('%03d', mt_rand(1, 999));

            $transaction = Transaction::create($data);
            
            $details = [];
            foreach($request->products as $product){
                $details[] = new TransactionDetail([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                ]);
                
                Product::find($product['id'])->decrement('quantity',$product['quantity']);
                Product::find($product['id'])->increment('total_sold',$product['quantity']);
            }

            $transaction->details()->saveMany($details);

            $newsaldo = $user->balance - $request->profit;
            User::where('id', $user->id)->update([
                'balance' => $newsaldo,
            ]);

            $details = [
                'title' => 'There is the latest incoming order',
                'body' => 'Congratulations, there is an incoming order with transaction number '.$transaction->uuid.', confirm now',
                'url' => 'esellexpress.com/login?next=esellexpress.com/transaction'
            ];
            Mail::to($transaction->stores->users->email)->send(new NotifMail($details));
            
            return response()->json(['checkout' => true]);
        }else{
            return response()->json(['checkout' => false]);
        }
    }

    public function statusProduct($nameStore){
        $store = Store::where('slug',$nameStore)->first();
        $items = Transaction::where('user_id',Auth::user()->id)->where('store_id',$store->id)->orderBy('created_at', 'desc')->get();
        return view('pages.transactions.product',compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function setStatus(Request $request,$id){
        $transaction = Transaction::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->is_confirmed = 'Y';

        if($request->resi){
            $transaction->resi = $request->resi;
        }

        // reseller
        if($request->status == 'The customer has received the order'){
            User::where('id', $transaction->stores->user_id)->increment('balance', $transaction->profit);
            $transaction->save();

            $details = [
                'title' => 'The customer has received the order '.$transaction->uuid,
                'body' => 'Product order status with number '.$transaction->uuid.' is '.$request->status,
            ];
            $emailCustomer = $transaction->email;
            $emailSeller = $transaction->stores->users->email;
            $emailReseller = "cs@esellexpress.com";
            Mail::to($emailCustomer)
            ->bcc([$emailSeller, $emailReseller])
            ->send(new NotifMail($details));

            return back()->with('toast_success', 'Successfully confirmed receipt of order');
        }
        
        $notifOrderMasuk = false;
        if ($request->status == 'Process') {
            $userId = Auth::id();

            if(Auth::user()->balance < $transaction->transaction_total){
                return back()->with('failedConfirm', '
                Your balance is not enough to take advantage of the product');
            }
             // Update the current user's balance
            User::where('id', $userId)->decrement('balance', $transaction->transaction_total);

            // Update the balance for the user with role_id == 1
            User::where('role', 1)->increment('balance', $transaction->transaction_total);
            
            $notifOrderMasuk = true;
        }

        if($notifOrderMasuk){
            $details = [
                'title' => 'There is the latest incoming order',
                'body' => 'Congratulations, there is an incoming order with transaction number '.$transaction->uuid.', prepare your order now',
                'url' => 'esellexpress.com/login?next=esellexpress.com/transaction'
            ];
            Mail::to("cs@esellexpress.com")->send(new NotifMail($details));
        }else{
            $details = [
                'title' => 'Order with transaction number '.$transaction->uuid,
                'body' => 'Product order status with number '.$transaction->uuid.' is '.$request->status,
            ];
            $emailCustomer = $transaction->email;
            $emailSeller = $transaction->stores->users->email;
            Mail::to($emailCustomer)
            ->bcc([$emailSeller])
            ->send(new NotifMail($details));   
        }
    
        $transaction->save();
    
        return back()->with('toast_success', 'Successfully taken, profits are hold until the order is received');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Transaction::with('details')->findOrFail($id);
        return view('pages.transactions.detail-product',['item'=>$item]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
