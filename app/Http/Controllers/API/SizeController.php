<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index()
    {
        return Size::latest()->paginate(10);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191'
        ]);

         return Size::create([
            'name' => $request['name']
        ]);
    }


    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:191'
        ]);

        $size->update($request->all());
    }



    public function destroy($id)
    {
        $size = Size::findOrFail($id);
        $size->delete();
    }
}
