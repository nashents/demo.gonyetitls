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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Categories<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedCategory" class="form-control" required>
                                           <option value="">Select Category</option>
                                         @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('selectedCategory') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Sub Categories</label>
                                       <select wire:model.debounce.300ms="selectedCategoryValue" class="form-control">
                                           <option value="">Select SubCategory</option>
                                         @foreach ($category_values as $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('selectedCategoryValue') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="brand">Brands</label>
                                       <select wire:model.debounce.300ms="brand_id" class="form-control">
                                           <option value="">Select Brand</option>
                                         @foreach ($brands as $brand)
                                            <option value="{{$brand->id}}">{{$brand->name}}</option>
                                         @endforeach
                                       </select>
                                        @error('brand_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name<span class="required" style="color: red">*</span></label>
                                    <input type="text" class="form-control" wire:model.debounce.300ms="name" placeholder="Enter Product Name" required>
                                    @error('name') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Model</label>
                                    <input type="text" class="form-control" wire:model.debounce.300ms="model" placeholder="Enter Product Name" >
                                    @error('model') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                                </div>
                            </div>
            
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
                                        <label for="footer">Specifications</label>
                                       <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="3" placeholder="Enter product specifications" ></textarea>
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


</div>
