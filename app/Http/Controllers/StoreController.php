<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function ecommerce(){
        $ecommerce = Store::where('user_id',Auth::user()->id)->first();
        return view('pages.reseller.ecommerce-information',compact('ecommerce'));
    }
    public function ecommerceadd(Request $request){
        $ecommerce = Store::where('user_id',Auth::user()->id)->first();
        if(! empty($ecommerce)){
            if($request->name){
                $ecommerce->name = $request->name;
            }
            if($request->hasFile('logo')){
                $path = $request->file('logo')->store('stores','public');
                $logo = 'storage/'.$path;

                $ecommerce->logo = $logo;
            }
            $ecommerce->save();
        }else{
            $path = $request->file('logo')->store('stores','public');
            $request['logo'] = 'storage/'.$path;
            $ecommerce = Store::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'slug' => 'bj20andbw01js1',
                'logo' => $request['logo'],
            ]);
        }
        
        return view('pages.reseller.ecommerce-information',compact('ecommerce'));
    }


    public function stores(){
        $stores = Store::where('user_id',Auth::user()->id)->first();
        return view('pages.penjual.store-information',compact('stores'));
    }
    public function storesadd(Request $request){
        $stores = Store::where('user_id',Auth::user()->id)->first();
        if(! empty($stores)){
            if($request->name){
                $stores->name = $request->name;
                $stores->slug = Str::slug($request->name);
            }
            if($request->hasFile('logo')){
                $path = $request->file('logo')->store('stores','public');
                $logo = 'storage/'.$path;

                $stores->logo = $logo;
            }
            $stores->save();
        }else{
            $path = $request->file('logo')->store('stores','public');
            $request['logo'] = 'storage/'.$path;
            $stores = Store::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'logo' => $request['logo'],
            ]);
        }
        
        return view('pages.penjual.store-information',compact('stores'));
    }
}
