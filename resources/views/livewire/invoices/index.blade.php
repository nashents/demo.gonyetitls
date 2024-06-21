<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div>
                                @include('includes.messages')
                            </div>

                            <div class="panel-title">
                                <a href="{{route('invoices.create')}}"  class="btn btn-default"><i class="fa fa-plus-square-o"></i>Invoice</a>
                            </div>
                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">
                            <div class="panel-title">
                                <h5>Trips Management</h5>
                                <div class="row">
                                
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                  Filter By
                                  </span>
                                  <select wire:model.debounce.300ms="trip_filter" class="form-control" aria-label="..." >
                                    <option value="created_at">Trip Created At</option>
                              </select>
                                    </div>
                                    <!-- /input-group -->
                                </div>
                                @if ($invoice_filter == "created_at")
                                <div class="col-lg-2" style="margin-right: 7px">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                  From
                                  </span>
                                  <input type="date" wire:model.debounce.300ms="from" wire:change="dateRange()" class="form-control" aria-label="...">
                                    </div>
                                    <!-- /input-group -->
                                </div>
                                <div class="col-lg-2" style="margin-left: 7px">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                  To
                                  </span>
                                  <input type="date" wire:model.debounce.300ms="to" wire:change="dateRange()" class="form-control" aria-label="...">
                                    </div>
                                    <!-- /input-group -->
                                </div>
                     
                                @endif
                               
                                <!-- /input-group -->
                            </div>
                            
                            </div>
                            <br>
                            <div class="col-lg-3"  >
                                <button type="button" data-toggle="modal" data-target="#paymentDrawdownModal" class="btn btn-default btn-rounded btn-wide"><i class="fa fa-credit-card"></i>Invoice Payments Drawdown</button>
                                <!-- /input-group -->
                            </div>
                            <div class="col-md-3" style="float: right; padding-right:0px">
                               
                                <div class="form-group">
                                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search trips...">
                                </div>
                            </div>
                            <table  class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  
                                  <tr>

                                    <th class="th-sm">Invoice#
                                    </th>
                                    <th class="th-sm">Customer
                                    </th>
                                    <th class="th-sm">Date
                                    </th>
                                    <th class="th-sm">Payment Due
                                    </th>
                                    <th class="th-sm">Status
                                    </th>  
                                    <th class="th-sm">Currency
                                    </th>
                                    <th class="th-sm">Total
                                    </th>
                                    <th class="th-sm">Paid
                                    </th>
                                    <th class="th-sm">Due
                                    </th>
                                    <th class="th-sm">Auth
                                    </th>
                                    <th class="th-sm">Action
                                    </th>
                                  </tr>
                                </thead>
                                @if ($invoices->count()>0)
                                <tbody>
                                    @forelse ($invoices as $invoice)
                                    @php
                                        $expiry = $invoice->expiry;
                                        $now = new DateTime();
                                        $expiry_date = new DateTime($expiry);
                                    @endphp
                                  <tr>
                                    <td>{{$invoice->invoice_number}}</td>
                                    <td>
                                        {{$invoice->customer ? $invoice->customer->name : ""}}
                                    </td>
                                    <td>{{$invoice->date}}</td>
                                    <td><span class="label label-{{$now <= $expiry_date ? 'success' : 'danger' }}">{{$invoice->expiry}}</span></td>
                                    <td><span class="label label-{{($invoice->status == 'Paid') ? 'success' : (($invoice->status == 'Partial') ? 'warning' : 'danger') }}">{{ $invoice->status }}</span></td>
                                    <td>
                                        {{$invoice->currency ? $invoice->currency->name : ""}}
                                    </td>
                                    <td>
                                        @if ($invoice->total)
                                        {{$invoice->currency ? $invoice->currency->symbol : ""}}{{number_format($invoice->total,2)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($invoice->payments))
                                             {{$invoice->currency ? $invoice->currency->symbol : ""}}{{number_format($invoice->payments->sum('amount'),2)}}
                                        @else
                                             {{$invoice->currency ? $invoice->currency->symbol : ""}}{{number_format($invoice->invoice_payments->sum('amount'),2)}}
                                        @endif
                                    </td>
                                    <td>
                                       
                                        {{-- @if ($invoice->balance) --}}
                                        {{$invoice->currency ? $invoice->currency->symbol : ""}}{{number_format($invoice->balance,2)}}
                                        {{-- @endif --}}
                                    </td>
                                    <td><span class="badge bg-{{($invoice->authorization == 'approved') ? 'success' : (($invoice->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($invoice->authorization == 'approved') ? 'approved' : (($invoice->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                   
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('invoices.show',$invoice->id)}}"  ><i class="fas fa-eye color-default"></i> View</a></li>
                                                @if ($invoice->authorization == "approved")
                                                <li><a href="{{route('invoices.preview',$invoice->id)}}"  ><i class="fas fa-file-invoice color-primary"></i> Preview</a></li>
                                                <li><a href="#" wire:click="showPayment({{$invoice->id}})"  ><i class="fas fa-credit-card color-primary"></i> Record Payment</a></li>
                                                @endif
                                                @if ($invoice->invoice_payments->count()>0)
                                                @else 
                                                <li><a href="{{route('invoices.edit',$invoice->id)}}"  ><i class="fas fa-edit color-success"></i> Edit</a></li>
                                                @endif
                                               
                                                <li><a href="#" data-toggle="modal" data-target="#invoiceDeleteModal{{ $invoice->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                        @include('invoices.delete')
                                </td>
                                  </tr>
                                  @empty
                                  <tr>
                                    <td colspan="11">
                                        <div style="text-align:center; text-color:grey; padding-top:5px; padding-bottom:5px; font-size:17px">
                                            No Invoices Found ....
                                        </div>
                                       
                                    </td>
                                  </tr>  
                                @endforelse
                                </tbody>
                                @else
                                    <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                                 @endif
                              </table>
                              <nav class="text-center" style="float: right">
                                <ul class="pagination rounded-corners">
                                    @if (isset($invoices))
                                        {{ $invoices->links() }} 
                                    @endif 
                                </ul>
                            </nav>    

                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
    </section>

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-plus"></i> Add Payment <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="recordPayment()" >
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Customers<span class="required" style="color: red">*</span></label>
                       <select  class="form-control" wire:model.debounce.300ms="customer_id" required>
                        <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                       </select>
                        @error('customer_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name Of Payer</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="name" placeholder="Enter Name"  >
                                @error('name') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Surname Of Payer</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="surname" placeholder="Enter Surname"  >
                                @error('surname') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Date Of Payment<span class="required" style="color: red">*</span></label>
                        <input type="date" class="form-control" wire:model.debounce.300ms="date" placeholder="Enter Date" required >
                        @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                   
                    <div class="form-group">
                        <label for="country">Method Of Payment<span class="required" style="color: red">*</span></label>
                       <select wire:model.debounce.300ms="mode_of_payment" class="form-control" required >
                           <option value="">Select Method Of Payment</option>
                           <option value="CASH">CASH</option>
                           <option value="ECOCASH">ECOCASH</option>
                           <option value="NOSTRO">FCA NOSTRO</option>
                           <option value="RTGS">RTGS/ZIPIT TRANSFER</option>
                           <option value="VISA">VISA/MASTER CARD</option>
                           <option value="OTHER">OTHER</option>   
                       </select>
                        @error('mode_of_payment') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                    @if ($mode_of_payment == "RTGS" || $mode_of_payment == "ECOCASH" || $mode_of_payment == "NOSTRO" || $mode_of_payment == "VISA")
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Reference Code</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="reference_code" placeholder="Enter Reference / Approval code"  >
                                @error('reference_code') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Proof Of Payment</label>
                                <input type="file" class="form-control" wire:model.debounce.300ms="pop" placeholder="Upload Pop" >
                                @error('pop') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                      
                    </div>
                    @elseif ($mode_of_payment == "CASH")
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Denomination</label>
                               <select wire:model.debounce.300ms="denomination.0" class="form-control"  >
                                   <option value="">Select Denomination</option>
                                   <option value="1">1</option>
                                   <option value="2">2</option>
                                   <option value="5">5</option>
                                   <option value="10">10</option>
                                   <option value="20">20</option>
                                   <option value="50">50</option>
                                   <option value="100">100</option>
                                   <option value="200">200</option>
                               </select>
                                @error('denomination.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="name">Quantity</label>
                            <input type="number" step="any" class="form-control" wire:model.debounce.300ms="denomination_qty.0" placeholder="Enter Quantity"  >
                            @error('denomination_qty.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                        </div>
                      
                        <div class="row">
                            @foreach ($inputs as $key => $value)
                            <div class="col-md-5">
                                <div class="form-group">
                                    {{-- <label for="country">Denomination</label> --}}
                                   <select wire:model.debounce.300ms="denomination.{{ $value }}" class="form-control"  >
                                       <option value="">Select Denomination</option>
                                       <option value="1">1</option>
                                       <option value="2">2</option>
                                       <option value="5">5</option>
                                       <option value="10">10</option>
                                       <option value="20">20</option>
                                       <option value="50">50</option>
                                       <option value="100">100</option>
                                       <option value="200">200</option>
                                   </select>
                                    @error('denomination.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                {{-- <label for="name">Quantity</label> --}}
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="denomination_qty.{{ $value }}" placeholder="Enter Quantity"  >
                                @error('denomination_qty.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for=""></label>
                                    <button class="btn btn-danger btn-rounded xs"   wire:click.prevent="remove({{$key}})" > <i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i>Denomination</button>
                                </div>
                            </div>
                        </div>
        
                
                    @endif
                    <div class="form-group">
                        <label for="country">Payment Accounts<span class="required" style="color: red">*</span> </label>
                       <select wire:model.debounce.300ms="account_id" class="form-control" required>
                           <option value="">Select Payment Account</option>
                         @foreach ($accounts as $account)
                         @if ($invoice_currency)
                         @if ($account->currency->id == $invoice_currency->id)
                         <option value="{{ $account->id }}">{{ $account->name }} {{ $account->currency ? $account->currency->name : ""}}</option>
                         @endif     
                         @else  
                         select currency for invoice
                         @endif
                         @endforeach
                        
                       
                       </select>
                        @error('account_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                        @if ($mode_of_payment == "OTHER")
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Value<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" max="{{ $invoice_balance }}"  {{ $amount > $invoice_balance ? "disabled" : "" }} class="form-control" wire:model.debounce.300ms="amount" placeholder="Enter Value" required >
                                @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                @if ($amount > $invoice_balance)
                                <small style="color: red">Amount should be less than or equal to invoice balance.</small>   
                                @endif
                            </div>
                        </div>
                        @else   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Amount<span class="required" style="color: red">*</span></label>
                                <input type="number" max="{{ $invoice_balance }}" step="any" {{ $amount > $invoice_balance ? "disabled" : "" }} class="form-control" wire:model.debounce.300ms="amount" placeholder="Enter Amount" required >
                                @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                @if ($amount > $invoice_balance)
                                <small style="color: red">Amount should be less than or equal to invoice balance.</small>   
                                @endif
                                
                            </div>
                        </div>
                        @endif
                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Balance<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="current_balance" placeholder="Current Balance" disabled required >
                                @error('current_balance') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Memo / Notes (Optional)</label>
                        <textarea class="form-control" wire:model.debounce.300ms="notes" cols="30" rows="5"></textarea>
                        @error('notes') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-success btn-wide btn-rounded" {{ $amount > $invoice_balance ? "disabled" : "" }} ><i class="fa fa-save" ></i>Save</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="paymentDrawdownModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-plus"></i> Add Payment Drawdown <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="drawdownPayments()" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Customers<span class="required" style="color: red">*</span></label>
                               <select  class="form-control" wire:model.debounce.300ms="selectedCustomer" required>
                                <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                               </select>
                                @error('selectedCustomer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Customer Accounts<span class="required" style="color: red">*</span> </label>
                               <select wire:model.debounce.300ms="selectedCustomerAccount" class="form-control" required>
                                   <option value="">Select Customer Account</option>
                                   @if (!is_null($selectedCustomer))
                                        @foreach ($customer_accounts as $customer_account)
                                        <option value="{{ $customer_account->id }}">{{ $customer_account->name }} {{ $customer_account->currency ? $customer_account->currency->name : ""}} {{ $customer_account->currency ? $customer_account->currency->symbol : ""}}{{ $customer_account->balance }}</option>    
                                        @endforeach
                                   @endif 
                               </select>
                               <small>  <a href="{{ route('accounts.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Customer Account</a></small> 
                                @error('selectedCustomerAccount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country">Account Payments<span class="required" style="color: red">*</span> </label>
                        <select wire:model.debounce.300ms="selectedPayment" class="form-control" required>
                            <option value="">Select payment</option>
                            @if (!is_null($selectedCustomer) && !is_null($selectedCustomerAccount))
                                @foreach ($account_payments as $payment)
                                    <option value="{{ $payment->id }}">{{$payment->payment_number}} | {{$payment->date}} | {{$payment->currency ? $payment->currency->name : ""}} {{$payment->currency ? $payment->currency->symbol : ""}}{{$payment->amount}}  </option>
                                @endforeach
                            @endif 
                        </select>
                         @error('selectedPayment') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="country">Invoices<span class="required" style="color: red">*</span> </label>
                        <select wire:model.debounce.300ms="selectedInvoice" class="form-control" required>
                            <option value="">Select Invoice</option>
                            @if (!is_null($selectedCustomer) && !is_null($selectedCustomerAccount))
                                @foreach ($unpaid_invoices as $invoice)
                                    <option value="{{ $invoice->id }}">{{$invoice->invoice_number}} | {{$invoice->customer ? $invoice->customer->name : ""}} | Balance: {{$invoice->currency ? $invoice->currency->name : ""}} {{$invoice->currency ? $invoice->currency->symbol : ""}}{{number_format($invoice->balance ? $invoice->balance : 0,2)}} | {{ $invoice->status }}</option>
                                @endforeach
                            @endif 
                        </select>
                         @error('selectedInvoice') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Drawdown Balance<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="payment_drawdown_balance" placeholder="Payment Drawdown Balance" disabled required >
                                @error('payment_drawdown_balance') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Balance<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="invoice_drawdown_balance" placeholder="Invoice Balance" disabled required >
                                @error('invoice_drawdown_balance') <span class="error" style="color:red">{{ $message }}</span> @enderror
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

