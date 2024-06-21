<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div>
                                @include('includes.messages')
                            </div>

                            <div class="panel-title">
                                <a href="#" data-toggle="modal" data-target="#purchaseModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Purchase Order</a>
                            </div>
                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                            <table id="purchasesTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Purchase#
                                    </th>
                                    <th class="th-sm">Date
                                    </th>
                                    <th class="th-sm">Expense
                                    </th>
                                    <th class="th-sm">Vendor
                                    </th>
                                    <th class="th-sm">Currency
                                    </th>
                                    <th class="th-sm">Total
                                    </th>
                                    <th class="th-sm">Authorization
                                    </th>
                                    <th class="th-sm">Action
                                    </th>
                                  </tr>
                                </thead>
                                @if ($purchases->count()>0)
                                <tbody>
                                    @foreach ($purchases as $purchase)
                                  <tr>
                                    <td>{{ucfirst($purchase->purchase_number)}}</td>
                                    <td>{{$purchase->date}}</td>
                                    <td>{{ucfirst($purchase->expense ? $purchase->expense->name : "")}}</td>
                                    <td>{{ucfirst($purchase->vendor ? $purchase->vendor->name : "")}}</td>
                                    <td>{{ucfirst($purchase->currency ? $purchase->currency->name : "")}}</td>
                                    <td>{{ucfirst($purchase->currency ? $purchase->currency->symbol : "")}}{{ucfirst(number_format($purchase->value,2))}}</td>
                                    <td><span class="badge bg-{{($purchase->authorization == 'approved') ? 'success' : (($purchase->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($purchase->authorization == 'approved') ? 'approved' : (($purchase->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('purchases.show',$purchase->id)}}" ><i class="fa fa-eye color-default"></i> View</a></li>
                                                <li><a href="#"  wire:click="edit({{$purchase->id}})" ><i class="fa fa-edit color-success"></i> Edit</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#purchaseDeleteModal{{ $purchase->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                        @include('purchases.delete')
                                </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                                @else
                                    <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                                 @endif
                              </table>

                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
    </section>
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-plus"></i> Create Purchase Order <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="store()" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Purchase Order#<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="purchase_number" placeholder="Enter Name" required disabled>
                                @error('purchase_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Date<span class="required" style="color: red">*</span></label>
                                <input type="date" class="form-control" wire:model.debounce.300ms="date" placeholder="Enter Purchase Order Date" required >
                                @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                      
                       
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Product">Accounts<span class="required" style="color: red">*</span></label>
                                <select wire:model.debounce.300ms="selectedAccount" class="form-control" required>
                                    <option value="">Select Account</option>
                                    @foreach ($accounts as $account)
                                      <option value="{{$account->id}}">{{$account->name}}</option>
                                    @endforeach
                                </select>
                                @error('selectedAccount') <span class="text-danger error">{{ $message }}</span>@enderror
                                <small>  <a href="{{ route('accounts.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Expense Category</a></small> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Product">Expenses<span class="required" style="color: red">*</span></label>
                                <select wire:model.debounce.300ms="expense_id" class="form-control" required>
                                    <option value="">Select Expense</option>
                                        @if (!is_null($selectedAccount))
                                            @foreach ($expenses as $expense)
                                                <option value="{{$expense->id}}">{{$expense->name}}</option>
                                            @endforeach
                                        @endif
                                </select>
                                @error('expense_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                <small>  <a href="{{ route('expenses.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Expense</a></small> 
                            </div>
                           
                        </div>
                       </div>
                  
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Product">Vendor Types<span class="required" style="color: red">*</span></label>
                                <select wire:model.debounce.300ms="selectedVendorType" class="form-control" required>
                                    <option value="">Select Type</option>
                                    @foreach ($vendor_types as $vendor_type)
                                      <option value="{{$vendor_type->id}}">{{$vendor_type->name}}</option>
                                    @endforeach
                                </select>
                                @error('selectedVendorType') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Product">Vendors<span class="required" style="color: red">*</span></label>
                                <select wire:model.debounce.300ms="vendor_id" class="form-control" required>
                                    <option value="">Select Selected Vendor</option>
                                    @if (!is_null($selectedVendorType))
                                            @foreach ($vendors as $vendor)
                                                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                            @endforeach
                                        @endif
                                </select>
                                @error('vendor_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                <small>  <a href="{{ route('vendors.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Vendor</a></small> 
                            </div>
                           
                        </div>
                       </div>
                       <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Currencies<span class="required" style="color: red">*</span></label>
                                <select wire:model.debounce.300ms="currency_id" class="form-control" required>
                                    <option value="">Select Currency</option>
                                    @foreach ($currencies as $currency)
                                    <option value="{{$currency->id}}">{{$currency->name}}</option>
                                    @endforeach
                                </select>
                                @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Description</label>
                               <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="3"></textarea>
                                @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                       </div>
                  
                   
                    <h5 class="underline mt-n">Select products</h5>

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
                                <label for="title">Sub Categories</label>
                                <select wire:model.debounce.300ms="selectedCategoryValue" class="form-control">
                                    <option value="">Select Sub Category</option>
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
                            <div class="form-group">
                                <label for="title">Products<span class="required" style="color: red">*</span></label>
                                <select wire:model.debounce.300ms="product_id.0" class="form-control" required>
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                    <option value="{{$product->id}}">{{$product->product_number}} {{$product->brand ? $product->brand->name : ""}} {{$product->name}} {{$product->model}}</option>
                                    @endforeach
                                </select>
                                @error('product_id.0') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="Product">Quantity<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="qty.0"  placeholder="Enter Qty " required>
                                @error('qty.0') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="Product">Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" min="0.01" class="form-control" wire:model.debounce.300ms="rate.0"  placeholder="Enter Price " required>
                                @error('rate.0') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                       
                    </div>

                        @foreach ($inputs as $key => $value)
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="title">Products<span class="required" style="color: red">*</span></label>
                                    <select wire:model.debounce.300ms="product_id.{{$value}}" class="form-control" required>
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                        <option value="{{$product->id}}">{{$product->product_number}} {{$product->name}} {{$product->model}}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="Product">Quantity<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="qty.{{$value}}"  placeholder="Enter Qty " required>
                                    @error('qty.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="Product">Rate<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" min="0.01" class="form-control" wire:model.debounce.300ms="rate.{{$value}}"  placeholder="Enter Price " required>
                                    @error('rate.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                           
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for=""></label>
                                    <button class="btn btn-danger btn-rounded xs" style="margin-top:23px"  wire:click.prevent="remove({{$key}})"> <i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <!-- /.col-md-6 -->
                        </div>
                        @endforeach
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i> Product</button>
                                </div>
                            </div>
                        </div>

                        
                        <h5 class="underline mt-n">Upload Purchase Order Attachments</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Titles</label>
                                    <select wire:model.debounce.300ms="title.0" class="form-control" >
                                        <option value="">Select Title</option>
                                        <option value="Invoice">Invoice</option>
                                        <option value="Quotation 1">Quotation 1</option>
                                        <option value="Quotation 2">Quotation 2</option>
                                        <option value="Quotation 3">Quotation 3</option>
                                        <option value="Receipt">Receipt</option>
                                    </select>
                                    @error('title.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="file">File</label>
                                    <input type="file" class="form-control" wire:model.debounce.300ms="file.0"  placeholder="Upload File " >
                                    @error('file.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="expires_at">Expiry Date</label>
                                    <input type="date" class="form-control" wire:model.debounce.300ms="expires_at.0" placeholder="dd/mm/yy" />
                                    @error('expires_at.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>


                            <!-- /.col-md-6 -->
                        </div>
                        @foreach ($documentInputs as $key => $value)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Titles</label>
                                    <select wire:model.debounce.300ms="title.{{$value}}" class="form-control">
                                        <option value="">Select Title</option>
                                        <option value="Invoice">Invoice</option>
                                        <option value="Quotation 1">Quotation 1</option>
                                        <option value="Quotation 2">Quotation 2</option>
                                        <option value="Quotation 3">Quotation 3</option>
                                        <option value="Receipt">Receipt</option>
                                    </select>
                                    @error('title.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="file">File</label>
                                    <input type="file" class="form-control" wire:model.debounce.300ms="file.{{$value}}"  placeholder="Upload File ">
                                    @error('file.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="file">Expiry Date</label>
                                    <input type="date" class="form-control" wire:model.debounce.300ms="expires_at.{{$value}}"  placeholder="dd/mm/yy"/>
                                    @error('expires_at.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for=""></label>
                                    <button class="btn btn-danger btn-rounded xs" style="margin-top:23px"  wire:click.prevent="documentsRemove({{$key}})"> <i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <!-- /.col-md-6 -->
                        </div>
                        @endforeach
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="documentsAdd({{$m}})"> <i class="fa fa-plus"></i> File</button>
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

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="purchaseEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-edit"></i> Edit Purchase Order <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="update()" >
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Purchase Order Number<span class="required" style="color: red">*</span></label>
                                    <input type="text" class="form-control" wire:model.debounce.300ms="purchase_number" placeholder="Enter Name" required disabled>
                                    @error('purchase_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Date<span class="required" style="color: red">*</span></label>
                                    <input type="date" class="form-control" wire:model.debounce.300ms="date" placeholder="Enter Purchase Order Date" required >
                                    @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                          
                           
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Product">Accounts<span class="required" style="color: red">*</span></label>
                                    <select wire:model.debounce.300ms="selectedAccount" class="form-control" required>
                                        <option value="">Select Accounts</option>
                                        @foreach ($accounts as $account)
                                          <option value="{{$account->id}}">{{$account->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedAccount') <span class="text-danger error">{{ $message }}</span>@enderror
                                    <small>  <a href="{{ route('accounts.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Expense Category</a></small> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Product">Expenses<span class="required" style="color: red">*</span></label>
                                    <select wire:model.debounce.300ms="expense_id" class="form-control" required>
                                        <option value="">Select Expense</option>
                                            @if (!is_null($selectedAccount))
                                                @foreach ($expenses as $expense)
                                                    <option value="{{$expense->id}}">{{$expense->name}}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                    @error('expense_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                    <small>  <a href="{{ route('expenses.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Expense</a></small> 
                                </div>
                               
                            </div>
                           </div>
                      
                       <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Product">Vendor Types<span class="required" style="color: red">*</span></label>
                                <select wire:model.debounce.300ms="selectedVendorType" class="form-control">
                                    <option value="">Select Type</option>
                                    @foreach ($vendor_types as $vendor_type)
                                      <option value="{{$vendor_type->id}}">{{$vendor_type->name}}</option>
                                    @endforeach
                                </select>
                                @error('selectedVendorType') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Product">Vendors<span class="required" style="color: red">*</span></label>
                                <select wire:model.debounce.300ms="vendor_id" class="form-control" required>
                                    <option value="">Select Vendor</option>
                                    @if (!is_null($selectedVendorType))
                                            @foreach ($vendors as $vendor)
                                                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                            @endforeach
                                        @endif
                                </select>
                                @error('vendor_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                       </div>
                       <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Currencies<span class="required" style="color: red">*</span></label>
                                <select wire:model.debounce.300ms="currency_id" class="form-control" required>
                                    <option value="">Select Currency</option>
                                    @foreach ($currencies as $currency)
                                    <option value="{{$currency->id}}">{{$currency->name}}</option>
                                    @endforeach
                                </select>
                                @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Description</label>
                               <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="3" ></textarea>
                                @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
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

