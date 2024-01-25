<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\User;
use App\Mail\NotifMail;
use App\Models\Transaction;
use App\Models\BadgeSidebar;
use App\Models\RequestStore;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BadgeSidebarController;

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
        BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'Seller Candidates')->delete();
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
            return $data->type_card . '<br><a href="'.$data->photo_card.'" data-lightbox="roadtrip"><img src="'.$data->photo_card.'" width="90px"></a>';
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
            $acction =  '<div class="btn-group">' .
            '<a href="'.$data->stores->slug.'" target="_blank" class="detailProduk mr-1 btn btn-info btn-lg">Visit Store</a>'.
            '<a href="javascript::void(0)" data-id="'.$data->id.'" class="profitSeller btn btn-primary btn-lg mr-1">Profit</a>';

            if($data->stores->is_active == "OFF"){
                $acction .= '<a href="javascript::void(0)" data-id="'.$data->id.'" data-status="ON" class="statusStore btn btn-success btn-lg">ON</a>';
            }else{
                $acction .= '<a href="javascript::void(0)" data-id="'.$data->id.'" data-status="OFF" class="statusStore btn btn-danger btn-lg">OFF</a>';
            }

            $acction .='</div>';

            return $acction;
        })
        ->addColumn('card',function($data){
                return $data->type_card . '<br><a href="'.$data->photo_card.'" data-lightbox="roadtrip"><img src="'.$data->photo_card.'" width="90px"></a>';
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

    public function updateStatus(Request $request){
        $data = User::with('stores')->find($request->id);
        $data->stores->is_active = $request->is_active;
        $data->stores->save();

        $details = [
            'title' => 'Your shop status was changed by the admin',
            'body' => 'Your shop status has now been changed by the admin to '.$request->is_active.'. If you want to activate or deactivate the shop again, please send an email to cs@esellexpress.com',
        ];
        Mail::to($data->email)->send(new NotifMail($details));

        return response()->json(['success' => 'Store status updated successfully']);
    }

    public function indexRequestPenjual()
    {
        BadgeSidebar::where('user_id',Auth::user()->id)->where('name', 'Seller Request')->delete();
        return view('pages.reseller.request-penjual');
    }

    public function datatableRequestPenjual(){
        $data = RequestStore::with('stores.users')->orderBy('created_at', 'desc')->get();
      return Datatables::of($data)
        ->addColumn('action', function ($data) {
            $acction =  '<div class="btn-group">';

            if($data->is_acc){
                $acction .= '<a href="javascript::void(0)" class="statusStore btn btn-secondary mr-1 btn-lg disabled" >Success Acc</a>';
            }else{
                $acction .= '<a href="javascript::void(0)" data-id="'.$data->stores->users->id.'" data-status="'.$data->request.'" data-reqid="'.$data->id.'" class="statusStore btn btn-success mr-1 btn-lg">ACC Request</a>';
            }

            $acction .= '<a href="'.$data->stores->slug.'" target="_blank" class="detailProduk btn btn-info btn-lg">Visit Store</a>';

            $acction .='</div>';

            return $acction;
        })
        ->addColumn('created_at',function($data){
            return Carbon::parse($data->created_at)->format('F j, Y');
        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }


    public function requestStatus(Request $request){
        RequestStore::create([
            'store_id' => $request->store_id, 
            'request' => $request->is_active, 
        ]);

        BadgeSidebarController::send('Seller Request');

        return response()->json(['success' => 'Request sent successfully']);
    }

    public function requestupdateStatus(Request $request){
        $data = User::with('stores')->find($request->id);
        $data->stores->is_active = $request->is_active;
        $data->stores->save();
        
        $reqStore = RequestStore::find($request->reqid);
        $reqStore->is_acc = 1;
        $reqStore->save();

        $details = [
            'title' => 'Store status request has been accepted',
            'body' => 'Congratulations. The shop status request has been received, now your shop is '.$request->is_active.'. To request off/on again, please access your shop dashboard',
        ];
        Mail::to($data->email)->send(new NotifMail($details));
        return response()->json(['success' => 'Store status request has been accepted']);
    }
}
