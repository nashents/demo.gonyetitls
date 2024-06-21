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
                           
                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Payment Account(s)</label>
                                        <select class="form-control" wire:model.debounce.300ms="selectedAccount">
                                            <option value="">Select Account</option>
                                            <option value="All">All Accounts</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }} {{ $account->currency ? $account->currency->name : "" }}</option>
                                            @endforeach
                                            @error('name') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8 mb-15">
                                    <label for="">Transactions</label>
                                    <button type="button" data-toggle="modal" data-target="#incomeModal" class="btn btn-default border-primary btn-rounded btn-wide">Add Income</button>
                                    <button type="button" data-toggle="modal" data-target="#expenseModal"  class="btn btn-default border-primary btn-rounded btn-wide">Add Expense</button>

                                </div>
                           
                            </div>
                           
                            <table id="cashflowsTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Date
                                    </th>
                                    <th class="th-sm">Account
                                    </th>
                                    <th class="th-sm">Currency
                                    </th>
                                    <th class="th-sm">Amount
                                    </th>
                                    <th class="th-sm">Action
                                    </th>
                                  </tr>
                                </thead>
                                @if ($cashflows->count()>0)
                                <tbody>
                                    @foreach ($cashflows as $cashflow)
                                  <tr>
                                    <td>{{$cashflow->date}}</td>
                                    <td>
                                        @if ($cashflow->account)
                                            <a href="{{ route('accounts.show',$cashflow->account->id) }}" style="color: blue" >{{$cashflow->account ? $cashflow->account->name : ""}} {{$cashflow->currency ? $cashflow->currency->name : ""}}</a>
                                        @endif
                                    </td>
                                    <td>{{$cashflow->currency ? $cashflow->currency->name : ""}}</td>
                                    <td>
                                            @if (isset($cashflow->amount))
                                            {{$cashflow->currency ? $cashflow->currency->symbol : ""}}{{number_format($cashflow->amount,2)}}
                                            @endif
                                    </td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('cashflows.show',$cashflow->id)}}"  ><i class="fa fa-eye color-default"></i> View</a></li>
                                                {{-- <li><a href="#" wire:click="edit({{$cashflow->id}})"  ><i class="fa fa-edit color-success"></i> Edit</a></li> --}}
                                                <li><a href="#" wire:click="delete({{$cashflow->id}})" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                       
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

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal fade" id="cashflowDeleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-danger">
                <div class="modal-body">
                   <center> <strong>Are you sure you want to delete this Transaction?</strong> </center> 
                </div>
                <form wire:submit.prevent="deleteTransaction()"  >
                <div class="modal-footer no-border">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn bg-white btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-black btn-wide btn-rounded" ><i class="fa fa-trash"></i>Delete</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="incomeModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-plus"></i> Add Income Transaction <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="storeIncomeTransaction()" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Date<span class="required" style="color: red">*</span></label>
                                <input type="date" class="form-control" wire:model.debounce.300ms="date" placeholder="Date" required />
                                @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Description<span class="required" style="color: red">*</span></label>
                               <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="2" placeholder="Enter description" required></textarea>
                                @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Payment Accounts<span class="required" style="color: red">*</span></label>
                                    <select wire:model.debounce.300ms="account_id" class="form-control" required>
                                        <option value="">Select Account</option>
                                        @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }} {{ $account->currency ? $account->currency->name : "" }}</option>
                                        @endforeach
                                    </select>
                                @error('account_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Type<span class="required" style="color: red">*</span></label>
                                    <select wire:model.debounce.300ms="transaction_type" class="form-control" required>
                                        <option value="">Select Type</option>
                                      <option value="Deposit">Deposit</option>
                                    </select>
                                @error('transaction_type') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Amount<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount" placeholder="Enter Amount" required />
                                @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Category</label>
                                    <select wire:model.debounce.300ms="transaction_category" class="form-control">
                                        <option value="">Select Category Type</option>
                                        @foreach ($account_types as $account_type)
                                             <option value="{{ $account_type->name }}">{{ $account_type->name }}</option>
                                        @endforeach
                                     
                                    </select>
                                @error('transaction_category') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    {{-- <h5 class="underline mt-30">Trip Expense(s)</h5> --}}
                    <div class="mb-10">
                        <input type="checkbox" wire:model.debounce.300ms="customer"   class="line-style" />
                        <label for="one" class="radio-label">Add Customer</label>
                        @error('customer') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    @if ($customer == TRUE)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Customers</label>
                                    <select wire:model.debounce.300ms="customer_id" class="form-control">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                             <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                     
                                    </select>
                                @error('customer_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    @endif
                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Notes</label>
                               <textarea class="form-control" wire:model.debounce.300ms="notes" cols="30" rows="3" placeholder="Enter Notes"></textarea>
                                @error('notes') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Receipt</label>
                               <input type="file" class="form-control" wire:model.debounce.300ms="receipt">
                                @error('receipt') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-plus"></i> Add Expense Transaction <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="storeExpenseTransaction()" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Date<span class="required" style="color: red">*</span></label>
                                <input type="date" class="form-control" wire:model.debounce.300ms="date" placeholder="Date" required />
                                @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Expenses<span class="required" style="color: red">*</span></label>
                                    <select wire:model.debounce.300ms="expense_id" class="form-control" required>
                                        <option value="">Select Expense</option>
                                        @foreach ($expenses as $expense)
                                             <option value="{{ $expense->id }}">{{ $expense->name }}</option>
                                        @endforeach
                                    </select>
                                @error('expense_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Payment Accounts<span class="required" style="color: red">*</span></label>
                                    <select wire:model.debounce.300ms="account_id" class="form-control" required>
                                        <option value="">Select Account</option>
                                        @foreach ($accounts as $account)
                                             <option value="{{ $account->id }}">{{ $account->name }} {{ $account->currency ? $account->currency->name : "" }}</option>
                                        @endforeach
                                    </select>
                                @error('account_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Type<span class="required" style="color: red">*</span></label>
                                    <select wire:model.debounce.300ms="transaction_type" class="form-control" required>
                                        <option value="">Select Type</option>
                                      <option value="Withdrawal">Withdrawal</option>
                                    </select>
                                @error('transaction_type') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Amount<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount" placeholder="Enter Amount" required />
                                @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Category</label>
                                    <select wire:model.debounce.300ms="transaction_category" class="form-control">
                                        <option value="">Select Category Type</option>
                                        @foreach ($account_types as $account_type)
                                             <option value="{{ $account_type->name }}">{{ $account_type->name }}</option>
                                        @endforeach
                                     
                                    </select>
                                @error('transaction_category') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-10">
                        <input type="checkbox" wire:model.debounce.300ms="customer"   class="line-style" />
                        <label for="one" class="radio-label">Add Customer</label>
                        <input type="checkbox" wire:model.debounce.300ms="vendor"   class="line-style" />
                        <label for="one" class="radio-label">Add Vendor</label>
                        @error('vendor') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                 
                    <div class="row">
                        @if ($customer == TRUE)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Customers</label>
                                    <select wire:model.debounce.300ms="customer_id" class="form-control">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                             <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                     
                                    </select>
                                @error('customer_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endif
                        @if ($vendor == TRUE)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Vendors</label>
                                    <select wire:model.debounce.300ms="vendor_id" class="form-control">
                                        <option value="">Select Vendor</option>
                                        @foreach ($vendors as $vendor)
                                             <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                     
                                    </select>
                                @error('vendor_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endif
                    </div>
                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Notes</label>
                               <textarea class="form-control" wire:model.debounce.300ms="notes" cols="30" rows="3" placeholder="Enter Notes"></textarea>
                                @error('notes') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Receipt</label>
                               <input type="file" class="form-control" wire:model.debounce.300ms="receipt">
                                @error('receipt') <span class="error" style="color:red">{{ $message }}</span> @enderror
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

