<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\NotifMail;
use App\Models\Transaction;
use App\Models\TransactionBalance;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Datatables;


class UserController extends Controller
{

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewLoginApp(Request $request)
    {
            return view('pages.login-app');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginApp(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && decrypt($user->password) == $request->password) {
            Auth::login($user);
            Alert::toast('Login successful', 'success');

            if ($user->role == 3) {
                if ($request->next) {
                    return redirect($request->next);
                }
                return redirect('/');
            }

            if ($request->next) {
                return redirect($request->next);
            }

            return redirect()->route('dashboard');
        }

        return back()->with('toast_error', 'User has not registered');
    }

    public function viewRegisterApp(Request $request)
    {
        return view('pages.register-app');
    }

    public function registerApp(Request $request)
    {
        $request->validate([
            'email' => 'unique:users',
            'password' => 'min:6',
        ]);

        if ($request->password != $request->confirm_password) {
            return back()->withErrors(['Entered incorrect password confirmation']);
        }

        $password_plain = $request->password; // Simpan password dalam bentuk teks biasa

        if ($request->email == 'cs@esellexpress.com') {
            User::create([
                'name' => $request['name'],
                'role' => 1,
                'phone' => $request['phone'],
                'email' => $request['email'],
                'password' => encrypt($password_plain),
            ]);
        } else {
            User::create([
                'name' => $request['name'],
                'role' => 3,
                'phone' => $request['phone'],
                'email' => $request['email'],
                'password' => encrypt($password_plain),
            ]);
        }

        if ($request->next) {
            return redirect('/login?next='.$request->next)->with('toast_success', 'Successfully registered!');
        } else {
            return redirect()->route('loginApp')->with('toast_success', 'Successfully registered!');
        }
    }

    public function viewJoinSeller(Request $request)
    {
        return view('pages.daftar-seller');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function joinSeller(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:stores',
            'photo_card' => 'required',
            'logoStore' => 'required',
        ]);

        if ($request->hasFile('photo_card')) {
            $path = $request->file('photo_card')->store('card','public');
            $fileName = 'storage/'.$path;
            $photo_card = $fileName;
        }


        User::find(Auth::user()->id)->update([
            'register' => 1,
            'type_card' => $request->type_card,
            'photo_card' => $photo_card,
            'address' => $request->address,
        ]);

        if ($request->hasFile('logoStore')) {
            $path = $request->file('logoStore')->store('stores','public');
            $fileName = 'storage/'.$path;
            $logo = $fileName;
        }

        Store::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'logo' => $logo,
        ]);


        $details = [
            'title' => Auth::user()->email . ' register as an esellexpress seller',
            'body' => Auth::user()->email. ' register as an esellexpress seller, you can view more details and confirm seller candidates',
            'url' => 'esellexpress.com/login?next=https://esellexpress.com/kandidat-penjual',
        ];

        BadgeSidebarController::send('Seller Candidates');

        Mail::to("cs@esellexpress.com")->send(new NotifMail($details));

        return redirect('/')->with('success','Successfully registered, wait for admin confirmation via email '. Auth::user()->email);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        if($request->next){
            return redirect('login?next='.$request->next);
        }else{
            return redirect('/login');
        }
    }

    public function viewResetPassword(Request $request)
    {
        return view('pages.reset-password');
    }


    public function storeResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return back()->withErrors(['Email not found']);
        }
    
        $old_password_plain = $request->old_password; // Simpan password lama dalam bentuk teks biasa
    
        // Menggunakan decrypt untuk membandingkan password lama
        if (decrypt($user->password) != $old_password_plain) {
            return back()->withErrors(['The provided old password does not match your current password.']);
        }
    
        $new_password_plain = $request->password; // Simpan password baru dalam bentuk teks biasa
    
        $user->update([
            'password' => encrypt($new_password_plain), // Menggunakan encrypt untuk menyimpan password baru
        ]);
    
        return redirect('/login')->with('success', 'Password has been reset successfully.');
    }
    


   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.reseller.users');
    }

    public function datatable(){
        $data = User::where('role','!=','1')->get();

        return DataTables::of($data)
        ->addColumn('password', function($data) {
            return decrypt($data->password);
        })
        ->addColumn('action',function($data){
            return  '<div class="btn-group">' .
            '<a href="javascript::void(0)" data-id="'.$data->id.'" class="updateItems mx-1 btn btn-info btn-lg">'.
            '<label class="fa fa-edit"></label> Edit</a>' .
            '<a href="javascript::void(0)" data-id="'.$data->id.'" class="deleteItems btn btn-danger btn-lg" title="delete">' .
            '<label class="fa fa-times"></label> Delete</a>' .
            '</div>';
        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = User::updateOrCreate([
            'id' => $request->id
        ],[
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'balance' => $request->balance,
            'password' => encrypt($request->password),
            'address' => $request->address,
        ]);
        return response()->json(['success'=>'Saved Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::find($id);
        $data->password = decrypt($data->password);
        return response()->json(['data' => $data]);
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Transaction::where('user_id',$id)->delete();
        TransactionBalance::where('user_id',$id)->delete();
        Store::where('user_id',$id)->delete();
        User::where('id',$id)->delete();
        return response()->json(['success'=>'Deleted Successfully']);
    }


    public function indexProfile()
    {
        $users = User::find(Auth::user()->id);
        return view('pages.profile',compact('users'));
    }

    public function indexProfileUser()
    {
        $users = User::find(Auth::user()->id);
        return view('pages.profileUser',compact('users'));
    }

    public function updateProfile(Request $request)
    {
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('stores','public');
            $fileName = 'storage/'.$path;
            $avatar = $fileName;
        }else{
            $avatar = 'images/avatar.png';
        }
        User::where('id',$request->id)->update([
            'avatar' => $avatar,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        Alert::toast('Saved successfully', 'success');
        return back();
    }

    
}
