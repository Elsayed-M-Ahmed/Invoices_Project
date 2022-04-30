<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\departments;
use App\Models\invoices;

class CustomerReports extends Controller
{
    public function index() {
        $departments = departments::all();
        return view('reports.customer_reports' , [
            'departments' => $departments,
        ]);
        
    }

    public function Search_customers(Request $request) {
        // return $request;
        if ($request->department && $request->product &&$request->start_at == '' && $request->end_at == '') {
            $departments = departments::all();
            $invoices = invoices::select('*')->where('department_id' ,'=' , $request->department)->where('product','=',$request->product)->get();
            return view('reports.customer_reports' ,['departments' => $departments])->withDetails($invoices);
        }else{
            $start_at = Date($request->start_at);
            $end_at = Date($request->end_at);

            $invoices = invoices::whereBetween('invoice_Date' , [$start_at,$end_at])->where('department_id' ,'=' , $request->department)->where('product','=',$request->product)->get();
            $departments = departments::all();

            return view('reports.customer_reports' ,[
                'departments' => $departments,
                ])->withDetails($invoices);
        }
    }
}
