<?php

namespace App\Http\Controllers;

use App\Models\DeliveryServices;
use App\Models\ShippingFee;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Carbon;

class DeliveryServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fee = ShippingFee::first();
        return view('pages.reseller.delivery-service',compact('fee'));
    }

    public function datatable(){
        $data = DeliveryServices::get();

      return Datatables::of($data)
        ->addColumn('action', function ($data) {
            return  '<div class="btn-group">' .
            '<a href="javascript::void(0)" data-id="'.$data->id.'" class="updateItems mx-1 btn btn-info btn-lg">'.
            '<label class="fa fa-edit"></label> Edit</a>' .
            '<a href="javascript::void(0)" data-id="'.$data->id.'" class="deleteItems btn btn-danger btn-lg" title="delete">' .
            '<label class="fa fa-times"></label> Delete</a>' .
            '</div>';
        })
        ->addColumn('created_at',function($data){
            return Carbon::parse($data->created_at)->format('d F Y');
        })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = DeliveryServices::updateOrCreate([
            'id' => $request->id
        ], [
            'name' => $request->name
        ]);
        return response()->json(['success' => 'Saved Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryServices  $deliveryServices
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $data = DeliveryServices::find($id);

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliveryServices  $deliveryServices
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryServices $deliveryServices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryServices  $deliveryServices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // DeliveryServices::findOrFail($request->id)->update($request->all());
        // return response()->json(['data' =>'Deleted Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryServices  $deliveryServices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        // return dd($id);
        DeliveryServices::where('id',$id)->delete();
        return response()->json(['success' =>'Deleted Successfully']);
    }
}
