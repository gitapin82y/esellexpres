<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use App\Models\Store;
use App\Mail\NotifMail;
use App\Models\Product;
use App\Models\ShippingFee;
use App\Models\Transaction;
use App\Models\BadgeSidebar;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Url;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BadgeSidebarController;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'Incoming Orders')->delete();
        if(Auth::user()->role == 1){
            return view('pages.transactions.incoming-orders');
        }else{
            $store = Store::where('user_id', Auth::id())->first();
    
            $hold = Transaction::where('store_id', $store->id)->where('is_confirmed', 'Y')->where('status','!=', 'The customer has received the order')->where('status','!=', 'Reject order')->sum('profit');
        
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
            '<a href="#mymodal" data-remote="'. route('transaction.show',$data->id) .'" data-toggle="modal" data-target="#mymodal" data-title="Detail Transaksi <b>'. $data->uuid .'</b>" class="btn btn-primary"><i class="fa fa-eye text-white"></i></a>';
            if($data->status=='Waiting process'){
                $aksi .= '<a href="'.route('transactions.status',['id' => $data->id, 'status' => 'Process']) . '"" class="btn btn-success"><i class="fa fa-check"></i></a>';
            }
            if(Auth::user()->role == 1){
                if($data->status == 'Reject order' || $data->status == 'The customer has received the order'){
                    $aksi .= '<a href="javascript:void(0)" style="opacity:0.2;" class="btn btn-main disabled ml-1 text-light"><i class="fa fa-times"></i></a>';
                }else{
                    $aksi .= '<a href="'.route('transactions.status',['id' => $data->id, 'status' => 'Reject order']) . '"" class="btn btn-main  ml-1 text-light"><i class="fa fa-times"></i></a>';
                }
                
                // Tambahkan form untuk delete
                $aksi .= '<form method="POST" action="'. route('transactions.destroy', $data->id) .'" style="display: inline;" id="deleteForm">';
                $aksi .= '<input type="hidden" name="_token" value="'. csrf_token() .'">';
                $aksi .= '<input type="hidden" name="_method" value="DELETE">';
                $aksi .= '<button type="button" class="btn btn-danger ml-1" onclick="confirmDelete('.$data->id.')"><i class="fa fa-trash"></i></button>';
                $aksi .= '</form>';
            }
            $aksi .= '</div>';
            return $aksi;
        })
        ->addColumn('created_at',function($data){
                return Carbon::parse($data->created_at)->format('F j, Y');
        })
        ->addColumn('profit',function($data){
            if( $data->status == 'The customer has received the order'){
                return '<span style="color: #349e5a;"><strong>$'.$data->profit.'</strong></span>';
            }

            if( $data->status == 'Reject order'){
                return '<span style="color: #dc3545;"><strong>$'.$data->profit.'</strong></span>';
            }
            
            if($data->is_confirmed == 'Y'){
                return '<span style="color: #828282;"><strong> $'.$data->profit.'</strong></span>';
            }
       
            return '<span style="color: #e7973c;"><strong>$'.$data->profit.'</strong></span>';
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
            $fullUrl = url()->full();
            return redirect('login?next='.$fullUrl);
        }
    }

    public function checkout(Request $request){
        $user = Auth::user();

        $newBalance = $user->balance - $request->transaction_total;

        if($newBalance >= 0.00){
            
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
                'url' => 'esellexpress.com/login?next=https://esellexpress.com/transaction'
            ];

            BadgeSidebarController::send('Incoming Orders',$transaction->stores->users->id);
            
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

    public function statusProductAll(){
        $items = Transaction::where('user_id',Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('pages.transactions.productAll',compact('items'));
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


            BadgeSidebarController::send('Incoming Orders');
            
            $notifOrderMasuk = true;
        }


        if($request->status == 'Reject order'){
            User::where('id', $transaction->stores->user_id)->increment('balance', $transaction->transaction_total);
            User::where('role', 1)->decrement('balance', $transaction->transaction_total);
            User::where('id', $transaction->user_id)->increment('balance', $transaction->profit);
        }

        if($notifOrderMasuk){
            $details = [
                'title' => 'There is the latest incoming order',
                'body' => 'Congratulations, there is an incoming order with transaction number '.$transaction->uuid.', prepare your order now',
                'url' => 'esellexpress.com/elxadmin?next=https://esellexpress.com/transaction'
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

    public function destroy($id)
    {
        // Hapus detail transaksi terkait
        TransactionDetail::where('transaction_id', $id)->delete();

        // Hapus transaksi
        Transaction::destroy($id);

        return redirect()->back()->with('success', 'Transaction deleted successfully.');
    }
}
