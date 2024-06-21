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
                                <a href="" data-toggle="modal" data-target="#accountModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Account</a>
                            </div>
                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                            <table id="accountsTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Group
                                    </th>
                                    <th class="th-sm">Type
                                    </th>
                                    <th class="th-sm">Name
                                    </th>
                                    <th class="th-sm">Description
                                    </th>
                                    <th class="th-sm">Action
                                    </th>
                                  </tr>
                                </thead>
                                @if ($accounts->count()>0)
                                <tbody>
                                    @foreach ($accounts as $account)
                                  <tr>
                                    <td>{{$account->account_type->account_type_group ? $account->account_type->account_type_group->name : ""}}</td>
                                    <td>{{$account->account_type ? $account->account_type->name : ""}}</td>
                                    <td>{{$account->account_reference}} {{$account->name}}</td>
                                    <td>{{$account->description}}</td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ route('accounts.show',$account->id) }}"  ><i class="fas fa-eye color-default" ></i> View</a></li>
                                                <li><a href="#"  wire:click="showTransaction({{$account->id}})" ><i class="fas fa-credit-card color-primary" ></i> Transact</a></li>
                                                <li><a href="#"  wire:click="edit({{$account->id}})" ><i class="fas fa-edit color-success" ></i> Edit</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#accountDeleteModal{{ $account->id }}"  ><i class="fas fa-trash color-danger"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                        @include('accounts.delete')
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
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-credit-card"></i> Add Transaction <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="storeTransaction()" >
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
                                <label for="name">Description</label>
                               <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="2" placeholder="Enter description"></textarea>
                                @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Account<span class="required" style="color: red">*</span></label>
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
                                        <option value="">Select Category</option>
                                        @foreach ($account_types as $account_type)
                                             <option value="{{ $account_type->name }}">{{ $account_type->name }}</option>
                                        @endforeach
                                     
                                    </select>
                                @error('transaction_category') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
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
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Add Account <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="store()" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Account Types<span class="required" style="color: red">*</span></label>
                                    <select wire:model.debounce.300ms="account_type_id" class="form-control" required>
                                        <option value="">Select Account Type</option>
                                        @foreach ($account_types as $account_type)
                                             <option value="{{ $account_type->id }}">{{ $account_type->name }}</option>
                                        @endforeach
                                    </select>
                                @error('account_type_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Account Name<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="name" placeholder="Enter Account Name" required />
                                @error('name') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                   
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Account Currency</label>
                                    <select wire:model.debounce.300ms="selectedCurrency" class="form-control">
                                        <option value="">Select Currency</option>
                                        @foreach ($currencies as $currency)
                                             <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                        @endforeach
                                    </select>
                                @error('selectedCurrency') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Account Reference</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="account_reference" placeholder="Enter Account Ref"  />
                                @error('account_reference') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                           
                           </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Linked Account - Bank Account</label>
                                    <select wire:model.debounce.300ms="bank_account_id" class="form-control">
                                        <option value="">Select Bank Account</option>
                                        @foreach ($bank_accounts as $bank_account)
                                             <option value="{{ $bank_account->id }}">{{ $bank_account->name }} {{ $bank_account->account_number }} {{ $bank_account->branch }}</option>
                                        @endforeach
                                    </select>
                                    <small>  <a href="{{ route('bank_accounts.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Bank Account</a></small> 
                                @error('bank_account_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Description</label>
                               <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="3"></textarea>
                                @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="accountEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-edit"></i> Edit Account <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="update()" >
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Account Types<span class="required" style="color: red">*</span></label>
                                        <select wire:model.debounce.300ms="account_type_id" class="form-control" required>
                                            <option value="">Select Account Type</option>
                                            @foreach ($account_types as $account_type)
                                                 <option value="{{ $account_type->id }}">{{ $account_type->name }}</option>
                                            @endforeach
                                        </select>
                                    @error('account_type_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Account Name<span class="required" style="color: red">*</span></label>
                                    <input type="text" class="form-control" wire:model.debounce.300ms="name" placeholder="Enter Account Name" required />
                                    @error('name') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                       
                        <div class="row"> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Account Currency</label>
                                        <select wire:model.debounce.300ms="selectedCurrency" class="form-control">
                                            <option value="">Select Currency</option>
                                            @foreach ($currencies as $currency)
                                                 <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    @error('selectedCurrency') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                           <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Account Reference</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="account_reference" placeholder="Enter Account Ref"  />
                                @error('account_reference') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                           
                           </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Linked Account - Bank Account</label>
                                        <select wire:model.debounce.300ms="bank_account_id" class="form-control">
                                            <option value="">Select Bank Account</option>
                                            @foreach ($bank_accounts as $bank_account)
                                                 <option value="{{ $bank_account->id }}">{{ $bank_account->name }} {{ $bank_account->account_number }} {{ $bank_account->branch }}</option>
                                            @endforeach
                                        </select>
                                        <small>  <a href="{{ route('bank_accounts.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Bank Account</a></small> 
                                    @error('bank_account_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Description</label>
                                   <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="3"></textarea>
                                    @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-refresh"></i>Update</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>



</div>

