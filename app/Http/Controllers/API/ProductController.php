<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return Product::with('category')->with('size')->latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:products|string|max:191',
            'category_id' => 'required|numeric',
            'size_id' => 'required|numeric',
            'price' => 'required|numeric:between:0,99.99'
        ]);

         return Product::create([
            'name' => $request['name'],
            'category_id' => $request['category_id'],
            'size_id' => $request['size_id'],
            'price' => $request['price']
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

       $this->validate($request, [
            'name' => 'required|unique:products|string|max:191',
            'category_id' => 'required|numeric',
            'size_id' => 'required|numeric',
            'price' => 'required|numeric:between:0,99.99'
        ]);

        $product->update($request->all());
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }
}
