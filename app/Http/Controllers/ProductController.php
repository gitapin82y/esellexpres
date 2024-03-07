<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductGallery;
use App\Models\ProductStore;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\ProductRequest;
use App\Models\Store;
use App\Models\Category;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->path() == 'products-list') {
            // Produk yang diambil penjual
            $stores = Store::with('products')->where('user_id', Auth::user()->id)->first();
            $products = [];
            foreach ($stores->products as $key => $product) {
                $products[$key] = Product::where('id', $product->product_id)
                    ->with(['categories', 'galleries' => function ($query) {
                        $query->where('is_default', 1);
                    }])
                    ->latest()
                    ->first();
            }
        } else {
            if (Auth::user()->role == 1) {
                // Produk yang disediakan reseller
                $products = Product::with(['categories', 'galleries' => function ($query) {
                    $query->where('is_default', 1);
                }])->latest()->get();
            } else {
                // List produk reseller yang belum diambil penjual
                $stores = Store::with('products')->where('user_id', Auth::user()->id)->first();
                $kalsu = [];
                foreach ($stores->products as $key => $product) {
                    $kalsu[$key] = Product::where('id', $product->product_id)->first()->id;
                }
                $products = Product::with(['categories', 'galleries' => function ($query) {
                    $query->where('is_default', 1);
                }])->latest()->whereNotIn('id', $kalsu)->get();
            }
        }
    
        $searchEmpty = false;
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $products = collect($products)->filter(function ($product) use ($searchTerm) {
                return stripos($product->name, $searchTerm) !== false;
            })->values();
            if($products){
                $searchEmpty = true;
            }
        }
    
        return view('pages.products.index', ['products' => $products,'searchEmpty' => $searchEmpty]);
    }
    


    public function takeProduct($id){
        $product = Product::findOrFail($id);
        $storeid = Store::where('user_id',Auth::user()->id)->first()->id;
        ProductStore::create([
            'store_id'=> $storeid,
            'product_id'=> $product->id,
        ]);
        Alert::toast('Taken successfully', 'success');
        return back();
    }

    public function deleteProduct($id, $store){
        $product = Product::findOrFail($id);
        $storeid = Store::where('name', $store)->first()->id;
        ProductStore::where([
            'store_id'=> $storeid,
            'product_id'=> $product->id,
        ])->delete();
        Alert::toast('Return successfully', 'success');
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $request->validate([
            'photo_input' => 'required',
            'photo' => 'required',
            'category_id' => 'required'
        ], [
            'category_id.required' => 'The category field is required.',
            'photo_input.required' => 'The photo you uploaded has not been cropped, upload the product photo and click the crop button.'
        ]);
    
        $request['total_sold'] = $request->total_sold ?? 0;
        $request['total_views'] = $request->total_views ?? 0;
        
        $productData = $request->except(['photo', 'photo_input']) + [
            'slug' => Str::slug($request->name)
        ];
    
        $product = Product::create($productData);
    
        $image_parts = explode(";base64,", $request->photo_input);
        $image_base64 = base64_decode($image_parts[1]);
        $image = imagecreatefromstring($image_base64);
        $imageName = uniqid() . '.png';
        $imageFullPath = public_path('storage/products/') . $imageName;
        imagepng($image, $imageFullPath);
    
        ProductGallery::create([
            'products_id' => $product->id,
            'photo' => 'storage/products/' . $imageName,
            'is_default' => 1,
        ]);
    
        Alert::toast('Add product successfully', 'success');
    
        return redirect()->route('products.index');
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
        $product = Product::with(['galleries' => function ($query) {
            $query->where('is_default',1)->first();
        }])->findOrFail($id);
        return view('pages.products.edit',['product'=>$product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $request->validate([
            'category_id' => 'required'
        ],[
            'category_id.required' => 'The category field is required.'
        ]);


        if($request['total_views'] == null)
        $request['total_views'] = 0;

        if($request['total_sold'] == null)
        $request['total_sold'] = 0;

        $data = $request->except(['photo','photo_input']);
        $data['slug'] = Str::slug($request->name);
        $product = Product::findOrFail($id);
        $product->update($data);

        if($request->hasFile('photo')){
            $request->validate([
                'photo_input' => 'required',
            ], [
                'photo_input.required' => 'The photo you uploaded has not been cropped, upload the product photo and click the crop button.'
            ]);
                $image_parts = explode(";base64,", $request->photo_input);
                $image_base64 = base64_decode($image_parts[1]);
                $image = imagecreatefromstring($image_base64);
                $imageName = uniqid() . '.png';
                $imageFullPath = public_path('storage/products/') . $imageName;
                imagepng($image, $imageFullPath);
    
                ProductGallery::where('products_id',$id)->update([
                    'photo' => 'storage/products/' . $imageName,
                ]);   
            
        }
        Alert::toast('Edit product successfully', 'success');
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        ProductStore::where([
            'product_id'=> $product->id,
        ])->delete();

        TransactionDetail::where([
            'product_id'=> $product->id,
        ])->delete();

        ProductGallery::where('products_id',$id)->delete();
        Product::destroy($id);

        Alert::toast('Deleted product successfully', 'success');
        return back();
    }

    public function gallery($id)
    {
        $galleries = ProductGallery::with('product')->where('products_id',$id)->where('is_default',0)->get();
        $products = Product::find($id);

        return view('pages.products.gallery',['galleries' => $galleries,'products' => $products]);
    }


    public function productSeller($nameStore){
        $stores = Store::with('products')->where('slug',$nameStore)->first();
        if(empty($stores)){
            return redirect()->back();
        }
         // Mendapatkan produk berdasarkan total_sold secara descending
        $products = Product::whereIn('id', $stores->products->pluck('product_id'))
        ->with(['categories', 'galleries' => function ($query) {
            $query->where('is_default', 1);
        }])
        ->orderBy('total_sold', 'desc')
        ->take(6) // Mengambil hanya 6 produk
        ->get();

        Session::put('namaStores', $nameStore);

        return view('pages.penjual.index', [
            'products' => $products,
            'profit' => $stores->profit,
            'store' => $stores->name
        ]);
    }

    public function allProduct(Request $request, $nameStore){
        $store = Store::where('slug', $nameStore)->first();
        
        if (empty($store)) {
            return redirect()->back();
        }
    
        $query = DB::table('products')
            ->join('product_stores', 'products.id', '=', 'product_stores.product_id')
            ->whereIn('product_stores.store_id', [$store->id])
            ->leftJoin('product_galleries', function ($join) {
                $join->on('products.id', '=', 'product_galleries.products_id')
                    ->where('product_galleries.is_default', 1);
            })
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.category_id',
                'products.promo_price',
                'products.total_views',
                'products.total_sold',
                'products.slug',
                'products.name',
                'products.price',
                'product_galleries.photo',
                'product_galleries.is_default',
                'categories.name as category_name'
            );
    
        // Filter by category
        if ($request->has('category') && $request->input('category') != '') {
            $query->where('products.category_id', $request->input('category'));
        }
    
        // Search by name
        if ($request->has('search') && $request->input('search') != '') {
            $query->where('products.name', 'like', '%' . $request->input('search') . '%');
        }
        
        // if(!$query){
        //     return back()->with('toast_info','the product is not yet available');
        // }

        $products = $query->get();
        $categories = Category::all();
        $profit = $store->profit;
    
        return view('pages.penjual.all-products', compact('products', 'categories', 'profit'));
    }
    

    public function detailProductSeller($stores,$product){

        $store = Store::where('slug', $stores)->first();
        
        $detail = ProductStore::whereHas('products', function ($query) use ($product) {
            $query->where('slug', $product);
        })->whereHas('stores', function ($query) use ($store) {
            $query->where('slug', $store->slug);
        })->with('stores', 'products')->first();

        if(!$detail){
            return back();
        }
        
        Product::where('slug',$product)->increment('total_views', 1);
        

        $products = Product::join('product_stores', 'products.id', '=', 'product_stores.product_id')
        ->whereIn('product_stores.store_id', [$store->id])
        ->leftJoin('product_galleries', function ($join) {
            $join->on('products.id', '=', 'product_galleries.products_id')
                ->where('product_galleries.is_default', 1);
        })
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select(
            'products.id',
            'products.category_id',
            'products.promo_price',
            'products.total_views',
            'products.total_sold',
            'products.slug',
            'products.name',
            'products.price',
            'product_galleries.photo',
            'product_galleries.is_default',
            'categories.name as category_name'
        )
        ->orderByDesc('products.created_at') // Menambahkan ini untuk mengurutkan berdasarkan produk terbaru
        ->take(4) // Mengambil hanya 4 produk
        ->get();
        $profit = $store->profit;

        return view('pages.penjual.product',['detail'=>$detail,'products'=>$products,'profit'=>$profit]);
    }
}
