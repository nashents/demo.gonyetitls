
<div>
    <div class="row mt-30">
    <div class="col-md-10 col-md-offset-1">
        <!-- /.row -->
        <ul class="nav nav-tabs nav-justified" role="tablist">
            <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Payment Details</a></li>
            <li role="presentation" ><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Payment Documents</a></li>
        </ul>
        <div class="tab-content bg-white p-15">
            <div role="tabpanel" class="tab-pane active" id="basic">
                <table class="table table-striped">

                    <tbody class="text-center line-height-35 ">
                        <tr>
                            <th class="w-10 text-center line-height-35">Payment#</th>
                            <td class="w-20 line-height-35"> {{$payment->payment_number}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">RecordedBy</th>
                            <td class="w-20 line-height-35"> {{$payment->user ? $payment->user->name : ""}} {{$payment->user ? $payment->user->surname : ""}}</td>
                        </tr>
                        @if ($payment->invoice)
                        <tr>
                            <th class="w-10 text-center line-height-35">Invoice#</th>
                            <td class="w-20 line-height-35"> {{$payment->invoice ? $payment->invoice->invoice_number : ""}}</td>
                        </tr>
                        @endif
                       @if ($payment->bill)
                       <tr>
                        <th class="w-10 text-center line-height-35">Bill#</th>
                        <td class="w-20 line-height-35"> {{$payment->bill ? $payment->bill->bill_number : ""}}</td>
                         </tr> 
                       @endif
                       @if ($payment->container)
                       <tr>
                        <th class="w-10 text-center line-height-35">Fueling Station</th>
                        <td class="w-20 line-height-35"> {{$payment->container ? $payment->container->name : ""}}</td>
                         </tr> 
                       @endif
                       @if ($payment->top_up)
                       <tr>
                        <th class="w-10 text-center line-height-35">Topup#</th>
                        <td class="w-20 line-height-35"> {{$payment->top_up ? $payment->top_up->order_number : ""}}</td>
                         </tr> 
                       @endif
                       @if (isset($payment->name) || isset($payment->surname))
                            <tr>
                                <th class="w-10 text-center line-height-35">Paid By</th>
                                <td class="w-20 line-height-35">{{ucfirst($payment->name)}} {{ucfirst($payment->surname )}}</td>
                            </tr> 
                       @endif
                        <tr>
                            <th class="w-10 text-center line-height-35">Paid On</th>
                            <td class="w-20 line-height-35">{{$payment->date}}</td>
                        </tr>
                        @if ($payment->customer)
                        <tr>
                            <th class="w-10 text-center line-height-35">Customer</th>
                            <td class="w-20 line-height-35">{{$payment->customer ? $payment->customer->name : ""}}</td>
                        </tr> 
                        @endif
                        @if ($payment->vendor)
                        <tr>
                            <th class="w-10 text-center line-height-35">Vendor</th>
                            <td class="w-20 line-height-35">{{$payment->vendor ? $payment->vendor->name : ""}}</td>
                        </tr> 
                        @endif
                        @if ($payment->transporter)
                        <tr>
                            <th class="w-10 text-center line-height-35">Transporter</th>
                            <td class="w-20 line-height-35">{{$payment->transporter ? $payment->transporter->name : ""}}</td>
                        </tr> 
                        @endif
                       
                            <tr>
                                <th class="w-10 text-center line-height-35">Mode Of Payment</th>
                                <td class="w-20 line-height-35">{{$payment->mode_of_payment}}</td>
                            </tr>
                            @if ($payment->mode_of_payment == "OTHER")
                            <tr>
                                <th class="w-10 text-center line-height-35">Other Explanation</th>
                                <td class="w-20 line-height-35">{{$payment->specify_other}}</td>
                            </tr>
                            @endif
                            @if ($payment->mode_of_payment == "RTGS" || $payment->mode_of_payment == "ECOCASH")
                            <tr>
                                <th class="w-10 text-center line-height-35">Bank Account</th>
                                <td class="w-20 line-height-35">{{$payment->bank_account ? $payment->bank_account->name : ""}} {{$payment->bank_account ? $payment->bank_account->account_number : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Reference Number</th>
                                <td class="w-20 line-height-35">{{$payment->reference_code }}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Proof Of Payment</th>
                                <td class="w-20 line-height-35"><a href="{{ asset('myfiles/documents/'.$payment->pop) }}"><i class="fas fa-file"></i>{{ $payment->pop }}</a></td>
                            </tr>
                           
                            @endif
                          
                            <tr>
                                <th class="w-10 text-center line-height-35">Currency</th>
                                <td class="w-20 line-height-35">{{$payment->currency ? $payment->currency->name : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Amount</th>
                                <td class="w-20 line-height-35">
                                    @if ($payment->amount)
                                    {{ $payment->currency ? $payment->currency->symbol : "" }}{{ number_format($payment->amount,2)}}
                                    @endif
                                </td>
                            </tr>
                            @if ($payment->mode_of_payment == "CASH")
                            <tr>
                                <th class="w-10 text-center line-height-35">Denomitions</th>
                                <td class="w-20 line-height-35">@if ($payment->denominations->count()>0)
                                    @foreach ($payment->denominations as $denomination)
                                    {{ $denomination->quantity }} {{ $payment->currency ? $payment->currency->symbol : "" }}{{ $denomination->denomination }} Note(s) ,
                                    @endforeach        

                                @endif
                            </td>
                            </tr>
                            @endif
                              @if ($payment->balance)
                            <tr>
                                <th class="w-10 text-center line-height-35">Balance</th>
                                <td class="w-20 line-height-35">
                                  
                                    {{ $payment->currency ? $payment->currency->symbol : "" }}{{number_format($payment->balance,2)}}
                                 
                                </td>
                                
                            </tr>
                               @endif
                            @if ($payment->notes)
                            <tr>
                                <th class="w-10 text-center line-height-35">Notes</th>
                                <td class="w-20 line-height-35">
                                   
                                    {{$payment->notes}}
                                    
                                </td>
                            </tr>
                            @endif
                    </tbody>
                </table>
              
            </div>
            <div role="tabpanel" class="tab-pane" id="documents">
                @livewire('documents.index', ['id' => $payment->id,'category' =>'payment'])
              </div>

              <div class="row">
                <div class="col-md-12">
                    <div class="btn-group pull-right mt-10" >
                       <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                    </div>
                </div>
                </div>

            <!-- /.section-title -->
        </div>
    </div>
    </div>
</div>
