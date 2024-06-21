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
                                <a href="{{route('bills.create')}}"  class="btn btn-default"><i class="fa fa-plus-square-o"></i>Bill</a>
                            </div>
                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">
                            <div class="panel-title">
                                <div class="row">
                                
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                  Filter By
                                  </span>
                                  <select wire:model.debounce.300ms="bill_filter" class="form-control" aria-label="..." >
                                    <option value="created_at">Bill Created At</option>
                              </select>
                                    </div>
                                    <!-- /input-group -->
                                </div>
                                @if ($bill_filter == "created_at")
                                <div class="col-lg-2" style="margin-right: 7px">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                  From
                                  </span>
                                  <input type="date" wire:model.debounce.300ms="from" wire:change="dateRange()" class="form-control" aria-label="...">
                                    </div>
                                    <!-- /input-group -->
                                </div>
                                <div class="col-lg-2" style="margin-left: 30px">
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
                            <div class="col-md-3" style="float: right; padding-right:0px">
                                <div class="form-group">
                                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search bills...">
                                </div>
                            </div>

                            <table  class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Bill#
                                    </th>
                                    <th class="th-sm">Category
                                    </th>
                                    <th class="th-sm">Date
                                    </th>
                                    <th class="th-sm">Currency
                                    </th>
                                    <th class="th-sm">Total
                                    </th>
                                    <th class="th-sm">Paid
                                    </th>
                                    <th class="th-sm">Amount Due
                                    </th>
                                    <th class="th-sm">Payment Status
                                    </th>
                                    <th class="th-sm">Authorization
                                    </th>
                                    <th class="th-sm">Action
                                    </th>
                                  </tr>
                                </thead>
                                @if (isset($bills))
                                <tbody>
                                    @forelse ($bills as $bill)
                                        
                                   
                                   
                                  <tr>
                                    <td>{{$bill->bill_number}}</td>
                                    <td>
                                        @if ($bill->transporter)
                                            Transporter | <a href="{{ route('transporters.show',$bill->transporter->id) }}" style="color: blue" target="_blank">{{ $bill->transporter ? $bill->transporter->name  : ""}}</a> 
                                        @elseif($bill->vendor && ($bill->container == NULL || $bill->top_up == NULL))
                                            Vendor | <a href="{{ route('vendors.show',$bill->vendor->id) }}" style="color: blue" target="_blank">{{ $bill->vendor ? $bill->vendor->name : "" }}</a> 
                                        @elseif ( $bill->container && $bill->top_up)
                                            Fuel Topup | <a href="{{ route('containers.show', $bill->container->id) }}" style="color: blue" target="_blank">{{ $bill->container ? $bill->container->name : "" }}</a> 
                                        @elseif ( $bill->fuel)
                                            @if ($bill->trip)
                                            Trip Expense - Fuel Order | <a href="{{ route('fuels.show', $bill->fuel->id) }}" style="color: blue" target="_blank">{{ $bill->fuel ? $bill->fuel->order_number : "" }}</a> | <a href="{{ route('trips.show', $bill->trip->id) }}" style="color: blue" target="_blank">{{ $bill->trip->trip_number }}</a> 
                                            @else
                                            Fuel Order | <a href="{{ route('fuels.show', $bill->fuel->id) }}" style="color: blue" target="_blank">{{ $bill->fuel ? $bill->fuel->order_number : "" }}</a> 
                                            @endif
                                           
                                        @elseif ( $bill->invoice)
                                            Invoice VAT | <a href="{{ route('invoices.show', $bill->invoice->id) }}" style="color: blue" target="_blank">{{ $bill->invoice ? $bill->invoice->invoice_number : "" }}</a> 
                                        @elseif ( $bill->ticket)
                                            Ticket | <a href="{{ route('tickets.show', $bill->ticket->id) }}" style="color: blue" target="_blank">{{  $bill->ticket ? $bill->ticket->ticket_number : "" }}</a> 
                                        @elseif ($bill->trip && ($bill->horse || $bill->driver || $bill->driver))
                                            Trip Expense | <a href="{{ route('trips.show', $bill->trip->id) }}" style="color: blue" target="_blank">{{ $bill->trip->trip_number }}</a> 
                                        @elseif ($bill->purchase)
                                            {{ $bill->category }} | <a href="{{ route('purchases.show', $bill->purchase->id) }}" style="color: blue" target="_blank">{{ $bill->purchase->purchase_number }}</a> 
                                        @elseif ($bill->workshop_service)
                                            Service | {{$bill->workshop_service->account ? $bill->workshop_service->account->name : ""}} | <a href="{{ route('workshop_services.show', $bill->workshop_service->id) }}" style="color: blue" target="_blank">{{ $bill->workshop_service->workshop_service_number }}</a> 
                                        @endif
                                    </td>
                                    <td>{{$bill->bill_date}}</td>
                                    <td>{{$bill->currency ? $bill->currency->name : ""}}</td> 
                                    <td>
                                        @if ($bill->total)
                                             {{$bill->currency ? $bill->currency->symbol : ""}}{{number_format($bill->total,2)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($bill->payments)
                                             {{$bill->currency ? $bill->currency->symbol : ""}}{{number_format($bill->payments->sum('amount'),2)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($bill->balance)
                                             {{$bill->currency ? $bill->currency->symbol : ""}}{{number_format($bill->balance,2)}}
                                        @else   
                                        {{$bill->currency ? $bill->currency->symbol : ""}}{{number_format(0,2) }}
                                        @endif
                                    </td>
                                    <td><span class="label label-{{($bill->status == 'Paid') ? 'success' : (($bill->status == 'Partial') ? 'warning' : 'danger') }}">{{ $bill->status }}</span></td>
                                    <td><span class="badge bg-{{($bill->authorization == 'approved') ? 'success' : (($bill->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($bill->authorization == 'approved') ? 'approved' : (($bill->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('bills.show',$bill->id)}}"  ><i class="fas fa-eye color-default"></i>View</a></li>
                                                @if ($bill->authorization == "approved")
                                                     <li><a href="#" wire:click="showPayment({{$bill->id}})"  ><i class="fas fa-credit-card color-primary"></i> Record Payment</a></li>
                                                @endif
                                                <li><a href="{{route('bills.edit',$bill->id)}}"  ><i class="fas fa-edit color-success"></i> Edit</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#billDeleteModal{{ $bill->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                        @include('bills.delete')
                                </td>
                                  </tr>
                                  @empty
                                  <tr>
                                    <td colspan="10">
                                        <div style="text-align:center; text-color:grey; padding-top:5px; padding-bottom:5px; font-size:17px">
                                            No Bills Found ....
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
                                    @if (isset($bills))
                                        {{ $bills->links() }} 
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name Of Payer<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="name" placeholder="Enter Name" required disabled>
                                @error('name') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Surname Of Payer<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="surname" placeholder="Enter Surname" required disabled>
                                @error('surname') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Date Of Payment<span class="required" style="color: red">*</span></label>
                        <input type="date" class="form-control" wire:model.debounce.300ms="date" placeholder="Enter Date" required />
                        @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Payment Type<span class="required" style="color: red">*</span></label>
                               <select wire:model.debounce.300ms="payment_type" class="form-control" required >
                                   <option value="">Select payment type</option>
                                   <option value="Down payment">Down payment</option>
                                   <option value="Partial payment">Partial payment</option>
                                   <option value="Half payment">Half payment</option>
                                   <option value="Full payment">Full payment</option>
                               </select>
                                @error('payment_type') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Method Of Payments<span class="required" style="color: red">*</span></label>
                               <select wire:model.debounce.300ms="mode_of_payment" class="form-control" required >
                                   <option value="">Select MOP</option>
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
                   
                    @if ($mode_of_payment == "RTGS" || $mode_of_payment == "ECOCASH" || $mode_of_payment == "NOSTRO" || $mode_of_payment == "VISA"  )
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
                        <label for="country">Payment Accounts<span class="required" style="color: red">*</span></label>
                       <select wire:model.debounce.300ms="account_id" class="form-control" required>
                           <option value="">Select Payment Account</option>
                           @foreach ($accounts as $account)
                           @if ($bill_currency)
                           @if ($account->currency->id == $bill_currency->id)
                           <option value="{{ $account->id }}">{{ $account->name }} {{ $account->currency ? $account->currency->name : ""}}</option>
                           @endif     
                           @else  
                           select currency for bill
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
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount" placeholder="Enter Value" required />
                                @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @else   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Amount<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount" placeholder="Enter Amount" required />
                                @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endif
                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Balance<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="current_balance" placeholder="Current Balance" disabled required />
                                @error('current_balance') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Memo / Notes (Optional)</label>
                        <textarea class="form-control" wire:model.debounce.300ms="notes" cols="30" rows="5" placeholder="Write payment notes..."></textarea>
                        @error('notes') <span class="error" style="color:red">{{ $message }}</span> @enderror
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

