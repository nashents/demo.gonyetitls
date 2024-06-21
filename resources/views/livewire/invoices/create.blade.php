<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>New Invoice</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group" style="margin-left: 22px">
                                    <label for="name">You want to generate an invoice for?</label>
                                    <label class="radio-inline">
                                        <input type="radio" wire:model.debounce.300ms="reason" value="Trip" name="optradio" >Trip Invoice
                                      </label>
                                      <label class="radio-inline">
                                        <input type="radio" wire:model.debounce.300ms="reason" value="General Invoice" name="optradio">General Invoice
                                      </label>
                                </div>
                            </div>
                                 @if (isset($reason) && $reason == "Trip")
                        <form wire:submit.prevent="store()" >
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Invoice Number<span class="required" style="color: red">*</span></label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="invoice_number" placeholder="Enter Invoice Number" required />
                                        @error('invoice_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                       <small style="color: green">
                                        @if (Auth::user()->employee->company->invoice_serialize_by_customer == True)
                                        Invoice serialization by customer.
                                        @else   
                                        Invoice serialization default.
                                        @endif
                                       </small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Subheading</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="subheading" placeholder="Enter Subheading"  />
                                        @error('subheading') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vat">Customers<span class="required" style="color: red">*</span></label>
                                       <select class="form-control" wire:model.debounce.300ms="selectedCustomer" required>
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }} </option>                                        
                                        @endforeach
                                       </select>
                                        @error('selectedCustomer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                         
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Invoice Date<span class="required" style="color: red">*</span></label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="date" wire:change="invoiceDate()" placeholder="Enter Invoice Date" required >
                                        @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="date">Payment Due</label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="expiry" placeholder="Enter Payment Due Date" >
                                        @error('expiry') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vat">Currencies<span class="required" style="color: red">*</span></label>
                                       <select class="form-control" wire:model.debounce.300ms="selectedCurrency" required>
                                        <option value="">Select Currency</option>
                                        @foreach ($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->name }} </option>                                        
                                        @endforeach
                                       </select>
                                        @error('selectedCurrency') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="vat">Bank Accounts</label>
                                       <select class="form-control" wire:model.debounce.300ms="bank_account_id" >
                                        <option value="">Select Bank Account</option>
                                        @foreach ($bank_accounts as $bank_account)
                                                <option value="{{ $bank_account->id }}">{{ $bank_account->name }} {{ $bank_account->type }} {{ $bank_account->account_number }}</option>                                        
                                        @endforeach
                                       </select>
                                        @error('bank_account_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>                   
                            </div>
                       
                            @if (!is_null($selectedCurrency))
                            @if (Auth::user()->employee->company)
                                @if ($selectedCurrency != Auth::user()->employee->company->currency_id)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer">Conversion Rate<span class="required" style="color: red">*</span></label>
                                            <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_rate"  placeholder="Exchange Rate" required>
                                            @error('exchange_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div> 
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="customer">Invoice Amount {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }}<span class="required" style="color: red">*</span></label>
                                            <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_amount" placeholder="Converted Invoice Amount" required>
                                            @error('exchange_amount') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div> 
                                    </div>
                                </div>
                                @endif
                            @endif
                        @endif 
                            <div class="row">
                                <div class="col-md-3" >
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                  From
                                  </span>
                                  <input type="date" wire:model.debounce.300ms="from"  class="form-control" aria-label="...">
                                    </div>
                                    <!-- /input-group -->
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                  To
                                  </span>
                                  <input type="date" wire:model.debounce.300ms="to"  class="form-control" aria-label="...">
                                    </div>
                                    <!-- /input-group -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search using trip#, trip ref, waybill#, customer, horse, vehicle, freight, currency, start date...">
                                    </div>
                                </div>
                            </div>
                            @php
                            $invoice_items = App\Models\InvoiceItem::all();
                            foreach($invoice_items as $invoice_item){
                                    $trip_ids[] = $invoice_item->trip_id;
                            }   
                            @endphp
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subheading">Trips<span class="required" style="color: red">*</span></label>
                                        <select wire:model.debounce.300ms="selectedTrip.0"  class="form-control" required size="4">
                                            <option value="">Select Trip</option>
                                              
                                                @foreach ($trips as $trip)
                                                    @if ($trip->currency_id == $selectedCurrency)
                                                        @if (isset($trip_ids))
                                                            @if (in_array($trip->id,$trip_ids))
                                                            <option value="{{$trip->id}}" style="color: orange">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }}{{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} |  {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} </option> 
                                                            @else
                                                                <option value="{{$trip->id}}">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }} {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} | {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}}</option>
                                                            @endif
                                                        @else
                                                        <option value="{{$trip->id}}">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }} {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} |  {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} | {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}}</option>
                                                        @endif
                                                    @endif
                                                @endforeach  
                                               
                                            </select>
                                            <small style="color: green">NB: All invoiced trips will appear in orange</small>
                                            <br>
                                            <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                                        @error('selectedTrip.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="date">Qty<span class="required" style="color: red">*</span></label>
                                        <input type="number"  class="form-control" wire:model.debounce.300ms="qty.0"  placeholder="Enter Qty" required>
                                        @error('qty.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="subheading">Amount<span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount.0"  placeholder="Enter Amount" required/>
                                        @error('amount.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="subheading">Taxes</label>
                                        <select wire:model.debounce.300ms="selectedTax.0"  class="form-control">
                                            <option value="">Select Tax</option>
                                                @foreach ($tax_accounts as $tax)
                                                   <option value="{{$tax->id}}">{{$tax->abbreviation}} {{$tax->rate ? $tax->rate."%" : ""}}</option> 
                                                @endforeach
                                            </select>
                                            <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                                        @error('selectedTax.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            @foreach ($inputs as $key => $value)
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="subheading">Trips<span class="required" style="color: red">*</span></label>
                                        <select wire:model.debounce.300ms="selectedTrip.{{$value}}"  class="form-control" required size="4">
                                            <option value="">Select Trip</option>
                                            @if (isset($trips))
                                                @foreach ($trips as $trip)
                                                    @if ($trip->currency_id == $selectedCurrency)
                                                        @if (isset($trip_ids))
                                                            @if (in_array($trip->id,$trip_ids))
                                                            <option value="{{$trip->id}}" style="color: orange">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }}{{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} |  {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} </option> 
                                                            @else
                                                                <option value="{{$trip->id}}">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }} {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} | {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}}</option>
                                                            @endif
                                                        @else
                                                        <option value="{{$trip->id}}">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }} {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} |  {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} | {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}}</option>
                                                        @endif
                                                    @endif
                                                @endforeach  
                                            @endif
                                         
                                            </select>
                                            <small style="color: green">NB: All invoiced trips will appear in orange</small>
                                            <br>
                                            <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                                        @error('selectedTrip.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="date">Qty<span class="required" style="color: red">*</span></label>
                                        <input type="number"  class="form-control" wire:model.debounce.300ms="qty.0"  placeholder="Enter Qty" required>
                                        @error('qty.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="subheading">Amount<span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount.{{$value}}"  placeholder="Enter Amount" required/>
                                        @error('amount.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="subheading">Taxes</label>
                                        <select wire:model.debounce.300ms="selectedTax.{{$value}}"  class="form-control">
                                            <option value="">Select Tax</option>
                                                @foreach ($tax_accounts as $tax)
                                                   <option value="{{$tax->id}}">{{$tax->abbreviation}} {{$tax->rate ? $tax->rate."%" : ""}}</option> 
                                                @endforeach
                                            </select>
                                            <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                                        @error('selectedTax.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for=""></label>
                                        <button class="btn btn-danger btn-rounded xs" style="margin-top:23px"  wire:click.prevent="remove({{$key}})"> <i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i>Trip</button>
                                    </div>
                                </div>
                            </div>
                              
                            <hr>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="memo">Memo / Terms & Conditions</label>
                                   <textarea class="form-control" wire:model.debounce.300ms="memo" cols="30" rows="3" placeholder="Enter notes or terms of service that are visible to your customer."></textarea>
                                    @error('memo') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="footer">Footer</label>
                                       <textarea class="form-control" wire:model.debounce.300ms="footer" cols="30" rows="3" placeholder="Enter footer for your invoices eg (Tax Information, Thank you note)."></textarea>
                                        @error('footer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                            </div>

                      
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group" role="group">
                                <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                                <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-save"></i>Save</button>
                            </div>
                            <!-- /.btn-group -->
                        </div>
                    </form>
                    @elseif(isset($reason) && $reason == "General Invoice")
                    <form wire:submit.prevent="store()" >
                        <div class="modal-body">
                            <div class="row">
                                <img src="{{asset('images')}}" alt="">
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="name">Invoice Number<span class="required" style="color: red">*</span></label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="invoice_number" placeholder="Enter Invoice Number" required >
                                        @error('invoice_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        <small style="color: green">
                                            @if (Auth::user()->employee->company->invoice_serialize_by_customer == True)
                                            Invoice number is serialized by customer
                                            @else   
                                            Invoice serialization default
                                            @endif
                                           </small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="subheading">Subheading</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="subheading" placeholder="Enter Subheading" >
                                        @error('subheading') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vat">Customer<span class="required" style="color: red">*</span></label>
                                       <select class="form-control" wire:model.debounce.300ms="selectedCustomer" required>
                                        <option value="">Select Customers</option>
                                        @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }} </option>                                        
                                        @endforeach
                                       </select>
                                        @error('selectedCustomer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Invoice Date<span class="required" style="color: red">*</span></label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="date" wire:change="invoiceDate()" placeholder="Enter Invoice Date" required >
                                        @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Payment Due</label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="expiry" placeholder="Enter Payment Due Date"  >
                                        @error('expiry') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vat">Currencies<span class="required" style="color: red">*</span></label>
                                       <select class="form-control" wire:model.debounce.300ms="selectedCurrency" required>
                                        <option value="">Select Currency</option>
                                        @foreach ($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->name }} </option>                                        
                                        @endforeach
                                       </select>
                                        @error('selectedCurrency') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    @if (!is_null($selectedCurrency))
                                    @if (Auth::user()->employee->company)
                                        @if ($selectedCurrency != Auth::user()->employee->company->currency_id)
                                            <div class="form-group">
                                                <label for="customer">Exchange Rate</label>
                                                <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_rate" placeholder="The exchange rate @ trip date">
                                                @error('exchange_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                                            </div> 
                                        @endif
                                    @endif
                                @endif  
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vat">Bank Accounts</label>
                                       <select class="form-control" wire:model.debounce.300ms="bank_account_id" >
                                        <option value="">Select Bank Account</option>
                                        @foreach ($bank_accounts as $bank_account)
                                                <option value="{{ $bank_account->id }}">{{ $bank_account->name }} {{ $bank_account->type }} {{ $bank_account->account_number }}</option>                                        
                                        @endforeach
                                       </select>
                                        @error('bank_account_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>

                               
                            </div>
                          
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="country">Items<span class="required" style="color: red">*</span></label>
                                            <select wire:model.debounce.300ms="selectedProduct.0" class="form-control" required>
                                            <option value="">Select Item</option>
                                                @foreach ($products as $product)
                                                    <option value="{{$product->id}}">
                                                        <strong>{{$product->name}}</strong> {{$product->description ? "| ".$product->description : ""}}
                                                    </option> 
                                                @endforeach
                                            </select>
                                            <small>  <a href="#" data-toggle="modal" data-target="#product_serviceModal"><i class="fa fa-plus-square-o"></i> New Product / Service</a></small> 
                                        @error('selectedProduct.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Quantity<span class="required" style="color: red">*</span></label>
                                        <input type="number" class="form-control" wire:model.debounce.300ms="qty.0" placeholder="Enter Qty" required >
                                        @error('qty.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Rate<span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount.0" placeholder="Enter Rate" required >
                                        @error('amount.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="subheading">Taxes</label>
                                        <select wire:model.debounce.300ms="selectedTax.0"  class="form-control">
                                            <option value="">Select Tax</option>
                                                @foreach ($tax_accounts as $tax)
                                                   <option value="{{$tax->id}}">{{$tax->abbreviation}} {{$tax->rate ? $tax->rate."%" : ""}}</option> 
                                                @endforeach
                                            </select>
                                            <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                                        @error('selectedTax.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>      
                            </div>
                            @foreach ($inputs as $key => $value)
                                <div class="row">  
                                    <div class="col-md-5">
                                        <div class="form-group">
                                             <label for="country">Items<span class="required" style="color: red">*</span></label>
                                                <select wire:model.debounce.300ms="selectedProduct.{{ $value }}" class="form-control" required>
                                                    <option value="">Select Item</option>
                                                    @foreach ($products as $product)
                                                    <option value="{{$product->id}}">
                                                        <strong>{{$product->name}}</strong> {{$product->description ? "| ".$product->description : ""}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <small>  <a href="#" data-toggle="modal" data-target="#product_serviceModal"><i class="fa fa-plus-square-o"></i> New Product / Service</a></small> 
                                            @error('selectedProduct.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="name">Quantity<span class="required" style="color: red">*</span></label>
                                                <input type="number" class="form-control" wire:model.debounce.300ms="qty.{{ $value }}" placeholder="Enter Qty" required >
                                                @error('qty.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="name">Rate<span class="required" style="color: red">*</span></label>
                                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount.{{ $value }}" placeholder="Enter Rate" required >
                                                @error('amount.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                       
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="subheading">Taxes</label>
                                                <select wire:model.debounce.300ms="selectedTax.{{$value}}"  class="form-control">
                                                    <option value="">Select Tax</option>
                                                        @foreach ($tax_accounts as $tax)
                                                           <option value="{{$tax->id}}">{{$tax->abbreviation}} {{$tax->rate ? $tax->rate."%" : ""}}</option> 
                                                        @endforeach
                                                    </select>
                                                    <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                                                @error('selectedTax.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>      

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for=""></label>
                                            <button class="btn btn-danger btn-rounded xs" style="margin-top:23px"  wire:click.prevent="remove({{$key}})"> <i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i>Item</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="memo">Memo / Terms & Conditions</label>
                                   <textarea class="form-control" wire:model.debounce.300ms="memo" cols="30" rows="3" placeholder="Enter notes or terms of service that are visible to your customer."></textarea>
                                    @error('memo') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="footer">Footer</label>
                                       <textarea class="form-control" wire:model.debounce.300ms="footer" cols="30" rows="3" placeholder="Enter footer for this invoice eg (Tax information, Thank you note)."></textarea>
                                        @error('footer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                            </div>

                           
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group" role="group">
                                <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                                <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-save"></i>Save</button>
                            </div>
                            <!-- /.btn-group -->
                        </div>
                    </form>
                    @endif
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->


            </div>

        </div>
        <!-- /.container-fluid -->
    </section>

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="product_serviceModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-plus"></i> New Product / Service<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="storeItem()" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="comment">Item Name<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="item_name" placeholder="Enter Item Name" required>
                                @error('item_name') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="comment">Description</label>
                            <textarea class="form-control" wire:model.debounce.300ms="item_description" cols="30" rows="4"></textarea>
                                @error('item_description') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-10">
                                <label for=""></label>
                                <input type="checkbox" wire:model.debounce.300ms="sell"   class="line-style" disabled/>
                                <label for="one" class="radio-label">Sell this?</label>
                                @error('sell') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-10">
                                <input type="checkbox" wire:model.debounce.300ms="buy"   class="line-style" />
                                <label for="one" class="radio-label">Buy this?</label>
                                @error('buy') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @if ($buy == True)
                    <div class="form-group">
                        <label for="subheading">Select Expense Account</label>
                        <select wire:model.debounce.300ms="expense_account_id" class="form-control">
                            <option value="">Select Tax</option>
                                @foreach ($expense_accounts as $account)
                                <option value="{{$account->id}}">{{$account->name}}</option> 
                                @endforeach
                            </select>
                            {{-- <small><a href="{{ route('accounts.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Account</a></small>  --}}
                        @error('expense_account_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="comment">Price</label>
                                <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="item_price" placeholder="Enter Price" >
                                @error('item_price') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subheading">Sales Tax</label>
                                <select wire:model.debounce.300ms="tax_id" class="form-control">
                                    <option value="">Select Tax</option>
                                        @foreach ($tax_accounts as $tax)
                                        <option value="{{$tax->id}}">{{$tax->abbreviation}} {{$tax->rate ? $tax->rate."%" : ""}}</option> 
                                        @endforeach
                                    </select>
                                    <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                                @error('tax_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-save"></i>Save</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>
</div>


   