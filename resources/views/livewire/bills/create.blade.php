<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 ">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>New Bill</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="store()" >
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Bill#<span class="required" style="color: red">*</span></label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="bill_number" placeholder="Enter Bill Number" required >
                                        @error('bill_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Vendor(s)required</label>
                                        <select wire:model.debounce.300ms="vendor_id" class="form-control" required>
                                           <option value="">Select Vendor</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{$vendor->id}}">{{$vendor->name}} {{$vendor->vendor_number}}</option> 
                                            @endforeach
                                       </select>
                                        @error('vendor_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        <small> <a href="{{ route('vendors.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Vendor</a></small> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Currencies<span class="required" style="color: red">*</span></label>
                                        <select wire:model.debounce.300ms="currency_id" class="form-control" required>
                                           <option value="">Select Currency</option>
                                         @foreach ($currencies as $currency)
                                              <option value="{{$currency->id}}">{{$currency->name}}</option> 
                                         @endforeach
                                      
                                       </select>
                                        @error('currency_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        <small>  <a href="{{ route('currencies.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Currency</a></small> 
                                    </div>
                                    @if (!is_null($currency_id))
                                    @if (Auth::user()->employee->company)
                                        @if ($currency_id != Auth::user()->employee->company->currency_id)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer">Conversion Rate<span class="required" style="color: red">*</span></label>
                                                    <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_rate" wire:change="exchangeRate()" placeholder="Exchange Rate" required>
                                                    @error('exchange_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div> 
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="customer">Bill in {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }}<span class="required" style="color: red">*</span></label>
                                                    <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_amount" placeholder="Converted Bill" required>
                                                    @error('exchange_amount') <span class="text-danger error">{{ $message }}</span>@enderror
                                                </div> 
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                                @endif 
                                </div>
                             
                            </div>
                            <div class="row">
                  
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Bill Date<span class="required" style="color: red">*</span></label>
                                        <input type="date" class="form-control" wire:change="billDate()" wire:model.debounce.300ms="bill_date" placeholder="Enter Bill Date" required >
                                        @error('bill_date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Due Date</label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="due_date" placeholder="Enter Due Date"  >
                                        @error('due_date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="subheading">Notes</label>
                                        <textarea class="form-control" wire:model.debounce.300ms="notes" cols="30" rows="3"></textarea>
                                        @error('notes') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                              
                            </div>
                          
                     
                            <div class="row">
                                <div class="col-md-3">
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="subheading">Expense Accounts<span class="required" style="color: red">*</span></label>
                                        <select wire:model.debounce.300ms="selectedAccount.0" class="form-control" required>
                                            <option value="">Select Expense Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{$account->id}}">{{$account->name}}</option> 
                                        @endforeach
                                    
                                        </select>
                                        @error('selectedAccount.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Description</label>
                                       <textarea wire:model.debounce.300ms="description.0" class="form-control" cols="30" rows="1" placeholder="Enter Description"></textarea>
                                        @error('description.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="name">Qty<span class="required" style="color: red">*</span></label>
                                        <input type="number" class="form-control" wire:model.debounce.300ms="qty.0"  required />
                                        @error('qty.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="name">Price<span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount.0" required />
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
                                    <div class="col-md-2">
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="subheading">Expense Accounts<span class="required" style="color: red">*</span></label>
                                            <select wire:model.debounce.300ms="selectedAccount.{{$value}}" class="form-control" required>
                                                <option value="">Select Expense Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{$account->id}}">{{$account->name}}</option> 
                                            @endforeach
                                        
                                            </select>
                                            @error('selectedAccount.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="name">Description</label>
                                               <textarea wire:model.debounce.300ms="description.{{ $value }}" class="form-control" cols="30" rows="1" placeholder="Enter Description"></textarea>
                                                @error('description.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label for="name">Qty<span class="required" style="color: red">*</span></label>
                                                <input type="number" min="1" class="form-control" wire:model.debounce.300ms="qty.{{ $value }}" placeholder="" required />
                                                @error('qty.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label for="name">Price<span class="required" style="color: red">*</span></label>
                                                <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="amount.{{ $value }}" placeholder="" required />
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
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group" role="group">
                                <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                                <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-save"></i>Save</button>
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
                                <input type="checkbox" wire:model.debounce.300ms="sell"   class="line-style" />
                                <label for="one" class="radio-label">Sell this?</label>
                                @error('sell') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-10">
                                <input type="checkbox" wire:model.debounce.300ms="buy"   class="line-style" disabled />
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
                                @foreach ($accounts as $account)
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
