<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoices;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $count_all = invoices::count();
        $count_of_unpaid_invoices = invoices::where('Value_Status' , 2)->count();
        $percentage_of_unpaid_invoices =  $count_of_unpaid_invoices / $count_all * 100;

        $count_of_paid_invoices = invoices::where('Value_Status' , 1)->count();
        $percentage_of_paid_invoices =  $count_of_paid_invoices / $count_all * 100;

        $count_of_partial_paid_invoices = invoices::where('Value_Status' , 3)->count();
        $percentage_of_partial_paid_invoices =  $count_of_partial_paid_invoices / $count_all * 100;

        $chartjs = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 340, 'height' => 200])
            ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
            ->datasets([
                [
                    'backgroundColor' => ['#ec5858', '#81b214','#ff9642'],
                    'data' => [$percentage_of_unpaid_invoices, $percentage_of_paid_invoices,$percentage_of_partial_paid_invoices]
                ]
            ])
            ->options([]);

        $chartjs2 = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 350, 'height' => 200])
        ->labels(['الفواتير الغير المدفوعة', 'الفواتير المدفوعة','الفواتير المدفوعة جزئيا'])
        ->datasets([
            [
                "label" => "الفواتير الغير المدفوعة",
                'backgroundColor' => ['#ec5858'],
                'data' => [$percentage_of_unpaid_invoices]
            ],
            [
                "label" => "الفواتير المدفوعة",
                'backgroundColor' => ['#81b214'],
                'data' => [$percentage_of_paid_invoices]
            ],
            [
                "label" => "الفواتير المدفوعة جزئيا",
                'backgroundColor' => ['#ff9642'],
                'data' => [$percentage_of_partial_paid_invoices]
            ],


        ])
        ->options([]);

return view('home', [
    'chartjs2' => $chartjs2 ,
    'chartjs' => $chartjs,
            ]);
    }


}
