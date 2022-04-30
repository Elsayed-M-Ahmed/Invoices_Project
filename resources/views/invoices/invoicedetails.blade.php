@extends('layouts.master')
@section('title')
تفاصيل الفاتوره {{ $invoices->invoice_number }}
@stop
@section('css')
<!---Internal  Prism css-->
<link href="{{URL::asset('assets/plugins/prism/prism.css')}}" rel="stylesheet">
<!---Internal Input tags css-->
<link href="{{URL::asset('assets/plugins/inputtags/inputtags.css')}}" rel="stylesheet">
<!--- Custom-scroll -->
<link href="{{URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">تفاصيل الفاتوره {{ $invoices->invoice_number }}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الفواتير</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')

@if (session()->has('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
				<!-- row -->
				<div class="row">
           {{-- new --}}

           <div class="panel panel-primary tabs-style-2">
            <div class=" tab-menu-heading">
                <div class="tabs-menu1">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs main-nav-line">
                        <li><a href="#tab4" class="nav-link active" data-toggle="tab">معلومات الفاتوره</a></li>
                        <li><a href="#tab5" class="nav-link" data-toggle="tab">حالات الفاتوره</a></li>
                        <li><a href="#tab6" class="nav-link" data-toggle="tab">مرفقات الفاتوره</a></li>
                    </ul>
                </div>
            </div>
            <div class="panel-body tabs-menu-body main-content-body-right border">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab4">
                        <table id="example1" class="table key-buttons text-md-nowrap">
                            {{-- عشان تخلى الجدول فيه تصدير pdf  او  exccel --}}
                        {{-- <table id="example" class="table key-buttons text-md-nowrap"> --}}
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الفاتوره</th>
                                    <th class="border-bottom-0">تاريخ الفاتوره</th>
                                    <th class="border-bottom-0">معاد الدفع</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">نسبه الضريبه</th>
                                    <th class="border-bottom-0">قيمه الضريبه</th>
                                    <th class="border-bottom-0">الاجمالى</th>
                                    <th class="border-bottom-0">الحاله</th>
                                    <th class="border-bottom-0">الملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{ $invoices->invoice_number }}</td>
                                    <td>{{ $invoices->invoice_Date }}</td>
                                    <td>{{ $invoices->Due_date }}</td>
                                    <td>{{ $invoices->product }}</td>
                                    <td>{{ $invoices->department->department_name }}</td>
                                    <td>{{ $invoices->Discount }}</td>
                                    <td>{{ $invoices->Rate_VAT }}</td>
                                    <td>{{ $invoices->Value_VAT }}</td>
                                    <td>{{ $invoices->Total }}</td>
                                    <td> @if ($invoices->Value_Status == 1)
                                        <span class="text-success">{{ $invoices->Status }}</span>
                                    @elseif($invoices->Value_Status == 2)
                                        <span class="text-danger">{{ $invoices->Status }}</span>
                                    @else
                                        <span class="text-warning">{{ $invoices->Status }}</span>
                                    @endif</td>
                                    <td>{{ $invoices->note }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tab5">
                        <table id="example1" class="table key-buttons text-md-nowrap">
                            {{-- عشان تخلى الجدول فيه تصدير pdf  او  exccel --}}
                        {{-- <table id="example" class="table key-buttons text-md-nowrap"> --}}
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الفاتوره</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الحاله</th>
                                    <th class="border-bottom-0">تاريخ لدفع</th>
                                    <th class="border-bottom-0">الملاحظات</th>
                                    <th class="border-bottom-0">تاريخ الفاتوره</th>
                                    <th class="border-bottom-0">اسم المستخدم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0
                                @endphp
                                @foreach ($invoices_details as $invoice_details)
                                @php
                                $i++
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $invoice_details->invoice_number }}</td>
                                    <td>{{ $invoice_details->product }}</td>
                                    <td>{{ $invoices->department->department_name }}</td>
                                    <td> @if ($invoice_details->Value_Status == 1)
                                        <span class="text-success">{{ $invoice_details->Status }}</span>
                                    @elseif($invoice_details->Value_Status == 2)
                                        <span class="text-danger">{{ $invoice_details->Status }}</span>
                                    @else
                                        <span class="text-warning">{{ $invoice_details->Status }}</span>
                                    @endif</td>
                                    <td>{{ $invoice_details->Payment_Date }}</td>
                                    <td>{{ $invoice_details->note }}</td>
                                    <td>{{ $invoice_details->created_at }}</td>
                                    <td>{{ $invoice_details->user }}</td>
                                </tr>                                           
                                @endforeach
                            </tbody>
                        </table>  
                    </div>

                    
                    <div class="tab-pane" id="tab6">
                        @can('اضافة مرفق')
                        <form action="{{ url('/InvoiceAttachments') }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile"
                                    name="file_name" required>
                                <input type="hidden" id="customFile" name="invoice_number"
                                    value="{{ $invoices->invoice_number }}">
                                <input type="hidden" id="invoice_id" name="invoice_id"
                                    value="{{ $invoices->id }}">
                                <label class="custom-file-label" for="customFile">حدد
                                    المرفق</label>  
                            </div><br><br>
                            <button type="submit" class="btn btn-primary btn-sm "
                                name="uploadedFile">تاكيد</button>
                        </form>
                        @endcan
                        <table id="example1" class="table key-buttons text-md-nowrap">
                            {{-- عشان تخلى الجدول فيه تصدير pdf  او  exccel --}}
                        {{-- <table id="example" class="table key-buttons text-md-nowrap"> --}}
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">اسم الملف</th>
                                    <th class="border-bottom-0">تاريخ الانشاء</th>
                                    <th class="border-bottom-0">منشأ الفاتوره</th>
                                    <th class="border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0
                                @endphp
                                @foreach ($invoices_attachments as $invoice_attachments)
                                @php
                                $i++
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $invoice_attachments->file_name }}</td>
                                    <td>{{ $invoice_attachments->created_at }}</td>
                                    <td>{{ $invoice_attachments->Created_by }}</td>
                                    <td><a class="btn btn-outline-success btn-sm"
                                        href="{{ url('View_file') }}/{{ $invoices->invoice_number }}/{{ $invoice_attachments->file_name }}"
                                        role="button"><i class="fas fa-eye"></i>&nbsp;
                                        عرض</a>
            
                                    <a class="btn btn-outline-info btn-sm"
                                        href="{{ url('download') }}/{{ $invoices->invoice_number }}/{{ $invoice_attachments->file_name }}"
                                        role="button"><i
                                            class="fas fa-download"></i>&nbsp;
                                        تحميل</a>
            
                                        @can('حذف المرفق')
                                    <button class="btn btn-outline-danger btn-sm"
                                        data-toggle="modal"
                                        data-file_name="{{ $invoice_attachments->file_name }}"
                                        data-invoice_number="{{ $invoice_attachments->invoice_number }}"
                                        data-id_file="{{ $invoice_attachments->id }}"
                                        data-target="#delete_file">حذف
                                    </button>
                                    @endcan
                                    </td>
                                </tr>                                           
                                @endforeach
                            </tbody>
                    </div>
                </div>
            </div>
        </div>
        


 
