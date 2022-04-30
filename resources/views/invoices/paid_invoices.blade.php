@extends('layouts.master')
@section('title')
الفواتير المدفوعه
@stop
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير المدفوعه</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الفواتير</span>
						</div>
					</div>
					<div class="d-flex my-xl-auto right-content">
						
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')


@if (session()->has('soft_delete_for_invoice'))
<script>
	window.onload = function() {
		notif({
			msg: "تم حذف الفاتورة بنجاح",
			type: "success"
		})
	}
</script>
@endif


@if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
@endif


@if (session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
@endif
				<!-- row -->
				<div class="row">
						<div class="col-xl-12">
							<div class="card mg-b-20">
								<div class="card-header pb-0">
									<div class="d-flex justify-content-between">
										
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="example1" class="table key-buttons text-md-nowrap">
											{{-- عشان تخلى الجدول فيه تصدير pdf  او  exccel --}}
										{{-- <table id="example" class="table key-buttons text-md-nowrap"> --}}
											<thead>
												<tr>
													<th class="border-bottom-0">#</th>
													<th class="border-bottom-0">رقم الفاتورة</th>
													<th class="border-bottom-0">تاريخ القاتورة</th>
													<th class="border-bottom-0">تاريخ الاستحقاق</th>
													<th class="border-bottom-0">المنتج</th>
													<th class="border-bottom-0">القسم</th>
													<th class="border-bottom-0">الخصم</th>
													<th class="border-bottom-0">نسبة الضريبة</th>
													<th class="border-bottom-0">قيمة الضريبة</th>
													<th class="border-bottom-0">الاجمالي</th>
													<th class="border-bottom-0">الحالة</th>
													<th class="border-bottom-0">ملاحظات</th>
													<th class="border-bottom-0">العمليات</th>
												</tr>
											</thead>
											<tbody>
												@php
													$i=1
												@endphp
												@foreach ($invoices_data as $invoice_data)
												@php
													$i++
												@endphp
												<tr>
													<td>{{ $i }}</td>
													<td>
														<a
                                                href="{{ url('InvoicesDetails') }}/{{ $invoice_data->id }}">{{ $invoice_data->invoice_number }}
														</a>
													</td>
													<td>{{ $invoice_data->invoice_Date }}</td>
													<td>{{ $invoice_data->Due_date }}</td>
													<td>{{ $invoice_data->product }}</td>
													<td>{{ $invoice_data->department->department_name }}</td>
													<td>{{ $invoice_data->Discount }}</td>
													<td>{{ $invoice_data->Rate_VAT }}</td>
													<td>{{ $invoice_data->Value_VAT }}</td>
													<td>{{ $invoice_data->Total }}</td>
													<td> @if ($invoice_data->Value_Status == 1)
														<span class="text-success">{{ $invoice_data->Status }}</span>
													@elseif($invoice_data->Value_Status == 2)
														<span class="text-danger">{{ $invoice_data->Status }}</span>
													@else
														<span class="text-warning">{{ $invoice_data->Status }}</span>
													@endif</td>
													<td>{{ $invoice_data->note }}</td>
													<td>
														<div class="dropdown">
															<button aria-expanded="false" aria-haspopup="true"
																class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
																type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
															<div class="dropdown-menu tx-13">
																
															<a class="dropdown-item"
																href=" {{ url('edit_invoice') }}/{{ $invoice_data->id }}">تعديل
																الفاتورة</a>
														
	
														
															<a class="dropdown-item" href="#" data-invoice_id="{{ $invoice_data->id }}"
																data-toggle="modal" data-target="#delete_invoice"><i
																	class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
																الفاتورة</a>

																<a class="dropdown-item"
																href=" {{ url('Status_Update') }}/{{ $invoice_data->id }}"><i
																	class=" text-success fas fa-money-bill"></i>&nbsp;&nbsp;تغيير	حاله	الدفع</a>	
													</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<!--/div-->
						<div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
						aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<form action="{{ url('/delete_invoice') }}" method="post">
									
										{{ csrf_field() }}
								</div>
								<div class="modal-body">
									هل انت متاكد من عملية الحذف ؟
									<input type="hidden" name="invoice_id" id="invoice_id" value="">
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
									<button type="submit" class="btn btn-danger">تاكيد</button>
								</div>
								</form>
							</div>
						</div>
					</div>
				
						<!--div-->
						
					</div>
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')

<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>


<script>
	$('#delete_invoice').on('show.bs.modal', function(event) {
		var button = $(event.relatedTarget)
		var invoice_id = button.data('invoice_id')
		var modal = $(this)
		modal.find('.modal-body #invoice_id').val(invoice_id);
	})
</script>

<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>


@endsection