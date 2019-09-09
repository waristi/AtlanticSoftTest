<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use App\Category;

class CategoryController extends Controller
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

    // VIEW CATEGORY
    public function index()
    {
        return Category::with('children')
                        ->whereNull('parent')
                        ->get();
    }

    // SELECT PRODUCT
    public function select(){
        $validate = Category::whereNotNull('parent')->select('parent')->get()->toArray();

        return Category::with('children')
                ->whereNotIn('id', $validate)
                ->get();
    }

    public function paginate(){
        return Category::with('children')
                        ->whereNull('parent')
                        ->paginate(6);
    }

    public function paginate_show($id){
        return Category::with('children')
                        ->where('parent', $id)
                        ->paginate(6);
    }

    // SAVE CATEGORY
    public function store(Request $request)
    {
        $catagory = new Category();
        $catagory->name = $request->name;
        $catagory->parent = $request->parent;
        $catagory->save();

        if($request->image)
        {
            $image = $request->image;
            Image::make($image)->save('storage/' . $catagory->id . '.jpg');
        }

        return response()->json(['category_id' => 1], 200);  
    }

    // SHOW CATEGORY PARENT
    public function show($id)
    {
        return Category::with('children')->where('parent', $id)->get();
    }

    // UPDATE CATEGORY
    public function update(Request $request, $id)
    {
        $catagory = Category::find($id);
        $catagory->name = $request->name;
        $catagory->parent = $request->parent;
        $catagory->save();

        if($request->image)
        {
            $image = $request->image;
            Image::make($image)->save('storage/category/' . $catagory->id . '.jpg');
        }
    }


    // DELETE CATEGORY
    public function destroy($id)
    {
        try{
            $category = Category::find($id);
            $category->delete();

        } catch (\Illuminate\Database\QueryException $e) {
            if($e->getCode() == "23000"){ 
                return response()->json(['message' => "No puede borrar la categoria por que esta siendo usada."], 400);
            }
        }
    }

    
}
