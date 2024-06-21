<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Edit Invoice</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form wire:submit.prevent="update()" >
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
                                                    <label for="customer">Invoice Amount in {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }}<span class="required" style="color: red">*</span></label>
                                                    <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_amount" placeholder="Converted Invoice Amount" required>
                                                    @error('exchange_amount') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div> 
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                                @endif 
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
                                        <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                                        <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-refresh"></i>Update</button>
                                    </div>
                                    <!-- /.btn-group -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->


            </div>

        </div>
        <!-- /.container-fluid -->
    </section>


</div>
