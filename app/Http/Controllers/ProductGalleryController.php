<?php

namespace App\Http\Controllers;

use App\Models\ProductGallery;
use App\Models\Product;
use App\Http\Requests\ProductGalleryRequest;

class ProductGalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries = ProductGallery::with('product')->where('is_default',0)->get();
        return view('pages.product-galleries.index',['galleries'=>$galleries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('pages.product-galleries.create')->with(['product'=>Product::find($id)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductGalleryRequest $request)
    {
        $galleries = $request->all();
        $galleries['is_default'] = 0;
        $path = $request->file('photo')->store('products','public');
        $galleries['photo'] = 'storage/'.$path;
        ProductGallery::create($galleries);
        return redirect()->route('products.gallery',$request->products_id);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductGalleryRequest $request, $id)
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
        ProductGallery::destroy($id);
        return back()->with('toast_success', 'deleted successfully');
    }
}
