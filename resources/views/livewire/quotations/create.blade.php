<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>New Quotation</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="store()" >
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
                                        <label for="country"><a href="{{ route('customers.index') }}" target="_blank" style="color:blue">Customers</a><span class="required" style="color: red">*</span></label>
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
                                <div class="col-md-4">
                                <div class="form-group">
                                    <label for="vat">VAT</label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="vat" placeholder="Enter VAT">
                                    @error('vat') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                                <div class="col-md-4">
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
                                            <label for="customer">Conversion Rate<span class="required" style="color: red">*</span></label>
                                            <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_rate" wire:change="exchangeRate()" placeholder="Exchange Rate" required>
                                            @error('exchange_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div> 
                                        @endif
                                    @endif
                                @endif  
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="stops">Bank Accounts</label>
                                        <select wire:model.debounce.300ms="bank_account_id" class="form-control" multiple>
                                                <option value="">Select Bank Account</option>
                                                @foreach ($bank_accounts as $bank_account)
                                                    <option value="{{ $bank_account->id }}">{{ $bank_account->name }} {{ $bank_account->account_name }} {{ $bank_account->account_number }} {{ $bank_account->branch }}</option>
                                                @endforeach
                                        </select>
                                        <small>  <a href="{{ route('bank_accounts.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Bank Account</a></small> 
                                        @error('bank_account_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Date<span class="required" style="color: red">*</span></label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="date" wire:change="quotationDate" placeholder="Enter Quotation Date" required >
                                        @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Expires On</label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="expiry"  placeholder="Enter Quotation Expiry Date" >
                                        @error('expiry') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                               
                            </div>
                            <h5 class="underline mt-30">Product(s)</h5>
                            <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currency">From<span class="required" style="color: red">*</span></label>
                                  <select class="form-control" wire:model.debounce.300ms="from.0" required>
                                      <option value="">Select Starting Location</option>
                                      @foreach ($destinations as $destination)
                                          <option value="{{$destination->id}}">{{$destination->country ? $destination->country->name : ""}} {{$destination->city}}</option>
                                      @endforeach
                                  </select>
                                    @error('from.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    <small>  <a href="{{ route('destinations.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Destination</a></small> 
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="to">To<span class="required" style="color: red">*</span></label>
                                  <select class="form-control" wire:model.debounce.300ms="to.0" required>
                                      <option value="">Select Ending Location</option>
                                      @foreach ($destinations as $destination)
                                          <option value="{{$destination->id}}">{{$destination->country ? $destination->country->name : ""}} {{$destination->city}}</option>
                                      @endforeach
                                  </select>
                                    @error('to.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    <small>  <a href="{{ route('destinations.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Destination</a></small> 
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currency">Loading Point(s)</label>
                                  <select class="form-control" wire:model.debounce.300ms="loading_point_id.0" >
                                      <option value="">Select Loading Point</option>
                                      @foreach ($loading_points as $loading_point)
                                          <option value="{{$loading_point->id}}">{{$loading_point->name}}</option>
                                      @endforeach
                                  </select>
                                    @error('loading_point_id.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    <small>  <a href="{{ route('loading_points.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Loading Point</a></small> 
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currency">Offloading Point(s)</label>
                                  <select class="form-control" wire:model.debounce.300ms="offloading_point_id.0" >
                                      <option value="">Select Offloading Point</option>
                                      @foreach ($offloading_points as $offloading_point)
                                      <option value="{{$offloading_point->id}}">{{$offloading_point->name}}</option>
                                      @endforeach
                                  </select>
                                    @error('offloading_point_id.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    <small>  <a href="{{ route('offloading_points.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Offloading Point</a></small> 
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="customer">Cargo(s)<span class="required" style="color: red">*</span></label>
                                      <select class="form-control" wire:model.debounce.300ms="selectedCargo.0" required>
                                          <option value="">Select Cargo</option>
                                          @foreach ($cargos as $cargo)
                                              <option value="{{$cargo->id}}">{{$cargo->name}}</option>
                                          @endforeach
                                      </select>
                                        @error('selectedCargo.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                        <small>  <a href="{{ route('cargos.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Cargo</a></small> 
                                    </div>
                                </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">Weight(Tons)<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="weight.0" placeholder="Weight in tons" required>
                                    @error('weight.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="amount">Qty<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="qty.0" placeholder="Qty" required>
                                    @error('qty.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="freight">Freight<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="freight.0" placeholder="Freight" required>
                                    @error('freight.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                        @foreach ($inputs as $key => $value)
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currency">From<span class="required" style="color: red">*</span></label>
                                  <select class="form-control" wire:model.debounce.300ms="from.{{ $value }}" required>
                                      <option value="">Select Starting Location</option>
                                      @foreach ($destinations as $destination)
                                          <option value="{{$destination->id}}">{{$destination->country ? $destination->country->name : ""}} {{$destination->city}}</option>
                                      @endforeach
                                  </select>
                                    @error('from.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="to">To<span class="required" style="color: red">*</span></label>
                                  <select class="form-control" wire:model.debounce.300ms="to.{{ $value }}" required>
                                      <option value="">Select Ending Location</option>
                                      @foreach ($destinations as $destination)
                                          <option value="{{$destination->id}}">{{$destination->country ? $destination->country->name : ""}} {{$destination->city}}</option>
                                      @endforeach
                                  </select>
                                    @error('to.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currency">Loading Point</label>
                                  <select class="form-control" wire:model.debounce.300ms="loading_point_id.{{ $value }}" >
                                      <option value="">Select Loading Point</option>
                                      @foreach ($loading_points as $loading_point)
                                          <option value="{{$loading_point->id}}">{{$loading_point->name}}</option>
                                      @endforeach
                                  </select>
                                    @error('loading_point_id.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currency">Offloading Point</label>
                                  <select class="form-control" wire:model.debounce.300ms="offloading_point_id.{{ $value }}" >
                                      <option value="">Select Offloading Point</option>
                                      @foreach ($offloading_points as $offloading_point)
                                      <option value="{{$offloading_point->id}}">{{$offloading_point->name}}</option>
                                      @endforeach
                                  </select>
                                    @error('offloading_point_id.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="customer">Cargo(s)<span class="required" style="color: red">*</span></label>
                                      <select class="form-control" wire:model.debounce.300ms="selectedCargo.{{ $value }}" required>
                                          <option value="">Select Cargo</option>
                                          @foreach ($cargos as $cargo)
                                              <option value="{{$cargo->id}}">{{$cargo->name}}</option>
                                          @endforeach
                                      </select>
                                        @error('selectedCargo.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                               
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="amount">Weight(Tons)<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="weight.{{ $value }}" placeholder="Weight" required>
                                    @error('weight.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="amount">Qty</label>
                                    <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="qty.{{ $value }}" placeholder="Qty">
                                    @error('qty.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                          
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="freight">Freight<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="freight.{{ $value }}" placeholder="Freight" required>
                                    @error('freight.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
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
                                    <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i>Product</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="memo">Notes / Terms & Conditions</label>
                               <textarea class="form-control" wire:model.debounce.300ms="memo" cols="30" rows="3" placeholder="Enter notes or terms of service that are visible to your customer." ></textarea>
                                @error('memo') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="footer">Footer</label>
                                   <textarea class="form-control" wire:model.debounce.300ms="footer" cols="30" rows="3" placeholder="Enter footer for your quotations eg (Tax Information, Thank you note)."></textarea>
                                    @error('footer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                        </div>

                        </div>

                        </div>
                        <div class="modal-footer">
                            <div class="btn-group" role="group">
                                <button type="button" onclick="goBack()" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-arrow-left"></i>Back</button>
                                <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-save"></i>Save</button>
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
