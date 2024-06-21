<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Add New Asset</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="store()" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Purchase Orders</label>
                                        <select wire:model.debounce.300ms="selectedPurchase" class="form-control" >
                                            <option value="">Select Purchase Order</option>
                                            @foreach ($purchases as $purchase)
                                            <option value="{{$purchase->id}}">{{$purchase->purchase_number}} | {{$purchase->vendor ? $purchase->vendor->name : ""}} | {{ $purchase->currency ? $purchase->currency->name : "" }} {{ $purchase->currency ? $purchase->currency->symbol : "" }}{{number_format($purchase->value,2)}} | {{ $purchase->date }}</option>
                                            @endforeach
                                        </select>
                                        <small>  <a href="{{ route('purchases.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Purchase Order</a></small> 
                                        @error('selectedPurchase') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Vendors</label>
                                        <select wire:model.debounce.300ms="vendor_id" class="form-control" >
                                            <option value="">Select Vendor</option>
                                            @foreach ($vendors as $vendor)
                                            <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                            @endforeach
                                        </select>
                                        <small>  <a href="{{ route('vendors.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Vendor</a></small> 
                                        @error('vendor_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Categories<span class="required" style="color: red">*</span></label>
                                        <select wire:model.debounce.300ms="selectedCategory" class="form-control" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedCategory') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Sub Categories<span class="required" style="color: red">*</span></label>
                                        <select wire:model.debounce.300ms="selectedCategoryValue" class="form-control" required>
                                            <option value="">Select Sub Categories</option>
                                            @foreach ($category_values as $category_value)
                                            <option value="{{$category_value->id}}">{{$category_value->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedCategoryValue') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
               
                            <div class="row">
                                <div class="col-md-6">
                                    @if (is_null($selectedPurchase))
                                    <div class="form-group">
                                        <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedProduct" class="form-control" required>
                                           <option value="">Select Product</option>
                                         @foreach ($products as $product)
                                         <option value="{{$product->id}}"> {{$product->product_number}} {{$product->brand ? $product->brand->name : ""}} {{$product->name}} {{$product->model}}</option>
                                         @endforeach
                                       </select>
                                       <small>  <a href="{{ route('products.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Product</a></small> 
                                        @error('selectedProduct') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    @else   
                                    <div class="form-group">
                                        <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedProduct" class="form-control" required>
                                           <option value="">Select Product</option>
                                         @foreach ($purchase_products as $purchase_product)
                                         <option value="{{$purchase_product->product->id}}"> {{$purchase_product->product ? $purchase_product->product->product_number : ""}} {{$purchase_product->product->brand ? $purchase_product->product->brand->name : ""}} {{$purchase_product->product ? $purchase_product->product->name : ""}} {{$purchase_product->product ? $purchase_product->product->model : ""}}</option>
                                         @endforeach
                                       </select>
                                       <small>  <a href="{{ route('products.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Product</a></small> 
                                        @error('selectedProduct') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Serial Number</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="serial_number.0" placeholder="Enter Product Serial Number" >
                                        @error('serial_number.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    @foreach ($inputs as $key => $value)
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="name">Serial Number</label>
                                                <input type="text" class="form-control" wire:model.debounce.300ms="serial_number.{{$value}}" placeholder="Enter Product Serial Number">
                                                @error('serial_number.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
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
                                                <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i> SN#</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                           
                            <div class="row">
                               
                                    @if ($serial_number)
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Rate</label>
                                                <input type="text" class="form-control" wire:model.debounce.300ms="rate" placeholder="Enter Product Rate/Amount $" >
                                                @error('rate') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>     
                                    @else 
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Quantity</label>
                                                <input type="number" min="1" class="form-control" wire:model.debounce.300ms="qty" placeholder="Enter Product Quantity" >
                                                @error('qty') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Rate</label>
                                                <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="rate" placeholder="Enter Product Rate/Amount $" >
                                                @error('rate') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    @endif     
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="residual_value">Residual Value</label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="residual_value" placeholder="Enter Product Residual Value">
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
