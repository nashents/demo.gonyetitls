<!doctype html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 
  <title>Quotation Template</title>
 

@include('includes.css')

</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-body">
            <div id="invoice"  style="font-size: 16px">
                <div class="invoice overflow-auto" >
                    <div  style="margin-left: -30px; margin-right:-30px" >
                        <header style="margin-top:-25px; padding-top:-25px; padding-bottom:10px" >
                            <div class="row">
                                <div class="col" style="padding-bottom: 5px">
    								<img src="{{asset('images/uploads/'.$company->logo)}}" width="150" >
                                </div>
                                <div class="col company-details" style="margin-top:-100px;">
                                    <h4 class="name" style="color:  {{Auth::user()->employee->company ? Auth::user()->employee->company->color : Auth::user()->company->color }}">
									{{$company->name}}
                                    </h4>
                                    <div>{{$company->street_address}}, {{$company->suburb}}, {{$company->city}} {{$company->country}}</div>
                                    <div>{{$company->phonenumber}}
                                    </div>
                                    <div>{{$company->email}}</div>
                                </div>
                            </div>
                        </header>
                        <main>
                            <div class="row contacts"  style="margin-bottom: 20px" >
                                <div class="col invoice-to">
                                    <div class="text-gray-light">BILL TO:</div>
                                    <h5 class="to">{{$quotation->customer->name}}</h5>
                                    <div class="address">{{$quotation->customer->street_address}} {{$quotation->customer->suburb}}, <br> {{$quotation->customer->city}}, {{$quotation->customer->country}}</div>
                                    <div class="email"><a href="mailto:{{$quotation->customer->email}}">{{$quotation->customer->email}}</a>
                                    </div>
                                </div>
                                <div class="col invoice-details" style="margin-top:-120px;">
                                    <div class="date" style="padding-bottom: 3px"><strong>Quotation Number:</strong>  {{$quotation->quotation_number}}</div>
                                    <div class="date" style="padding-bottom: 3px">{{$quotation->subheading}}</div>
                                    <div class="date" style="padding-bottom: 3px"><strong>Quotation Date:</strong>  {{$quotation->date}}</div>
                                    @if ($quotation->expiry)
                                        <div class="date" style="padding-bottom: 3px"> <strong>Expires On:</strong> {{$quotation->expiry}}</div>
                                    @endif
                                </div>
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th class="text-left"><strong>#</strong></th>
                                        <th class="text-left"> <strong>Trip Details</strong></th>
                                        <th class="text-left"> <strong>Info</strong></th>
                                        <th class="text-right"><strong>Weight<small>(Tons)</small></strong></th> 
                                        <th class="text-right"><strong>Rate</strong></th>
                                        <th class="text-right"><strong>Freight</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $n = 1;
                                    @endphp
                                    @foreach ($quotation->quotation_products as $product)
                                    <tr>
                                        <td class="unit">{{$n++}}</td>
                                        @php
                                        $from = App\Models\Destination::find($product->from);
                                        $to = App\Models\Destination::find($product->to);
                                        @endphp
                                        <td class="text-left">
                                            <strong>Cargo:</strong> {{ucfirst($product->cargo->group)}} {{$product->cargo->name}}, <br> <strong>From:</strong> {{$from->country ? $from->country->name : ""}}  {{$from->city}} <strong>To:</strong> {{$to->country ? $to->country->name : ""}} {{$to->city}}, <br> <strong>Loading Point:</strong> {{$product->loading_point ? $product->loading_point->name : ""}} <strong>Offloading Point:</strong> {{$product->offloading_point ? $product->offloading_point->name : ""}}       
                                        </td>
                                        <td class="text-left">
                                            {{ Str::limit($product->description,100) }}
                                        </td>
                                        <td class="qty"> {{$product->weight}}</td>
                                        <td class="unit">${{number_format($product->rate,2)}}</td>
                                        <td class="unit">${{number_format($product->freight,2)}}</td>
                                    </tr>

                                    @endforeach


                                </tbody>
                                <tfoot >
                                    <tr >
                                        <td colspan="2"></td>
                                        <td colspan="3">SUBTOTAL</td>
                                        <td>${{number_format($quotation->subtotal,2)}}</td>
                                    </tr>
                                    @if (isset($quotation->vat) && $quotation->vat > 0) 
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="3">TAX {{$company->vat}}%</td>
                                        @php
                                            $value = ($quotation->vat/100)*$quotation->subtotal;
                                        @endphp
                                        <td>{{ $quotation->currency ? $quotation->currency->symbol : "" }}{{number_format($value,2)}}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="3">TAX  0%</td>
                                        <td>{{ $quotation->currency ? $quotation->currency->symbol : "" }}{{number_format(0,2)}}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="3">GRAND TOTAL</td>
                                        <td>${{number_format($quotation->total,2)}}</td>
                                    </tr>
                                </tfoot>
                            </table>

                            @if ($quotation->memo)
                            <div class="notices">
                                <div><strong>Notes / Terms & Conditions</strong></div>
                                <div class="notice">{{$quotation->memo}}</div>
                            </div>
                            @endif

                            
                          
                           
                    @if ( $quotation->bank_accounts->count()>0)
                        
                  
                            <table>
                                <thead>
                                    <tr>
                                        <th class="text-left" style="background-color: white;"><strong>BANKING DETAILS</strong></th>      
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                    <tr>
                                        @foreach ($quotation->bank_accounts as $bank_account)
                                        <td class="text-left "  scope="col" >
                                            @if (isset($bank_account->name))
                                                <strong>Bank: </strong>{{ $bank_account->name }} <br>
                                            @endif
                                            @if (isset($bank_account->branch))
                                                <strong>Branch: </strong>{{ $bank_account->branch }} <br>
                                            @endif
                                            @if (isset($bank_account->branch_code))
                                                <strong>Branch Code: </strong> {{ $bank_account->branch_code }} <br>
                                            @endif
                                            @if (isset($bank_account->swift_code))
                                                <strong>Swift Code: </strong> {{ $bank_account->swift_code }} <br>
                                            @endif
                                            @if (isset($bank_account->type))
                                                 <strong>Account Type: </strong> {{ $bank_account->type }} <br>
                                            @endif
                                            @if ($bank_account->account_name)
                                            <strong>Account Name: </strong> {{ $bank_account->account_name }} <br>
                                            @endif
                                            @if (isset($bank_account->account_number))
                                                <strong>Account Number: </strong> {{ $bank_account->account_number }} <br>
                                            @endif
                                           
                                          
                                        </td>
                                        @endforeach
                                       
                                    </tr>
                            
                                </tbody>
                            </table>
                            @endif
                        </main>
                       
                    </div>
                    <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                    <div> </div>
                    <center><footer style="   position: fixed; 
                        bottom: -60px; 
                        left: 0px; 
                        right: 0px;
                        height: 50px;">{{$quotation->footer}}</footer></center> 
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>