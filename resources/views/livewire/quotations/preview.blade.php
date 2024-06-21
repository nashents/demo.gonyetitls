<div>
    <div id="invoice">
        <x-loading/>
        <div class="toolbar hidden-print">
            <div class="text-end">
                <button type="button" onclick="goBack()" class="btn btn-default border-primary btn-wide btn-rounded"><i class="fa fa-arrow-left" style="color: black"></i> Back</button>
                <a href="#" wire:click="sendEmail({{$quotation->id}})" class="btn btn-default border-primary btn-wide btn-rounded"><i class="fa fa-envelope" style="color: red"></i> Send</a>
                <a href="{{route('quotations.print',$quotation->id)}}" class="btn btn-default border-primary btn-wide btn-rounded"><i class="fa fa-print" style="color: black"></i> Print</a>
                <a href="{{route('quotations.pdf', $quotation->id)}}" class="btn btn-default border-primary btn-wide btn-rounded"><i class="fa fa-file-pdf-o" style="color: red"></i> Export as PDF</a>
            </div>
            <hr>
        </div>
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <div class="col" style="padding-bottom: 5px">
                            <a href="#"><img src="{{asset('images/uploads/'.$company->logo)}}" width="150" alt=""> </a>
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
                        <center><h2>QUOTATION</h2>  </center>
                    </div>
                </header>
                <main>
                    <div class="row contacts" >
                        <div class="col invoice-to" >
                            <div class="text-gray-light">BILL TO:</div>
                            <h4 class="to">{{$quotation->customer ? $quotation->customer->name : ""}}</h4>
                          
                            <div class="address" >
                                @if (isset($quotation->customer->street_address) || isset($quotation->customer->suburb))
                                 {{$quotation->customer ? $quotation->customer->street_address : ""}} {{$quotation->customer ? $quotation->customer->suburb : ""}}, <br>  
                                @endif
                                 {{$quotation->customer ? $quotation->customer->city : ""}} {{$quotation->customer ? $quotation->customer->country : ""}}
                            </div>
                            
                            @if (isset($quotation->customer->email))
                            <div class="email"><a href="mailto:{{$quotation->customer->email}}">{{$quotation->customer->email}}</a></div>
                            @endif
                            
                            <div class="email">
                                @if (isset($quotation->customer->vat_number))
                                    VAT No.: {{$quotation->customer ? $quotation->customer->vat_number : ""}}
                                @endif
                            </div>
                            <div class="email">
                                @if (isset($quotation->customer->tin_number))
                                    TIN.: {{$quotation->customer ? $quotation->customer->tin_number : ""}}
                                @endif
                            </div>
                        </div>
                        <div class="col invoice-details">
                            <div class="date" style="padding-bottom: 3px"> <strong>Document No.:</strong> {{$quotation->quotation_number}}</div>
                            <div class="date" style="padding-bottom: 3px"><strong>Quotation Date:</strong> {{$quotation->date}}</div>
                            @if ($quotation->expiry)
                            <div class="date" style="padding-bottom: 3px"><strong>Quotation Expiry:</strong> {{$quotation->expiry}}</div>
                            @endif
                            <div class="date" style="padding-bottom: 3px"><strong>Currency:</strong> {{$quotation->currency ? $quotation->currency->name : ""}}</div>
                            
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th class="text-left"> <strong>Description</strong></th>
                                <th class="text-left"> <strong>Quantity</strong></th>
                                <th class="text-right"><strong>Price</th> 
                                <th class="text-right"><strong>Total(Excl)</strong></th>
                                <th class="text-right"><strong>VAT AMT</strong></th>
                                <th class="text-right"><strong>Total(Incl)</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            @foreach ($quotation->quotation_products as $product)
                            <tr>
                                <td class="text-left">
                                {{ $product->description }}
                                </td>
                                <td class="unit">{{ $product->qty }}</td>
                                <td class="unit">{{ $quotation->currency ? $product->quotation->currency->symbol : "" }}{{number_format($product->freight,2)}}</td>
                                <td class="unit">
                                    @if (isset($product->subtotal))
                                        {{ $quotation->currency ? $product->quotation->currency->symbol : "" }}{{number_format($product->subtotal,2)}} 
                                    @endif   
                                </td>
                                @if (isset($quotation->vat) && $quotation->vat > 0) 
                                @php
                                if (isset($product->subtotal) && $product->subtotal > 0) {
                                    $vat_amt = ($quotation->vat / 100) * $product->subtotal;
                                    $subtotal_incl = $vat_amt + $product->subtotal;
                                }else {
                                    $vat_amt = 0;
                                }
                                   
                                @endphp
                                <td class="unit text-center">
                                    @if (isset($vat_amt))
                                    {{ $quotation->currency ? $quotation->currency->symbol : "" }}{{number_format($vat_amt,2)}}
                                    @endif
                                </td>
                                @else
                                <td class="unit text-center"> {{ $quotation->currency ? $quotation->currency->symbol : "" }}{{number_format(0,2)}}</td>
                                @endif
                                <td class="unit">{{ $quotation->currency ? $quotation->currency->symbol : "" }}{{ number_format($quotation->total,2) }}</td>
                            </tr>

                            @endforeach
                        </tbody>
                        <tfoot >
                            <tr >
                                <td colspan="3"></td>
                                <td colspan="2">SUB-TOTAL {{ $quotation->currency ? $quotation->currency->name : "" }} <small>(Excl)</small></td>
                                <td>  
                                    @if ($quotation->subtotal)
                                        {{ $quotation->currency ? $quotation->currency->symbol : "" }}{{number_format($quotation->subtotal,2)}}  
                                    @endif
                                </td>
                            </tr>
                            @if (isset($quotation->vat) && $quotation->vat > 0) 
                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2">VAT TOTAL</td>
                                  @php
                                  if ($quotation->subtotal) {
                                    $value = ($quotation->vat/100)*$quotation->subtotal;
                                  }
                                    
                                  @endphp
                                <td>
                                    @if ($value)
                                        {{ $quotation->currency ? $quotation->currency->symbol : "" }}{{number_format($value,2)}}
                                    @endif
                                </td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2"> VAT TOTAL</td>
                                <td>{{ $quotation->currency ? $quotation->currency->symbol : "" }}{{number_format(0,2)}}</td>
                            </tr>
                            @endif
                          
                            <tr>
                                <td colspan="3"></td>
                                <td colspan="2">QUOTATION TOTAL {{ $quotation->currency ? $quotation->currency->name : "" }} </td>
                                <td>
                                    @if ($quotation->total)
                                          {{ $quotation->currency ? $quotation->currency->symbol : "" }}{{number_format($quotation->total,2)}}
                                    @endif
                                </td>
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
                <center><footer  style="text-align: center;">{{$quotation->footer}}</footer></center> 
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>
</div>
