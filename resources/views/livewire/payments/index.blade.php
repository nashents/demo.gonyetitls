<div>
    <style>
        .modal-lg {
        max-width: 80%;
    }
    </style>
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
                                    <a href="" data-toggle="modal" data-target="#paymentModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Payment</a>
                                </div>
                            </div>
                            <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                                <table id="paymentsTable" class="table  table-spaymented table-bordered table-sm table-responsive" cellspacing="0" width="100%" style=" width:100%; height:100%;">
                                    <thead >
                                        <th class="th-sm">Payment#
                                        </th>
                                        <th class="th-sm">Category
                                        </th>
                                        <th class="th-sm">MOP
                                        </th>
                                        <th class="th-sm">Account
                                        </th>
                                        <th class="th-sm">Currency
                                        </th>
                                        <th class="th-sm">Amt
                                        </th>
                                        <th class="th-sm">Actions
                                        </th>

                                      </tr>
                                    </thead>
                               
                                    @if ($payments->count()>0)
                                    <tbody>
                                        @foreach ($payments as $payment)
                                      <tr>
                                        
                                        <td>{{ucfirst($payment->payment_number)}}</td>
                                        <td>
                                            @if ($payment->invoice)
                                            Invoice Payment | <a href="{{ route('invoices.show',$payment->invoice->id) }}" style="color:blue">{{$payment->invoice ? $payment->invoice->invoice_number : ""}}</a>
                                            @elseif ($payment->invoice_payment)
                                            Invoice Payment | <a href="{{ route('invoices.show',$payment->invoice_payment->invoice_id) }}" style="color:blue">{{$payment->invoice_payment->invoice ? $payment->invoice_payment->invoice->invoice_number : ""}}</a>
                                            @elseif ($payment->customer && !$payment->invoice_payment)
                                            @php
                                                $customer_account = App\Models\Account::find($payment->customer_account_id);
                                            @endphp
                                            Customer Payment | <a href="{{ route('customers.show',$payment->customer->id) }}" style="color:blue">{{$payment->customer ? $payment->customer->name : ""}}</a>
                                            @if ($customer_account)
                                            | <a href="{{ route('accounts.show',$customer_account->id) }}" style="color: blue">{{ $customer_account->name }}</a> 
                                            @endif
                                            @elseif ($payment->bill)
                                            Bill Payment | <a href="{{ route('bills.show',$payment->bill->id) }}" style="color:blue">{{$payment->bill ? $payment->bill->bill_number : ""}}</a>
                                            @elseif ($payment->recovery)
                                            Recovery Payment | <a href="{{ route('recoveries.show',$payment->recovery->id) }}" style="color:blue">{{$payment->recovery ? $payment->recovery->recovery_number : ""}}</a>
                                            @endif
                                        </td>
                                        <td>{{$payment->mode_of_payment}}</td>
                                        <td>{{$payment->account ? $payment->account->name : ""}}</td>
                                        <td>{{$payment->currency ? $payment->currency->name : ""}}</td>
                                        <td>@if ($payment->amount)
                                            {{$payment->currency ? $payment->currency->symbol : ""}}{{number_format($payment->amount,2)}}
                                        @endif</td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('payments.show', $payment->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                                                    
                                                   @if ($payment->receipt)
                                                   <li><a href="{{route('receipts.preview',$payment->receipt->id)}}"  ><i class="fas fa-receipt color-primary"></i> Receipt</a></li>
                                                   @endif    
                                                   <li><a href="#" data-toggle="modal" data-target="#paymentDeleteModal{{$payment->id}}"><i class="fas fa-trash color-danger"></i>Delete</a></li> 
                                                </ul>
                                            </div>
                                     
                                            @include('payments.delete')
                                    </td>
                                      </tr>
                                      @endforeach
                                    </tbody>
                                    @else
                                    <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                                    @endif
                                  

                                  </table>

                                <!-- /.col-md-12 -->
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
        </section>

          <!-- Modal -->
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
                           <select  class="form-control" wire:model.debounce.300ms="selectedCustomer" required>
                            <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                           </select>
                            @error('selectedCustomer') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Date Of Payment<span class="required" style="color: red">*</span></label>
                                    <input type="date" class="form-control" wire:model.debounce.300ms="date" placeholder="Enter Date" required >
                                    @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Currencies<span class="required" style="color: red">*</span></label>
                                   <select  class="form-control" wire:model.debounce.300ms="selectedCurrency" required>
                                    <option value="">Select Currency</option>
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                        @endforeach
                                   </select>
                                    @error('selectedCurrency') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                       
                        <div class="row">
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Payment Receiving Accounts<span class="required" style="color: red">*</span> </label>
                                   <select wire:model.debounce.300ms="account_id" class="form-control" required>
                                       <option value="">Select Receiving Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }} {{ $account->currency ? $account->currency->name : ""}}</option>    
                                        @endforeach
                                   </select>
                                    @error('account_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            </div>
                           
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

                        @elseif ($mode_of_payment == "OTHER")
                        <div class="form-group">
                            <label for="">Specify Other</label>
                            <textarea wire:model.debounce.300ms="specify_other" class="form-control" cols="30" rows="2"></textarea>
                        </div>
                    
                        @endif
                     
                        <div class="row">
                            @if ($mode_of_payment == "OTHER")
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Value<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount" placeholder="Enter Value" required >
                                    @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @else   
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Amount<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount" placeholder="Enter Amount" required >
                                    @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @endif
                           <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Memo / Notes (Optional)</label>
                                <textarea class="form-control" wire:model.debounce.300ms="notes" cols="30" rows="5"></textarea>
                                @error('notes') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
