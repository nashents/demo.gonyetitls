<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">

                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Edit Tyres</h5>
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
                                            <select wire:model.debounce.300ms="currency_id" class="form-control" >
                                                <option value="">Select Currency</option>
                                              @foreach ($currencies as $currency)
                                                 <option value="{{$currency->id}}">{{$currency->name}}</option>
                                              @endforeach
                                            </select>
                                            @error('currency_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                    </div> 
                                </div>
                               
                                <h5 class="underline mt-30">Tyre Details</h5>
                                <small style="color: red">205 / 65 R 15 </small>
                                <div class="row">
                                    <div class="col-md-3">
                                        @if (is_null($selectedPurchase))
                                        <div class="form-group">
                                            <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                           <select wire:model.debounce.300ms="selectedProduct" class="form-control" required>
                                               <option value="">Select Product</option>
                                             @foreach ($products as $product)
                                                <option value="{{$product->id}}"> {{$product->brand ? $product->brand->name : ""}} {{$product->name}}</option>
                                             @endforeach
                                           </select>
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="width">Type</label>
                                          <select class="form-control" wire:model.debounce.300ms="type" >
                                            <option value="">Select Type</option>
                                            <option value="Diff">Diff</option>
                                            <option value="Multipurpose">Multipurpose</option>
                                            <option value="Steer">Steer</option>
                                            <option value="Supersingle">Supersingle</option>
                                          </select>
                                            @error('type') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="width">Serial#</label>
                                            <input type="text" class="form-control" wire:model.debounce.300ms="serial_number"  placeholder="Serial# " />
                                            @error('serial_number') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="width">Rate</label>
                                            <input type="number" class="form-control" step="any" wire:model.debounce.300ms="rate"  placeholder="Rate" />
                                            @error('rate') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
    
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="width">Width<i>(mL)</i> /</label>
                                            <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="width"  placeholder="Width " />
                                            @error('width') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="aspect_ratio">A/Ratio (R)<span class="required" style="color: red">*</span></label>
                                            <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="aspect_ratio" placeholder="Aspect Ratio" required />
                                            @error('aspect_ratio') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="diameter">Diameter/Rim<i>(in)</i><span class="required" style="color: red">*</span></label>
                                            <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="diameter"  placeholder="Diameter " required />
                                            @error('diameter') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
    
                                </div>
                                <br>
                              
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
                                            <input type="text" class="form-control" wire:model.debounce.300ms="life" placeholder="Useful Life">
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="condition">Status</label>
                                            <select wire:model.debounce.300ms="status" class="form-control" >
                                                <option value="">Select Status</option>
                                                <option value="1">Available</option>
                                                <option value="0">Unavailable</option>
                                            </select>
                                            @error('status') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
