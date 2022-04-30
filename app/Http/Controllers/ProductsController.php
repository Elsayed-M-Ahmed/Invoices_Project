<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\departments;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = departments::all();
        $products = products::all();
        return view('products.products' ,[
            'departments' => $departments,
            'products' => $products,
        ]);
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
        $products_validation = $request->validate([
            'Product_name' => 'required|unique:products|max:50',
            'department_id'=>'required|integer',
        ]);
        $products = products::create([
            'Product_name' => $request->Product_name,
            'description' => $request->description,
            'department_id'=>$request->department_id,
        ]);
        session()->flash('Add' , 'Product has been added successfully');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, products $products)
    {
        $id = departments::where('department_name' , $request->department_name)->first()->id;
        $product_id = $request->pro_id;
        $product = products::find($id);

        $product = products::where('id' , $product_id)
                    ->update([
                    'Product_name' => $request->Product_name,
                    'description' => $request->description,
                    'department_id' => $id,
                ]);

        session()->flash('Edit' , 'Product has been updated successfuly');
        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request , products $products)
    {
        $product_id = $request->pro_id;
        $products = products::find($product_id)->delete();
        session()->flash('Delete' , 'Product has deleted successfully');
        return redirect('/products');
    }
}
