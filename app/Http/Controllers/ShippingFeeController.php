<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingFee;

class ShippingFeeController extends Controller
{
    public function index()
    {
        $fee = ShippingFee::first();
        return view('pages.reseller.delivery-service', compact('fee'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'fee' => 'required',
        ]);

        $fee = ShippingFee::first();
        $fee->update([
            'fee' => $request->fee,
        ]);

        return response()->json(['success' => true]);
    }
}
