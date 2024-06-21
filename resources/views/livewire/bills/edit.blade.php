<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Edit Bill</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="update()" >
                        <div class="modal-body">
                            <div class="row">
                                @if ($bill->trip)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Bill #<span class="required" style="color: red">*</span></label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="bill_number" placeholder="Enter Bill Number" required>
                                        @error('bill_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Trips(s)</label>
                                        <select wire:model.debounce.300ms="trip_id" class="form-control" disabled>
                                           <option value="">Select Transporter</option>
                                         @foreach ($trips as $trip)
                                              <option value="{{$trip->id}}">{{$trip->trip_number}} From: {{$trip->loading_point ? $trip->loading_point->name : ""}} To: {{$trip->offloading_point ? $trip->offloading_point->name : ""}}</option> 
                                         @endforeach
                                       </select>
                                        @error('trip_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                   @else
                                   <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Bill #<span class="required" style="color: red">*</span></label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="bill_number" placeholder="Enter Bill Number" required>
                                        @error('bill_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Vendor(s)</label>
                                        <select wire:model.debounce.300ms="vendor_id" class="form-control" >
                                           <option value="">Select Vendor</option>
                                         @foreach ($vendors as $vendor)
                                              <option value="{{$vendor->id}}">{{$vendor->name}} {{$vendor->vendor_number}}</option> 
                                         @endforeach
                                      
                                       </select>
                                        @error('vendor_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        <small>  <a href="{{ route('vendors.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Vendor</a></small> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Transporter(s)</label>
                                        <select wire:model.debounce.300ms="transporter_id" class="form-control">
                                           <option value="">Select Transporter</option>
                                         @foreach ($transporters as $transporter)
                                              <option value="{{$transporter->id}}">{{$transporter->name}} {{$transporter->transporter_number}} </option> 
                                         @endforeach
                                       </select>
                                        @error('transporter_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        <small>  <a href="{{ route('transporters.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Transporter</a></small> 
                                    </div>
                                </div> 
                                @endif
                     
                                
                            </div>
                            <div class="row">
                              
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Bill Date<span class="required" style="color: red">*</span></label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="bill_date" placeholder="Enter Bill Date" required >
                                        @error('bill_date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Due Date</label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="due_date" placeholder="Enter Due Date" >
                                        @error('due_date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                              
                            </div>
                          
                            <div class="row">
                                <div class="col-md-6">
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
                                   
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="subheading">Notes</label>
                                        <textarea class="form-control" wire:model.debounce.300ms="notes" cols="30" rows="5"></textarea>
                                        @error('notes') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                            </div>
                            @if (!is_null($currency_id))
                            @if (Auth::user()->employee->company)
                                @if ($currency_id != Auth::user()->employee->company->currency_id)
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
                                            <label for="customer">Bill Amount {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }}<span class="required" style="color: red">*</span></label>
                                            <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_amount" placeholder="Converted Bill Amount" required>
                                            @error('exchange_amount') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div> 
                                    </div>
                                </div>
                                @endif
                            @endif
                        @endif 
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
