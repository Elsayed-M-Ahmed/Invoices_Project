<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoices;

class InvoicesReport extends Controller
{
    public function index() {
        return view('reports.invoices_reports');
    }

    public function Search_invoices(Request $request) {
        $rdio = $request->rdio ;

        if ($rdio == 1) {
            // فى حاله البحث بدون تاريخ
            if ($request-> type && $request->start_at == '' && $request->end_at == '') {
                $invoices = invoices::select('*')->where('Status' ,'=', $request->type)->get();
                $type = $request->type;
                return view('reports.invoices_reports' , [
                    'type' => $type,
                ])->withDetails($invoices);
            }else{
                $start_at = Date($request->start_at);
                $end_at = Date($request->end_at);
                $type = $request->type;
    
                $invoices = invoices::whereBetween('invoice_Date' , [$start_at,$end_at])->where('Status' , '=' , $type)->get();
                return view('reports.invoices_reports' , [
                    'type' => $type,
                    'start_at' => $start_at,
                    'end_at' => $end_at
                ])->withDetails($invoices);
            }
        }else{
            $invoices = invoices::select('*')->where('invoice_number' , '=' , $request->invoice_number)->get();
            return view('reports.invoices_reports')->withDetails($invoices);;
        }
    }


}
