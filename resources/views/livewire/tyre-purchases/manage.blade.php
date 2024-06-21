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
                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">
                            <table id="purchasesTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Purchase#
                                    </th>
                                    <th class="th-sm">Date
                                    </th>
                                    <th class="th-sm">Category
                                    </th>
                                    <th class="th-sm">Vendor
                                    </th>
                                    <th class="th-sm">Currency
                                    </th>
                                    <th class="th-sm">Value
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
                                    <td>{{ucfirst($purchase->category ? $purchase->category->name : "")}}</td>
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


    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="purchaseEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-hand-holding-usd"></i> Edit Purchase Order <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
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
                                        @if (!is_null($selectedVendorType))
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{$vendor->id}}">{{$vendor->name}}</option>
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
                                <label for="name">Description<span class="required" style="color: red">*</span></label>
                               <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="3" required></textarea>
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

