<?php

namespace App\Http\Controllers;

use App\Models\departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = departments::all();
        return view('departments.departments' , [
            "departments" => $departments,
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

            $ValidateData = $request->validate([
                "department_name" => "required|max:50" ,
                "description" => "required",
            ]);
            departments::create([
                'department_name' => $request->department_name,
                'description' => $request->description,
                'created_by' => (Auth()->user()->name),
            ]);
            session()->flash('Add' , 'The element Added successfully');
            return redirect('/department');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\departments  $departments
     * @return \Illuminate\Http\Response
     */
    public function show(departments $departments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\departments  $departments
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $departments = departments::find($id);
        return view('/department' , ['departments' => $departments]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\departments  $departments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return $request;
        $id = $request->id;
        $ValidateData = $request->validate([
            "section_name" => "required|max:50|unique:departments,department_name,".$id ,
            "description" => "required",
        ]);

        $departments = departments::find($id);

        $department = departments::where('id' , $id)
                        ->update([
                            "department_name" => $request->input('section_name'),
                            "description" => $request->input('description'),
                        ]);
        session()->flash('Edit' , 'The element updated successfully');
        return redirect('/department');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\departments  $departments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $department = departments::find($id)->delete();
        session()->flash('Delete' , 'The element deleted successfully');
        return redirect('/department');
    }
}
