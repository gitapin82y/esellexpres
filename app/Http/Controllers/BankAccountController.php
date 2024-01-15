<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.reseller.bank-account');
    }

    public function datatable(){
        $data = BankAccount::with(['user' => function ($query){
            $query->where('id', Auth::user()->id);
        }])->get();

        return DataTables::of($data)
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
        $data = BankAccount::updateOrCreate([
            'id' => $request->id
        ],[
            'user_id' => Auth::user()->id,
            'type_payment' => $request->type_payment,
            'account_number' => $request->account_number,
        ]);
        return response()->json(['success'=>'Saved Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = BankAccount::find($id);

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(BankAccount $bankAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BankAccount::where('id',$id)->delete();
        return response()->json(['success'=>'Deleted Successfully']);
    }
}
