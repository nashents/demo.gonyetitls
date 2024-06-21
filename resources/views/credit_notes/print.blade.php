
@extends('layouts.main')
@section('extra-css')
@if (Auth::user()->employee->company)
<link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->employee->company->logo)!!}">
@elseif (Auth::user()->company)
<link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->company->logo)!!}">
@endif
@endsection
@section('title')
Credit Note Print |@if (Auth::user()->employee->company)
{{Auth::user()->employee->company->name}}
@elseif (Auth::user()->company)
{{Auth::user()->company->name}}
@endif
@endsection
@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div id="invoice">
                
                <div class="invoice overflow-auto">
                    <div style="min-width: 600px">
                            <header>
                                <div class="row">
                                    <div class="col">
                                        <a href="javascript:;">
                                                        <img src="{{asset('images/uploads/'.$company->logo)}}" width="200" alt="">
                                                    </a>
                                    </div>
                                    <div class="col company-details">
                                      
                                        <h4 class="name" >
                                            <a target="_blank" href="javascript:;" style="color:  {{Auth::user()->employee->company ? Auth::user()->employee->company->color : Auth::user()->company->color }}">
                                                {{$company->name}}
                                            </a>
                                        </h4>
                                        <div>{{$company->street_address}}, {{$company->suburb}}, {{$company->city}} {{$company->country}}</div>
                                        <div>
                                            {{$company->phonenumber}}
                                            @if ($company->second_phonenumber)
                                            | {{$company->second_phonenumber}}
                                            @endif
                                            @if ($company->third_phonenumber)
                                            | {{$company->third_phonenumber}}
                                            @endif
                                        </div>
                                      
                                        
                                        <div>{{$company->email}}</div>
                                        @if ($company->second_email)
                                        <div>{{$company->second_email}}</div>
                                        @endif
                                        @if ($company->third_email)
                                        <div>{{$company->third_email}}</div>
                                        <br>
                                        @endif
                                        <div>
                                            @if (isset($company->vat_number))
                                                VAT No.: {{$company->vat_number}}
                                            @endif
                                        </div>
                                        <div>
                                            @if (isset($company->tin_number))
                                                TIN.: {{$company->tin_number}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                              
                                <div style="padding-top: 25px; padding-bottom:15px">
                                    <center><h2>CREDIT NOTE </h2>  </center>
                                </div>  
                            </header>
                        <main>
                            <div class="row contacts">
                                <div class="col invoice-to">
                                    <div class="text-gray-light">BILL TO:</div>
                                    <h4 class="to">{{$credit_note->customer ? $credit_note->customer->name : ""}}</h4>
                                  
                                    <div class="address" >
                                        @if (isset($credit_note->customer->street_address) || isset($credit_note->customer->suburb))
                                         {{$credit_note->customer ? $credit_note->customer->street_address : ""}} {{$credit_note->customer ? $credit_note->customer->suburb : ""}}, <br>  
                                        @endif
                                         {{$credit_note->customer ? $credit_note->customer->city : ""}} {{$credit_note->customer ? $credit_note->customer->country : ""}}
                                    </div>
                                    
                                    @if (isset($credit_note->customer->email))
                                    <div class="email"><a href="mailto:{{$credit_note->customer->email}}">{{$credit_note->customer->email}}</a></div>
                                    @endif
                                    
                                    <div class="email">
                                        @if (isset($credit_note->customer->vat_number))
                                            VAT No.: {{$credit_note->customer ? $credit_note->customer->vat_number : ""}}
                                        @endif
                                    </div>
                                    <div class="email">
                                        @if (isset($credit_note->customer->tin_number))
                                            TIN.: {{$credit_note->customer ? $credit_note->customer->tin_number : ""}}
                                        @endif
                                    </div>
                                </div>
                                <div class="col invoice-details">
                                    @if (Auth::user()->employee->company->fiscalize == TRUE)
                                    <div class="date" style="padding-bottom: 3px"> <strong>Document No.:</strong> {{$credit_note->credit_note_number}}</div>
                                    @else   
                                    <div class="date" style="padding-bottom: 3px"> <strong>Credit Note No.:</strong> {{$credit_note->credit_note_number}}</div>
                                    @endif
                                    <div class="date" style="padding-bottom: 3px"> <strong>Reference No.:</strong> {{$credit_note->invoice ? $credit_note->invoice->invoice_number : ""}}</div>
                                    @if ($credit_note->subheading)
                                    <div class="date" style="padding-bottom: 3px"> {{$credit_note->subheading}}</div>
                                    @endif
                                    <div class="date" style="padding-bottom: 3px"><strong>Date:</strong> {{$credit_note->date}}</div>
                                    @if ($credit_note->expiry)
                                    <div class="date" style="padding-bottom: 3px"><strong>Payment Due:</strong> {{$credit_note->expiry}}</div>
                                    @endif
                                    <div class="date" style="padding-bottom: 3px"><strong>Currency:</strong> {{$credit_note->currency ? $credit_note->currency->name : ""}}</div>
                                    
                                </div>
                            </div>
                            <table>
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center"> <strong>Description</strong></th>
                                        <th class="text-center"><strong>Qty</strong></th>
                                        <th class="text-center"><strong>Price</strong></th>
                                        <th class="text-center"><strong>Total</strong><small>(Excl)</small></th>
                                        <th class="text-center"><strong>VAT AMT</strong></th>
                                        <th class="text-center"><strong>Total</strong><small>(Incl)</small></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($invoice->reason == "Trip")
                           
                                    @foreach ($invoice_items as $invoice_item)
                                        @php
                                            $trip = $invoice_item->trip
                                        @endphp
                                        
                                         <tr >
                                            <td class="text-center">
                                                 {{$invoice_item->trip_details}}
                                            </td>
                                            <td class="unit text-center"> 
                                               {{ $invoice_item->qty }}
                                            </td>
                                            <td class="unit text-center"> 
                                           
                                                     {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($invoice_item->amount,2)}}
                                         
                                            </td>
                                            <td class="unit text-center">
                                                @if ($invoice_item->subtotal)
                                                {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($invoice_item->subtotal,2)}}
                                                @endif
                                                
                                            </td>
                                           
                                           
                                            @if (isset($invoice->vat) && $invoice->vat > 0) 
                                            @php
                                            if (isset($invoice_item->subtotal) && $invoice_item->subtotal > 0) {
                                                $vat_amt = ($invoice->vat / 100) * $invoice_item->subtotal;
                                                $subtotal_incl = $vat_amt + $invoice_item->subtotal;
                                            }else {
                                                $vat_amt = 0;
                                            }
                                               
                                            @endphp
                                            <td class="unit text-center">
                                                @if (isset($vat_amt))
                                                {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($vat_amt,2)}}
                                                @endif
                                            </td>
                                            @else
                                            <td class="unit text-center"> {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format(0,2)}}</td>
                                            @endif
                                            <td class="unit text-center">
                                                @if (isset($subtotal_incl))
                                                    {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($subtotal_incl,2)}}
                                                @else
                                                    {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($invoice_item->subtotal,2)}}
                                                @endif
                                            </td>
                                        </tr>
                                      
                                    @endforeach
                                    @elseif ($invoice->reason == "General Invoice")
                                        
                                    @foreach ($invoice_items as $invoice_item)
                                   
                                    
                                     <tr >
                                        <td class="text-center">
                                             {{$invoice_item->invoice_product ? $invoice_item->invoice_product->name : "" }}
                                        </td>
                                        <td class="unit text-center"> 
                                           {{ $invoice_item->qty }}
                                        </td>
                                        <td class="unit text-center"> 
                                       
                                                 {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($invoice_item->amount,2)}}
                                     
                                        </td>
                                        <td class="unit text-center">
                                            @if ($invoice_item->subtotal)
                                            {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($invoice_item->subtotal,2)}}
                                            @endif
                                            
                                        </td>
                                       
                                       
                                        @if (isset($invoice->vat) && $invoice->vat > 0) 
                                        @php
                                        if (isset($invoice_item->subtotal) && $invoice_item->subtotal > 0) {
                                            $vat_amt = ($invoice->vat / 100) * $invoice_item->subtotal;
                                            $subtotal_incl = $vat_amt + $invoice_item->subtotal;
                                        }else {
                                            $vat_amt = 0;
                                        }
                                           
                                        @endphp
                                        <td class="unit text-center">
                                            @if (isset($vat_amt))
                                            {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($vat_amt,2)}}
                                            @endif
                                        </td>
                                        @else
                                        <td class="unit text-center"> {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format(0,2)}}</td>
                                        @endif
                                        <td class="unit text-center">
                                            @if (isset($subtotal_incl))
                                                {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($subtotal_incl,2)}}
                                            @else
                                                {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($invoice_item->subtotal,2)}}
                                            @endif
                                        </td>
                                    </tr>
                                  
                                @endforeach
                                    @endif
                                   
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2">SUB-TOTAL {{ $invoice->currency ? $invoice->currency->name : "" }} <small>(Excl)</small></td>
                                        <td>  
                                            @if ($invoice->subtotal)
                                                {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($invoice->subtotal,2)}}  
                                            @endif
                                        </td>
                                    </tr>
                                    @if (isset($invoice->vat) && $invoice->vat > 0) 
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2">VAT TOTAL</td>
                                          @php
                                          if ($invoice->subtotal) {
                                            $value = ($invoice->vat/100)*$invoice->subtotal;
                                          }
                                            
                                          @endphp
                                        <td>
                                            @if ($value)
                                                {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($value,2)}}
                                            @endif
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2"> VAT TOTAL</td>
                                        <td>{{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format(0,2)}}</td>
                                    </tr>
                                    @endif
                                  
                                    <tr>
                                        <td colspan="3"></td>
                                        <td colspan="2">CREDIT NOTE TOTAL {{ $invoice->currency ? $invoice->currency->name : "" }} </td>
                                        <td>
                                            @if ($credit_note->total)
                                                  {{ $invoice->currency ? $invoice->currency->symbol : "" }}{{number_format($credit_note->total,2)}}
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <br>
                            @if ($credit_note->credit_note_reason)
                            <div class="notices">
                                <div><strong>Reason</strong></div>
                                <div class="notice">{{$credit_note->credit_note_reason}}</div>
                            </div>
                            @endif
                            @if ($credit_note->memo)
                            <div class="notices">
                                <div><strong>Terms & Conditions</strong></div>
                                <div class="notice">{{$credit_note->memo}}</div>
                            </div>
                            @endif
                           
                            <br>
                            <br>
                     
                    </div>
                    <center > <footer style="   position: fixed; 
                        bottom: 0px; 
                        left: 0px; 
                        right: 0px;
                        height: 50px;">{{$credit_note->footer}}</footer></center>  
                    <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
  window.addEventListener("load", window.print());
</script>
@endsection

