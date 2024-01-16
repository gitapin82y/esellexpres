<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\NotifMail;
use Illuminate\Support\Facades\Mail;


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
        $user = User::where('email',$request->email)->first();
        if($user && Hash::check($request->password, $user->password)){
                Auth::login($user);

                if($user->role == 3){
                    if($request->next){
                        return redirect($request->next)->with('toast_success', 'Login successful');
                    }
                    return redirect('/')->with('toast_success', 'Login successful');
                }
                if($request->next){
                    return redirect($request->next)->with('toast_success', 'Login successful');
                }

                return redirect()->route('dashboard')->with('toast_success', 'Login successful');
        }
        return back()->with('toast_error', 'User has not register');
        
    }

    public function viewRegisterApp(Request $request)
    {
        return view('pages.register-app');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function registerApp(Request $request)
    {
        $request->validate([
            'email' => 'unique:users',
            'password' => 'min:6',
        ]);

        if($request->password != $request->confirm_password){
            return back()->withErrors(['Entered incorrect password confirmation']);
        }
        if($request->email == 'cs@esellexpress.com'){
            User::create([
                'name' => $request['name'],
                'role' => 1,
                'phone' => $request['phone'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
        }else{
            User::create([
                'name' => $request['name'],
                'role' => 3,
                'phone' => $request['phone'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
        }
        if($request->next){
            return redirect('/login?next='.$request->next)->with('toast_success', 'Successfully registered!'. $request->next);
        }else{
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
            'type_card' => 'required',
            'logoStore' => 'required',
        ]);


        User::find(Auth::user()->id)->update([
            'register' => 1,
            'type_card' => $request->type_card,
            'id_card' => $request->id_card,
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
            'url' => 'esellexpress.com/login?next=esellexpress.com/kandidat-penjual',
        ];

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function edit(User $User)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $User)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $User)
    {
        //
    }
}
