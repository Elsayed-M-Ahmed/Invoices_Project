<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\departments;
use App\Models\invoices_details;
use App\Models\invoice_attachments;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Notifications\addinvoice;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\invoicesExport;


class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices_data = invoices::all();
        return view('invoices.invoices',[
            'invoices_data' => $invoices_data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = departments::all();
        return view('invoices/addinvoice' , [
            'departments' => $departments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'department_id' => $request->department,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'Payment_Date' => $request->Due_date,
        ]);

        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'department' => $request->department,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'Payment_Date' => $request->Due_date,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {
            $invoice_id = invoices::latest()->first()->id;
            $image = $request->file('pic');
            $image_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;
            invoice_attachments::create([
                'file_name' => $image_name ,
                'invoice_number' => $invoice_number ,
                'Created_by' => Auth::user()->name ,
                'invoice_id' => $invoice_id ,
            ]);
            $request->pic->move(public_path('Attachments/' . $invoice_number) , $image_name);
        }



        // $user = User::first();
        // Notification::send($user ,new Addinvoice($invoice_id));

        $user = User::get();
        $invoices = invoices::latest()->first();
        Notification::send($user ,new \App\Notifications\addinvoice($invoices));
        session()->flash('Add' , 'The invoice has been added successfully');
        return redirect('/invoices');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show(invoices $invoices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = invoices::where('id' , $id)->first();
        $departments = departments::all();
        return view('invoices.edit_invoice' , [
            'invoices' => $invoices ,
            "departments" => $departments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices $invoices)
    {
        // return $request;
        $invoice = invoices::findOrFail($request->invoice_id);
        $invoice->update([
            'invoice_number' => $request->invoice_number ,
            'invoice_Date' => $request->invoice_Date ,
            'department_id' => $request->department ,
            'product' => $request->product ,
            'Amount_collection' => $request->Amount_collection ,
            'Amount_Commission' => $request->Amount_Commission ,
            'Discount' => $request->Discount ,
            'Rate_VAT' => $request->Rate_VAT ,
            'Value_VAT' => $request->Value_VAT ,
            'Total' => $request->Total ,
            'note' => $request->note ,
        ]);
        session()->flash('edit_invoice' , 'تم تحديث الفاتوره بنجاح');
        return redirect('invoices');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $delete_invoice_attachment = invoice_attachments::findOrFail($request->id_file);
        $delete_invoice_attachment ->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
        session()->flash('delete' , 'File has deleted sucessfully');
        return back();


        
    }

    public function GetProducts($id) {
        $products = DB::table('products')->where('department_id' , $id)->pluck('product_name' , 'id');
        return json_encode($products);
    }

    public function GetInvoiceDetails($id) {
        $invoices = invoices::where('id' , $id)->first();
        $invoices_details = DB::table('invoices_details')->where('id_Invoice' , $id)->get();
        $invoices_attachments = DB::table('invoice_attachments')->where('invoice_id' , $id)->get();
        $departments = departments::all();
        return view('invoices.invoicedetails' , [
            'invoices' => $invoices ,
            'departments' => $departments,
            'invoices_details' => $invoices_details ,
            'invoices_attachments' => $invoices_attachments,
        ]);
    }


    public function open_file($invoice_number,$file_name){
        $show_file = storage::disk('public_uploads')->getDriver()->getAdapter()->applypathprefix($invoice_number.'/'.$file_name);
        return response()->file($show_file); 
    }

    public function get_file($invoice_number,$file_name) {
        $download_file = storage::disk('public_uploads')->getDriver()->getAdapter()->applypathprefix($invoice_number.'/'.$file_name);
        return response()->download($download_file);
    }

    public function add_new_attachment(Request $request) {
        $file = $request->file('file_name');
        $file_name = $file->getClientOriginalName();
        invoice_attachments::create([
            'file_name' => $file_name,
            'invoice_number' => $request->invoice_number,
            'invoice_id' => $request->invoice_id,
            'Created_by' => Auth::user()->name,
        ]);
        $request->file_name->move(public_path('Attachments/' . $request->invoice_number) , $file_name);
        session()->flash('Add' , 'The file has added successfully');
        return back();
    }


    public function status_edit($id) {
        $invoices = invoices::where('id' , $id)->first();
        return view('invoices/status_update' ,[
            'invoices' => $invoices,
        ]);
    }

    public function update_invoice_status(Request $request) {
        //return $request;

        $invoices = invoices::where('id' , $request->invoice_id);
        $invoice_details = invoices_details::where('id_Invoice' , $request->invoice_id);
        
        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'invoice_number' => $request->invoice_number,
                'invoice_Date' => $request->invoice_Date,
                'Due_date' => $request->Due_date,
                'department_id' => $request->department,
                'product' => $request->product,
                'Amount_collection' => $request->Amount_collection,
                'Discount' => $request->Discount,
                'Rate_VAT' => $request->Rate_VAT,
                'Value_VAT' => $request->Value_VAT,
                'Total' => $request->Total,
                'note' => $request->note,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'Payment_Date' => $request->Payment_Date,
            ]);

            $invoice_details->create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'department' => $request->department,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'Payment_Date' => $request->Payment_Date,
                'note' => $request->note,
                'user' => Auth::user()->name,
            ]);
        }else{
            $invoices->update([
                'invoice_number' => $request->invoice_number,
                'invoice_Date' => $request->invoice_Date,
                'Due_date' => $request->Due_date,
                'department_id' => $request->department,
                'product' => $request->product,
                'Amount_collection' => $request->Amount_collection,
                'Discount' => $request->Discount,
                'Rate_VAT' => $request->Rate_VAT,
                'Value_VAT' => $request->Value_VAT,
                'Total' => $request->Total,
                'note' => $request->note,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'Payment_Date' => $request->Payment_Date,
            ]);

            $invoice_details->create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'department' => $request->department,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'Payment_Date' => $request->Payment_Date,
                'note' => $request->note,
                'user' => Auth::user()->name,
            ]);
        }
        session()->flash('Edit' , 'تم تحديث الفاتوره بنجاح');
        return redirect('/invoices');
    }

    public function delete_invoice(Request $request) {
        $id = $request->invoice_id;
        $invoices = invoices::where('id' , $id)->first();
        $attachments = invoice_attachments::where('invoice_id' , $id)->first();

        if (!empty($attachment->invoice_number)) {
        Storage::disk('public_uploads')->deleteDirectory($attachments->invoice_number);
        }
        $invoices->forceDelete();
        session()->flash('soft_delete_for_invoice');
        return redirect('/invoices');
    }

    public function archive_invoice(Request $request) {
        $id = $request->invoice_id;
        $invoices = invoices::where('id' , $id)->first();
        $attachment = invoice_attachments::where('invoice_id' , $id)->first();

        if (!empty($attachment->invoice_number)) {
        Storage::disk('public_uploads')->deleteDirectory($attachment->invoice_number);
        }
        $invoices->Delete();
        session()->flash('soft_delete_for_invoice');
        return redirect('/archived_invoices');
    }

    public function paid_invoices() {
        $invoices_data = invoices::where('Value_Status' , '1')->get();
        return view('invoices.paid_invoices',[
            'invoices_data' => $invoices_data,
        ]);
    }

    public function unpaid_invoices() {
        $invoices_data = invoices::where('Value_Status' , '2')->get();
        return view('invoices.unpaid_invoices',[
            'invoices_data' => $invoices_data,
        ]);
    }

    public function partial_paid_invoices() {
        $invoices_data = invoices::where('Value_Status' , '3')->get();
        return view('invoices.partial_paid_invoices',[
            'invoices_data' => $invoices_data,
        ]);
    }

    public function Print_invoice($id) {
        $invoices = invoices::where('id' , $id)->first();
        return view('invoices.print_invoice' , [
            'invoices' => $invoices,
        ]);
    }

    public function export() 
    {
        return Excel::download(new InvoicesExport, 'invoice.xlsx');
       
    }

    public function MarkAsRead_all (Request $request)
    {

        $userUnreadNotification= auth()->user()->unreadNotifications;

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }


    }
}
