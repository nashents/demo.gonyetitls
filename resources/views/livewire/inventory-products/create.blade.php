<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Add New Product</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="store()" >
                        <div class="modal-body">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Categories<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedCategory" class="form-control" required>
                                           <option value="">Select Category</option>
                                         @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                         @endforeach
                                       </select>
                                       <small>  <a href="{{ route('categories.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Category</a></small> 
                                        @error('selectedCategory') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Sub Categories</label>
                                       <select wire:model.debounce.300ms="selectedCategoryValue" class="form-control" >
                                           <option value="">Select Category</option>
                                         @foreach ($category_values as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                         @endforeach
                                       </select>
                                       <small>  <a href="{{ route('categories.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Sub Category</a></small> 
                                        @error('selectedCategoryValue') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="brand">Brands<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="brand_id" class="form-control" required>
                                           <option value="">Select Brand</option>
                                         @foreach ($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                         @endforeach
                                       </select>
                                       <small>  <a href="{{ route('brands.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Brand</a></small> 
                                        @error('brand_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name<span class="required" style="color: red">*</span></label>
                                    <input type="text" class="form-control" wire:model.debounce.300ms="name" placeholder="Enter Product Name eg 50L Engine Oil, 4x Spark Plug Pack" required>
                                    @error('name') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Attributes</label>
                                       <select wire:model.debounce.300ms="selectedAttribute.0" class="form-control" >
                                           <option value="">Select Attribute</option>
                                         @foreach ($attributes as $attribute)
                                            <option value="{{$attribute->id}}">{{$attribute->name}}</option>
                                         @endforeach
                                       </select>
                                       <small>  <a href="{{ route('attributes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Attribute</a></small> 
                                        @error('selectedAttribute.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Attribute Values</label>
                                       <select wire:model.debounce.300ms="attribute_value_id.0" class="form-control" >
                                           <option value="">Select Value</option>
                                         @foreach ($attribute_values as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('attribute_value_id.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                            </div>
                            {{-- @foreach ($inputs as $key => $value)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="attribute">Attributes<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedAttribute.{{$value}}" class="form-control" required>
                                           <option value="">Select Attribute</option>
                                         @foreach ($attributes as $attribute)
                                            <option value="{{$attribute->id}}">{{$attribute->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('selectedAttribute.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="attribute_value">Attribute Values<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="attribute_value_id.{{$value}}" class="form-control" required>
                                           <option value="">Select Value</option>
                                         @foreach ($attribute_values as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('attribute_value_id.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
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
                                        <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i> Attribute</button>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-4">
                                <div class="form-group">
                                    <label for="manufacturer">Manufacturer</label>
                                  <input type="text" class="form-control" placeholder="Enter Product Manufacturer" wire:model.debounce.300ms="manufacturer">
                                    @error('manufacturer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="footer">Description</label>
                                       <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="3" ></textarea>
                                        @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="image">Product Image</label>
                                          <input type="file" class="form-control" wire:model.debounce.300ms="image" placeholder="Enter Product Image">
                                            @error('image') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
                <!-- /.col-md-6 -->


            </div>

        </div>
        <!-- /.container-fluid -->
    </section>


</div>
