<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Edit Quotation</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="update()" >
                        <div class="modal-body">
                          
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Quotation Number<span class="required" style="color: red">*</span></label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="quotation_number" placeholder="Enter Quotation Number" required>
                                        @error('quotation_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        <small style="color: green">
                                            @if (Auth::user()->employee->company->quotation_serialize_by_customer == True)
                                            Quotation number is serialized by customer
                                            @else   
                                            Quotation serialization default
                                            @endif
                                           </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Customers<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedCustomer" class="form-control" required >
                                           <option value="">Select Customers</option>
                                         @foreach ($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('selectedCustomer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        <small>  <a href="{{ route('customers.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Customer</a></small> 
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subheading">Subheading</label>
                                    <input type="text" class="form-control" wire:model.debounce.300ms="subheading" placeholder="Enter Subheading" >
                                    @error('subheading') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                                <div class="col-md-3">
                                <div class="form-group">
                                    <label for="vat">VAT</label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="vat" placeholder="Enter VAT" disabled>
                                    @error('vat') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to">Currency<span class="required" style="color: red">*</span></label>
                                      <select class="form-control" wire:model.debounce.300ms="currency_id" required>
                                          <option value="">Select Currency</option>
                                          @foreach ($currencies as $currency)
                                              <option value="{{$currency->id}}">{{$currency->name}} </option>
                                          @endforeach
                                      </select>
                                        @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                        <small>  <a href="{{ route('currencies.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Currency</a></small> 
                                    </div>
                                    @if (!is_null($currency_id))
                                    @if (Auth::user()->employee->company)
                                        @if ($currency_id != Auth::user()->employee->company->currency_id)
                                            <div class="form-group">
                                                <label for="customer">Exchange Rate</label>
                                                <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_rate" placeholder="The exchange rate @ trip date">
                                                @error('exchange_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                                            </div> 
                                        @endif
                                    @endif
                                @endif  
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="stops">Bank Accounts</label>
                                        <select wire:model.debounce.300ms="bank_account_id" class="form-control" multiple>
                                                <option value="">Select Bank Account</option>
                                                @foreach ($bank_accounts as $bank_account)
                                                    <option value="{{ $bank_account->id }}">{{ $bank_account->name }} {{ $bank_account->account_number }}</option>
                                                @endforeach
                                        </select>
                                        @error('bank_account_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                          
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Date<span class="required" style="color: red">*</span></label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="date" placeholder="Enter Quotation Date" required >
                                        @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Expires On</label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="expiry" placeholder="Enter Quotation Expiry Date" >
                                        @error('expiry') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="memo">Notes / Terms & Conditions</label>
                                   <textarea class="form-control" wire:model.debounce.300ms="memo" cols="30" rows="3" ></textarea>
                                    @error('memo') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="footer">Footer</label>
                                       <textarea class="form-control" wire:model.debounce.300ms="footer" cols="30" rows="3" ></textarea>
                                        @error('footer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="btn-group" role="group">
                                <button type="button" onclick="goBack()" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-arrow-left"></i>Back</button>
                                <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-refresh"></i>Update</button>
                            </div>
                            <!-- /.btn-group -->
                        </div>
                    </form>





                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->


            </div>

        </div>
        <!-- /.container-fluid -->
    </section>
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="expenseModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-tasks"></i> Add Expense <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="expense()" >
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" wire:model.debounce.300ms="expense_name" placeholder="Enter Expense Name" >
                        @error('expense_name') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
