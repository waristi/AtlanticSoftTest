<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

use App\Product;
use App\ProductGaleria;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    
    // VIEW PRODUCT
    public function index()
    {
        return Product::with('category', 'galeria')->get();
    }

    // VIEW PAGINATE
    public function paginate($id)
    {
        return Product::with('category')->where('categories_id', $id)->paginate(12);
    }

    // SAVE PRODUCT
    public function store(Request $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->weight = $request->weight;
        $product->categories_id = $request->categories_id;
        $product->save();

        if($request->images)
        {
            for ($i = 0; $i < count($request->images); $i++) {
                $image = $request->images[$i];

                $galeria = new ProductGaleria();
                $galeria->name = $product->id . '_' . $i . '.jpg';
                $galeria->products_id = $product->id;
                $galeria->save();

                Image::make($image)->save('storage/product/' . $galeria->name);
            }
        }
    }

    // SHOW PRODUCT PARENT
    public function show($id)
    {
        return Product::with('category', 'galeria')->find($id);
    }

    // UPDATE PRODUCT
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->weight = $request->weight;
        $product->categories_id = $request->categories_id;
        $product->save();

        if($request->images)
        {
            $borrar = ProductGaleria::where('products_id', $id)->get();
            for ($i = 0; $i < count($borrar); $i++) {
                if(file_exists('storage/product/' . $borrar[$i]->name))
                    File::delete('storage/product/' . $borrar[$i]->name);
                $borrar[$i]->delete();
            }
        
            for ($i = 0; $i < count($request->images); $i++) {
                $image = $request->images[$i];
                //dd($image);

                $galeria = new ProductGaleria();
                $galeria->name = $product->id . '_' . $i . '.jpg';
                $galeria->products_id = $product->id;
                $galeria->save();

                Image::make($image)->save('storage/product/' . $galeria->name);
            }
        }
    }

    public function upload(Request $request, $id)
    {
        
    }

    // DELETE PRODUCT
    public function destroy($id)
    {
        try{
            $product = Product::find($id);
            $product->delete();

        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() == "23000"){ 
                return response()->json(['message' => "No puede borrar el Producto que esta siendo usada."], 400);
            }
        }
    }
}
