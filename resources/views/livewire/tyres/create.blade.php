<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Add Tyre(s)</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="store()" >
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Purchase Orders</label>
                                        <select wire:model.debounce.300ms="selectedPurchase" class="form-control" >
                                            <option value="">Select Purchase Order</option>
                                            @foreach ($purchases as $purchase)
                                            <option value="{{$purchase->id}}">{{$purchase->purchase_number}} | {{$purchase->vendor ? $purchase->vendor->name : ""}} | {{ $purchase->currency ? $purchase->currency->name : "" }} {{ $purchase->currency ? $purchase->currency->symbol : "" }}{{number_format($purchase->value,2)}} | {{ $purchase->date }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedPurchase') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Vendors</label>
                                        <select wire:model.debounce.300ms="vendor_id" class="form-control">
                                            <option value="">Select Vendor</option>
                                            @foreach ($vendors as $vendor)
                                            <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('vendor_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Currencies</label>
                                        <select wire:model.debounce.300ms="currency_id" class="form-control">
                                            <option value="">Select Currency</option>
                                          @foreach ($currencies as $currency)
                                             <option value="{{$currency->id}}">{{$currency->name}}</option>
                                          @endforeach
                                        </select>
                                        @error('currency_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div> 
                            </div>

                            <h5 class="underline mt-30">Tyre Assignment</h5>
                            <div class="mb-10">
                               <input type="checkbox" wire:model.debounce.300ms="tyre_assignment"   class="line-style" />
                               <label for="one" class="radio-label">Assign Tyre(s)</label>
                               @error('tyre_assignment') <span class="text-danger error">{{ $message }}</span>@enderror
                           </div>
                           @if ($tyre_assignment == True)
                           <div class="mb-10">
                               <label for="">Assignment For?</label>
                               <input type="radio" wire:model.debounce.300ms="assignment_type" value="Horse"  class="line-style"  />
                               <label for="one" class="radio-label">Horse</label>
                               <input type="radio" wire:model.debounce.300ms="assignment_type" value="Trailer"  class="line-style"  />
                               <label for="one" class="radio-label">Trailer</label>
                               <input type="radio" wire:model.debounce.300ms="assignment_type" value="Vehicle"  class="line-style" />
                               <label for="one" class="radio-label">Vehicle</label>
                           </div>      
       
               @if ($assignment_type == "Horse")
               <div class="row">
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="name">Horses<span class="required" style="color: red">*</span></label>
                           <select class="form-control" wire:model.debounce.300ms="horse_id" required>
                               <option value="">Select Horse</option>
                               @foreach ($horses as $horse)
                                    <option value="{{$horse->id}}">{{$horse->registration_number}} {{$horse->horse_make ? $horse->horse_make->name : ""}} {{$horse->horse_model ? $horse->horse_model->name : ""}}</option>
                               @endforeach

                           </select>
                           @error('horse_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="name">Starting Mileage<span class="required" style="color: red">*</span></label>
                           <input type="number" step="any" class="form-control" wire:model.debounce.300ms="starting_odometer" placeholder="Enter Odometer" required>
                           @error('starting_odometer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                       </div>
                   </div>
                  
               </div>
               @elseif ($assignment_type == "Vehicle")
               <div class="row">
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="name">Vehicles<span class="required" style="color: red">*</span></label>
                           <select class="form-control" wire:model.debounce.300ms="vehicle_id" required>
                               <option value="">Select vehicle</option>
                               @foreach ($vehicles as $vehicle)
                                    <option value="{{$vehicle->id}}">{{$vehicle->registration_number}} {{$vehicle->vehicle_make ? $vehicle->vehicle_make->name : ""}} {{$vehicle->vehicle_model ? $vehicle->vehicle_model->name : ""}}</option>
                               @endforeach

                           </select>
                           @error('vehicle_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="name">Starting Mileage<span class="required" style="color: red">*</span></label>
                           <input type="number" step="any" class="form-control" wire:model.debounce.300ms="starting_odometer" placeholder="Enter Odometer" required>
                           @error('starting_odometer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                       </div>
                   </div>
               </div>
               @elseif ($assignment_type == "Trailer")
               <div class="row">
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="name">Trailers<span class="required" style="color: red">*</span></label>
                           <select class="form-control" wire:model.debounce.300ms="trailer_id" required>
                               <option value="">Select Trailer</option>
                               @foreach ($trailers as $trailer)
                                    <option value="{{$trailer->id}}">{{$trailer->registration_number}} {{$trailer->make}} {{$trailer->model}} </option>
                               @endforeach

                           </select>
                           @error('trailer_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="name">Starting Mileage</label>
                           <input type="number" step="any" class="form-control" wire:model.debounce.300ms="starting_odometer" placeholder="Enter Odometer">
                           @error('starting_odometer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                       </div>
                   </div>
               </div>
               @endif
         
              
               <div class="row">
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="name">Wheel Axles<span class="required" style="color: red">*</span></label>
                          <select class="form-control" wire:model.debounce.300ms="axle" required>
                               <option value="">Select Wheel Axle</option>
                               <option value="Steering Axle">Steering Axle</option>
                               <option value="Drive Axle">Drive Axle</option>
                               <option value="Front Axle">Front Axle</option>
                               <option value="Middle Axle">Middle Axle</option>
                               <option value="Rear Axle">Rear Axle</option>
                               <option value="Diff Axle">Diff Axle</option>
                               <option value="Spare Wheel">Spare Wheel</option>

                          </select>
                           @error('axle') <span class="error" style="color:red">{{ $message }}</span> @enderror
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="name">Wheel Positions<span class="required" style="color: red">*</span></label>
                         <select class="form-control" wire:model.debounce.300ms="position" required>
                           <option value="">Select Wheel Position</option>
                           <option value="Front Left Inside">Front Left Inside</option>
                           <option value="Front Left Outside">Front Left Outside</option>
                           <option value="Front Right Inside">Front Right Inside</option>
                           <option value="Front Right Outside">Front Right Outside</option>
                           <option value="Middle Left Inside">Middle Left Inside</option>
                           <option value="Middle Left Outside">Middle Left Outside</option>
                           <option value="Middle Right Inside">Middle Right Inside</option>
                           <option value="Middle Right Outside">Middle Right Outside</option>
                           <option value="Rear Left Inside">Rear Left Inside</option>
                           <option value="Rear Left Outside">Rear Left Outside</option>
                           <option value="Rear Right Inside">Rear Right Inside</option>
                           <option value="Rear Right Outside">Rear Right Outside</option>
                           <option value="Spare Wheel">Spare Wheel</option>
                         </select>
                           @error('position') <span class="error" style="color:red">{{ $message }}</span> @enderror
                       </div>
                   </div>
               </div>

                          
                           @endif
                           
                            <h5 class="underline mt-30">Tyre Details</h5>
                            <small style="color: red">205 / 65 R 15 </small>
                            <div class="row">
                                <div class="col-md-3">
                                    @if (is_null($selectedPurchase))
                                    <div class="form-group">
                                        <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedProduct.0" class="form-control" required>
                                           <option value="">Select Product</option>
                                         @foreach ($products as $product)
                                            <option value="{{$product->id}}"> {{$product->brand ? $product->brand->name : ""}} {{$product->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('selectedProduct.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    @else   
                                    <div class="form-group">
                                        <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedProduct.0" class="form-control" required>
                                           <option value="">Select Product</option>
                                           @foreach ($purchase_products as $purchase_product)
                                           <option value="{{$purchase_product->product->id}}"> {{$purchase_product->product->brand ? $purchase_product->product->brand->name : ""}} {{$purchase_product->product->name}}</option>
                                        @endforeach
                                       </select>
                                        @error('selectedProduct.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    @endif
                                  
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width">Type</label>
                                      <select class="form-control" wire:model.debounce.300ms="type.0" >
                                        <option value="">Select Type</option>
                                        <option value="Diff">Diff</option>
                                        <option value="Multipurpose">Multipurpose</option>
                                        <option value="Steer">Steer</option>
                                      </select>
                                        @error('type.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width">Serial#</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="serial_number.0"  placeholder="Serial# " />
                                        @error('serial_number.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width">Rate</label>
                                        <input type="number" class="form-control" step="any" wire:model.debounce.300ms="rate.0"  placeholder="Rate" />
                                        @error('rate.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="width">Width<i>(mL)</i> /</label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="width.0"  placeholder="Width " />
                                        @error('width.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="aspect_ratio">A/Ratio (R)<span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="aspect_ratio.0" placeholder="Aspect Ratio" required />
                                        @error('aspect_ratio.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="diameter">Diameter/Rim<i>(in)</i><span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="diameter.0"  placeholder="Diameter " required />
                                        @error('diameter.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="width">Thread Depth</label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="thread_depth.0"  placeholder="Tyre Thread Depth " />
                                        @error('thread_depth.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="aspect_ratio">Lifespan in (Kms)</label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="life_span.0" placeholder="Tyre life span in kilometers" />
                                        @error('life_span.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                        
                            </div>

                            @foreach ($inputs as $key => $value)
                            <div class="row">
                                <div class="col-md-3">
                                    @if (is_null($selectedPurchase))
                                    <div class="form-group">
                                        <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedProduct.{{$value}}" class="form-control" required>
                                           <option value="">Select Product</option>
                                         @foreach ($products as $product)
                                            <option value="{{$product->id}}"> {{$product->brand ? $product->brand->name : ""}} {{$product->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('selectedProduct.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    @else   
                                    <div class="form-group">
                                        <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedProduct.{{$value}}" class="form-control" required>
                                           <option value="">Select Product</option>
                                         @foreach ($purchase_products as $purchase_product)
                                            <option value="{{$purchase_product->product->id}}"> {{$purchase_product->product->brand ? $purchase_product->product->brand->name : ""}} {{$purchase_product->product->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('selectedProduct.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width">Type</label>
                                      <select class="form-control" wire:model.debounce.300ms="type.{{$value}}" >
                                        <option value="">Select Type</option>
                                        <option value="Diff">Diff</option>
                                        <option value="Multipurpose">Multipurpose</option>
                                        <option value="Steer">Steer</option>
                                        <option value="Supersingle">Supersingle</option>
                                      </select>
                                        @error('type.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width">Serial#</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="serial_number.{{$value}}"  placeholder="Enter Serial#" />
                                        @error('serial_number.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width">Rate</label>
                                        <input type="number" class="form-control" step="any" wire:model.debounce.300ms="rate.{{$value}}"  placeholder="Enter rate"  />
                                        @error('rate.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="width">Width<i>(mL)</i> /</label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="width.{{$value}}" placeholder="Enter Tyre Width" />
                                        @error('width.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="aspect_ratio">Aspect Ratio (R)<span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="aspect_ratio.{{$value}}"  placeholder="Enter Tyre Aspect Ratio " required/>
                                        @error('aspect_ratio.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="diameter">Diameter/Rim<i>(in)</i><span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="diameter.{{$value}}"  placeholder="Enter Tyre Diameter " required/>
                                        @error('diameter.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>


                               
                                <!-- /.col-md-6 -->
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="width">Thread Depth</label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="thread_depth.{{ $value }}"  placeholder="Tyre Thread Depth " />
                                        @error('thread_depth.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="aspect_ratio">Lifespan in (Kms)</label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="life_span.{{ $value }}" placeholder="Tyre life span in kilometers" />
                                        @error('life_span.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button class="btn btn-danger btn-rounded xs" style="margin-top:23px"  wire:click.prevent="remove({{$key}})"> <i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                        
                            </div>
                            @endforeach
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i> Tyre(s)</button>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="row">
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purchase_date">Date<span class="required" style="color: red">*</span></label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="purchase_date" placeholder="Purchase Date" required>
                                        @error('purchase_date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Stores</label>
                                       <select wire:model.debounce.300ms="store_id" class="form-control">
                                           <option value="">Select Store</option>
                                         @foreach ($stores as $store)
                                            <option value="{{$store->id}}">{{$store->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('store_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="condition">Conditions</label>
                                        <select wire:model.debounce.300ms="condition" class="form-control" >
                                            <option value="">Select Condition</option>
                                            <option value="New">New</option>
                                            <option value="Refurbished">Refurbished</option>
                                            <option value="Second Hand">Second Hand</option>
                                        </select>
                                        @error('condition') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="condition">Purchase Types</label>
                                        <select wire:model.debounce.300ms="purchase_type" class="form-control" >
                                            <option value="">Select Purchase Type</option>
                                            <option value="Owned">Owned</option>
                                            <option value="Rented">Rented</option>
                                            <option value="Leased">Leased</option>
                                        </select>
                                        @error('purchase_type') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purchase_date">Warranty Expiry Date</label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="warranty_exp_date" placeholder="Warranty Expiry Date">
                                        @error('warranty_exp_date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purchase_date">Useful Life</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="life" placeholder="Useful Life">
                                        @error('life') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="condition">Depriciation Types</label>
                                        <select wire:model.debounce.300ms="depreciation_type" class="form-control" >
                                            <option value="">Select Depriciation Type</option>
                                            <option value="Declining Balance">Declining Balance</option>
                                            <option value="Double Declining Balance">Double Declining Balance</option>
                                            <option value="Straight line">Straight line</option>
                                            <option value="Sum of the years digit">Sum of the years digit</option>
                                        </select>
                                        @error('depreciation_type') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="residual_value">Residual Value</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="residual_value" placeholder="Enter Product Residual Value">
                                        @error('residual_value') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea wire:model.debounce.300ms="description" class="form-control" cols="30" rows="5"></textarea>
                                        @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group pull-right mt-10" >
                                   <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                                    <button type="submit" class="btn bg-success btn-wide btn-rounded" > <i class="fa fa-save"></i>Create</button>
                                </div>
                            </div>
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