<div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">حذف المرفق</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ route('delete_file') }}" method="post">

            {{ csrf_field() }}
            <div class="modal-body">
                <p class="text-center">
                <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                </p>

                <input type="hidden" name="id_file" id="id_file" value="">
                <input type="hidden" name="file_name" id="file_name" value="">
                <input type="hidden" name="invoice_number" id="invoice_number" value="">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                <button type="submit" class="btn btn-danger">تاكيد</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
				
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!--Internal  Datepicker js -->
<script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!-- Internal Select2 js-->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!-- Internal Jquery.mCustomScrollbar js-->
<script src="{{URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<!-- Internal Input tags js-->
<script src="{{URL::asset('assets/plugins/inputtags/inputtags.js')}}"></script>
<!--- Tabs JS-->
<script src="{{URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
<script src="{{URL::asset('assets/js/tabs.js')}}"></script>
<!--Internal  Clipboard js-->
<script src="{{URL::asset('assets/plugins/clipboard/clipboard.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/clipboard/clipboard.js')}}"></script>
<!-- Internal Prism js-->
<script src="{{URL::asset('assets/plugins/prism/prism.js')}}"></script>
<script>
    $('#delete_file').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id_file = button.data('id_file')
        var file_name = button.data('file_name')
        var invoice_number = button.data('invoice_number')
        var modal = $(this)
        modal.find('.modal-body #id_file').val(id_file);
        modal.find('.modal-body #file_name').val(file_name);
        modal.find('.modal-body #invoice_number').val(invoice_number);
    })
</script>

<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endsection