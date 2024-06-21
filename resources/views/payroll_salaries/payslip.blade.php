<!doctype html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 
  <title>Payslip Template</title>
 

@include('includes.css')

</head>
<body>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div id="invoice">
                    <div class="invoice overflow-auto">
                        <div style="min-width: 600px">
                            <header>
                                <div class="row" style="margin-top:-20px;">
                                    <div class="col">
                                        <a href="#"><img src="{{asset('images/uploads/'.$company->logo)}}" width="100" alt=""></a>
                                    </div>
                                    <div class="col company-details"  style="margin-top:-70px;">
                                      
                                        <h3 class="name" >
                                            <a target="_blank" href="#" style="color:  {{Auth::user()->employee->company ? Auth::user()->employee->company->color : Auth::user()->company->color }}">
                                        {{$company->name}}
                                        </a>
                                        </h3>
                                        <div>{{$company->street_address}}, {{$company->suburb}}, {{$company->city}} {{$company->country}}</div>
                                        <div>{{$company->phonenumber}}
                                        </div>
                                        <div>{{$company->email}}</div>
                                    </div>
                                </div>
                            </header>
                            <main>
                                <div class="row contacts">
                                    <div class="col invoice-to">
                                        <h4 class="to">{{$employee->name}} {{$employee->surname}}</h4>
                                        <div class="text-gray-light">{{$employee->post}}</div>
                                        <div class="address">{{$employee->street_address}} {{$employee->suburb}}, {{$employee->city}}, {{$employee->country}}</div>
                                        <div class="email"><a href="mailto:{{$employee->email}}">{{$employee->email}}</a></div>
                                    </div>
                                    <div class="col invoice-details" style="margin-top:-120px;">
                                        <h5 class="invoice-id" style="margin-bottom: -1px;">Payroll#: {{$payroll_salary->payroll ? $payroll_salary->payroll->payroll_number : ""}}</h5>
                                        <div class="date" style="font-size: 16px;">Payslip For: {{$payroll_salary->payroll->month}} {{$payroll_salary->payroll->year}}</div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="text-left"><strong>Category</strong></th>
                                            <th class="text-left"> <strong>Item</strong></th>
                                            <th class="text-left"> <strong>Currency</strong></th>
                                            <th class="text-left"> <strong>Amount</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payroll_salary->payroll_salary_details as $payroll_salary_detail)
                                        <tr>
                                            @if ($payroll_salary_detail->salary_item)
                                            <td>{{$payroll_salary_detail->salary_item ? $payroll_salary_detail->salary_item->type : ""}}</td>
                                            <td>{{$payroll_salary_detail->salary_item ? $payroll_salary_detail->salary_item->name : ""}}</td>
                                            @elseif ($payroll_salary_detail->loan)   
                                            <td>Deductions</td>
                                            <td>Loan</td>
                                            @endif
                                            <td>{{$payroll_salary_detail->payroll_salary->currency->name}}</td>
                                            <td>
                                                @if ($payroll_salary_detail->amount)
                                                  {{$payroll_salary_detail->payroll_salary->currency->symbol}}{{number_format($payroll_salary_detail->amount,2)}}        
                                                @endif
                                            </td>
                                        </tr>
                                       
                                        @endforeach
                                       
    
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="1"></td>
                                            <td colspan="2">Basic</td>
                                            <td>  {{ $payroll_salary->currency ? $payroll_salary->currency->symbol : "" }}{{number_format($payroll_salary->basic,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1"></td>
                                            <td colspan="2">Gross</td>
                                            <td>  {{ $payroll_salary->currency ? $payroll_salary->currency->symbol : "" }}{{number_format($payroll_salary->gross,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1"></td>
                                            <td colspan="2">Net</td>
                                            <td>  {{ $payroll_salary->currency ? $payroll_salary->currency->symbol : "" }}{{number_format($payroll_salary->net,2)}}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                              
                        </div>
                        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>