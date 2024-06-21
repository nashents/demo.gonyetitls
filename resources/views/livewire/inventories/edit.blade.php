<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Edit Inventory</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="update()" >

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
                                           <small>  <a href="{{ route('inventory_purchases.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Purchase Order</a></small> 
                                            @error('selectedPurchase') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="country">Vendors<span class="required" style="color: red">*</span></label>
                                           <select wire:model.debounce.300ms="vendor_id" class="form-control">
                                               <option value="">Select Vendor</option>
                                             @foreach ($vendors as $vendor)
                                                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                             @endforeach
                                           </select>
                                           <small>  <a href="{{ route('vendors.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Vendor</a></small> 
                                            @error('vendor_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">              
                                        @if (isset($rate)) 
                                            <div class="form-group">
                                                <label for="name">Currencies<span class="required" style="color: red">*</span></label>
                                                <select wire:model.debounce.300ms="currency_id" class="form-control" required>
                                                    <option value="">Select Currency</option>
                                                @foreach ($currencies as $currency)
                                                    <option value="{{$currency->id}}">{{$currency->name}}</option>
                                                @endforeach
                                                </select>
                                                @error('currency_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                        @else
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
                                        @endif
                                    </div>

                            </div>
                            <div class="row">

                                <div class="col-md-4">
                                    @if (is_null($selectedPurchase))
                                    <div class="form-group">
                                        <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedProduct" class="form-control" required>
                                           <option value="">Select Product</option>
                                         @foreach ($products as $product)
                                            <option value="{{$product->id}}"> {{$product->brand ? $product->brand->name : ""}} {{$product->name}}</option>
                                         @endforeach
                                       </select>
                                       <small>  <a href="{{ route('inventory_products.create') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Product</a></small> 
                                        @error('selectedProduct') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    @else   
                                    <div class="form-group">
                                        <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedProduct" class="form-control" required>
                                           <option value="">Select Product</option>
                                         @foreach ($purchase_products as $purchase_product)
                                            <option value="{{$purchase_product->product->id}}"> {{$purchase_product->product->brand ? $purchase_product->product->brand->name : ""}} {{$purchase_product->product->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('selectedProduct') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Part#</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="part_number" placeholder="Enter Part#"/>
                                        @error('part_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Serial#</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="serial_number" placeholder="Enter Serial#"/>
                                        @error('serial_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="name">Quantity<span class="required" style="color: red">*</span></label>
                                            <input type="number" min="1" class="form-control" wire:model.debounce.300ms="qty" placeholder="Enter Quantity" required/>
                                            @error('qty') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                        </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="name">Unit Price</label>
                                            <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="rate" placeholder="Enter Unit Price" />
                                            @error('rate') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                        </div>
                          
                            </div>
                          
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="country">Horse Makes</label>
                                       <select wire:model.debounce.300ms="selectedHorseMake" class="form-control" >
                                           <option value="">Select Horse Make</option>
                                         @foreach ($horse_makes as $horse_make)
                                            <option value="{{$horse_make->id}}">{{$horse_make->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('selectedHorseMake') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name">Horse Models</label>
                                    <select wire:model.debounce.300ms="horse_model_id" class="form-control">
                                        <option value="">Select Horse Model</option>
                                        @if (!is_null($selectedHorseMake))
                                        @foreach ($horse_models as $horse_model)
                                         <option value="{{$horse_model->id}}">{{$horse_model->name}}</option>
                                      @endforeach
                                      @endif
                                    </select>
                                    @error('horse_model_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="country">Vehicle Makes</label>
                                       <select wire:model.debounce.300ms="selectedVehicleMake" class="form-control" >
                                           <option value="">Select Vehicle Make</option>
                                         @foreach ($vehicle_makes as $vehicle_make)
                                            <option value="{{$vehicle_make->id}}">{{$vehicle_make->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('selectedVehicleMake') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name">Vehicle Models</label>
                                    <select wire:model.debounce.300ms="vehicle_model_id" class="form-control">
                                        <option value="">Select Horse Model</option>
                                        @if (!is_null($selectedVehicleMake))
                                        @foreach ($vehicle_models as $vehicle_model)
                                        <option value="{{$vehicle_model->id}}">{{$vehicle_model->name}}</option>
                                             @endforeach
                                        @endif

                                    </select>
                                    @error('vehicle_model_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                            </div>

                        

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="country">Stores</label>
                                       <select wire:model.debounce.300ms="store_id" class="form-control">
                                           <option value="">Select Store</option>
                                         @foreach ($stores as $store)
                                            <option value="{{$store->id}}">{{$store->name}}</option>
                                         @endforeach
                                       </select>
                                       <small>  <a href="{{ route('stores.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Store</a></small> 
                                        @error('store_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="purchase_date">Weight/Litreage/Quantity<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="weight" placeholder="Enter Contents" required>
                                        @error('weight') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="purchase_date">Balance<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="balance" placeholder="Remaining Contents" required>
                                        @error('balance') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="country">Measurement<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="measurement" class="form-control" required>
                                           <option value="">Select Measurement</option>
                                           <option value="Litre(s)">Litre(s)</option>
                                           <option value="Gram(s)">Gram(s)</option>
                                           <option value="Kg()s)">Kg(s)</option>
                                           <option value="Ton(s)">Ton(s)</option>
                                           <option value="Piece(s)">Piece(s)</option>
                                           <option value="Item(s)">Item(s)</option>
                                       </select>
                                        @error('measurement') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                         
                              
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purchase_date">Date<span class="required" style="color: red">*</span></label>
                                    <input type="date" class="form-control" wire:model.debounce.300ms="purchase_date" placeholder="Purchase Date" >
                                        @error('purchase_date') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
                            </div>
                            <div class="row">
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
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="life" placeholder="Useful Life" >
                                        @error('life') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
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
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="residual_value">Residual Value</label>
                                    <input type="number" step="" class="form-control" wire:model.debounce.300ms="residual_value" placeholder="Enter Product Residual Value">
                                        @error('residual_value') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                                <div class="col-md-6">
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
                                    <button type="submit" class="btn bg-success btn-wide btn-rounded" > <i class="fa fa-refresh"></i>Update</button>
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
