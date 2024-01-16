<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\Transaction;
use App\Mail\NotifMail;
use Illuminate\Support\Facades\Mail;

class PenjualController extends Controller
{
       // KANDIDAT PENJUAL
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.reseller.kandidat-penjual');
    }

    public function datatable(){
        $data = User::with('stores')->where('role',3)->where('register',1)->orderBy('created_at', 'desc')->get();

      return Datatables::of($data)
        ->addColumn('action', function ($data) {
            return  '<div class="btn-group">' .
            '<a href="javascript::void(0)" data-id="'.$data->id.'" class="accKandidat mx-1 btn btn-info btn-lg">'.
            '<label class="fa fa-pencil-alt"></label>Acc</a>' .
            '<a href="javascript::void(0)" data-id="'.$data->id.'" class="tolakKandidat btn btn-danger btn-lg" title="tolak">Tolak</a>' .
            '</div>';
        })
        ->addColumn('card',function($data){
                return $data->type_card . ' - ' . $data->id_card;
        })
        ->rawColumns(['card','action'])
        ->addIndexColumn()
        ->make(true);
    }
    
    public function accKandidat($id){
        $user = User::find($id);

        $user->update(['role' => 2]);

        $details = [
            'title' => 'Congratulations, you have succeeded in becoming an esellexpress seller',
            'body' => 'Now you can set up your own shop and promote it to get lots of profits from sales',
            'url' => 'esellexpress.com/login',
        ];

        Mail::to($user->email)->send(new NotifMail($details));
        response()->json(['success' => 'Data']);
    }

    public function tolakKandidat(){
        response()->json(['success' => 'Data']);
    }

    // LIST PENJUAL

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexListPenjual()
    {
        return view('pages.reseller.list-penjual');
    }

    public function datatableListPenjual(){
        $data = User::with('stores')->where('role',2)->orderBy('created_at', 'desc')->get();

      return Datatables::of($data)
        ->addColumn('list_produk', function ($data) {
            return  '<div class="btn-group">' .
            '<a href="'.$data->stores->slug.'" target="_blank" class="detailProduk mx-1 btn btn-info btn-lg">Visit Store</a>'.
            '<a href="javascript::void(0)" data-id="'.$data->id.'" class="profitSeller btn btn-primary btn-lg">Profit</a>'.
            '</div>';
        })
        ->addColumn('card',function($data){
                return $data->type_card . ' - ' . $data->id_card;
        })
        ->addColumn('total_sales',function($data){
            $taken = Transaction::where('store_id', $data->stores->id)->where('is_confirmed', 'Y')
            ->where('status', 'The customer has received the order')->sum('profit');
            return '<span class="text-success">$'.$taken.'</span>';
        })
        ->rawColumns(['list_produk','total_sales','card'])
        ->addIndexColumn()
        ->make(true);
    }

    public function detailProduk($id){
        response()->json(['success' => 'Data']);
    }

    public function editProfit($id){
        $data = User::with('stores')->find($id);
        return response()->json(['data' => $data]);
    }

    public function updateProfit(Request $request){
        $data = User::with('stores')->find($request->id);
        $data->stores->profit = $request->profit;
        $data->stores->save();
        return response()->json(['success' => 'Profit successfully saved']);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
