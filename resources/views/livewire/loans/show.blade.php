<div>
    <div class="row mt-30">
    
        <!-- /.col-md-3 -->

        <div class="col-md-10 col-md-offset-1" >

            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Loan Details</a></li>
            </ul>
            <div class="tab-content bg-white p-15">
                <div role="tabpanel" class="tab-pane active" id="basic">
                    <table class="table table-striped">

                        <tbody class="text-center line-height-35 ">
                            <tr>
                                <th class="w-10 text-center line-height-35">Loan#</th>
                                <td class="w-20 line-height-35">{{$loan->loan_number}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Type</th>
                                <td class="w-20 line-height-35">{{$loan->loan_type ? $loan->loan_type->name : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">AppliedBy</th>
                                <td class="w-20 line-height-35">{{$loan->employee ? $loan->employee->name : ""}} {{$loan->employee ? $loan->employee->surname : ""}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Date</th>
                                <td class="w-20 line-height-35">{{$loan->start_date}}</td>
                            </tr>
                          
                                <tr>
                                    <th class="w-10 text-center line-height-35">Currency</th>
                                    <td class="w-20 line-height-35">{{$loan->currency ? $loan->currency->name : ""}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Amount</th>
                                    <td class="w-20 line-height-35">
                                         @if ($loan->amount)
                                        {{$loan->currency ? $loan->currency->symbol : ""}}{{number_format($loan->amount,2)}}        
                                   @endif
                                </td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Interest</th>
                                    <td class="w-20 line-height-35">{{$loan->interest}}%</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Period</th>
                                    <td class="w-20 line-height-35">{{$loan->period}}Months</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Total</th>
                                    <td class="w-20 line-height-35">  @if ($loan->total)
                                        {{$loan->currency ? $loan->currency->symbol : ""}}{{number_format($loan->total,2)}}        
                                    @endif</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Monthly Payment</th>
                                    <td class="w-20 line-height-35">
                                        @if ($loan->payment_per_month)
                                        {{$loan->currency ? $loan->currency->symbol : ""}}{{number_format($loan->payment_per_month,2)}}        
                                    @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Balance</th>
                                    <td class="w-20 line-height-35">
                                        @if ($loan->balance)
                                        {{$loan->currency ? $loan->currency->symbol : ""}}{{number_format($loan->balance,2)}}        
                                    @endif
                                    </td>
                                </tr>
                                @if ($loan->purpose)
                                <tr>
                                    <th class="w-10 text-center line-height-35">Purpose of Loan</th>
                                    <td class="w-20 line-height-35">{{$loan->purpose}}</td>
                                </tr>
                             @endif
                                <tr>
                                    <th class="w-10 text-center line-height-35">Payment Status</th>
                                    <td class="w-20 line-height-35"><span class="label label-{{($loan->status == 'Paid') ? 'success' : (($loan->status == 'Partial') ? 'warning' : 'danger') }}">{{ $loan->status }}</span></td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Authorization</th>
                                    <td class="w-20 line-height-35"><span class="badge bg-{{($loan->authorization == 'approved') ? 'success' : (($loan->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($loan->authorization == 'approved') ? 'approved' : (($loan->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                </tr>
                             
                                @if ($loan->reason)
                                    <tr>
                                        <th class="w-10 text-center line-height-35">Authorization Comments</th>
                                        <td class="w-20 line-height-35">{{$loan->reason}}</td>
                                    </tr>
                                @endif       
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group pull-right mt-10" >
                           <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                        </div>
                    </div>
                    </div>

            </div>
        </div>
        <!-- /.col-md-9 -->
    </div>
   
</div>
