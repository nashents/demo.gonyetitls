<div>
    {{-- <blockquote class="blockquote-reverse mt-20"> --}}
        <x-loading/>
        @if ($invoice->reason == "Trip")
        @if ($invoice->payments->count()>0)
        @else    
        <a href="" data-toggle="modal" data-target="#addInvoiceItemModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Item</a>
        @endif
        
        <br>
        <br>
        <table id="invoice_itemsTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th class="th-sm">Invoice#
                </th>
                <th class="th-sm">Trip#
                </th>
                <th class="th-sm">Description
                </th>
                <th class="th-sm">Quantity
                </th>
                <th class="th-sm">Currency
                </th>
                <th class="th-sm">Rate
                </th>
                <th class="th-sm">Subtotal (Excl)
                </th>
                <th class="th-sm">Tax Amt
                </th>
                <th class="th-sm">Subtotal (Incl)
                </th>
                <th class="th-sm">Action
                </th>
              </tr>
            </thead>
            @if ($invoice_items->count()>0)
            <tbody>
                @foreach ($invoice_items as $invoice_item)
              <tr>
                <td>{{$invoice_item->invoice->invoice_number}}</td>
                <td>{{$invoice_item->trip ? $invoice_item->trip->trip_number : ""}}</td>
                <td>{{$invoice_item->trip_details}}</td>
                <td>{{$invoice_item->qty}}</td>
                <td>{{$invoice_item->invoice->currency->name}}</td>
                <td>
                    @if ($invoice_item->amount)
                    {{$invoice_item->invoice->currency->symbol}}{{number_format($invoice_item->amount,2)}}        
                    @endif
                </td>
                <td>
                    @if ($invoice_item->subtotal)
                    {{$invoice_item->invoice->currency->symbol}}{{number_format($invoice_item->subtotal,2)}}
                    @endif
                </td>
                <td>
                    @if ($invoice_item->tax_amount)
                    {{$invoice_item->invoice->currency->symbol}}{{number_format($invoice_item->tax_amount,2)}}
                    @endif
                </td>
                <td>
                    @if ($invoice_item->subtotal_incl)
                    {{$invoice_item->invoice->currency->symbol}}{{number_format($invoice_item->subtotal_incl,2)}}
                    @endif
                </td>
                <td class="w-10 line-height-35 table-dropdown">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars"></i>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            @if ($invoice->payments->count()>0)
                            @else    
                            <li><a href="#" wire:click="edit({{$invoice_item->id }})"><i class="fa fa-edit color-success"></i>Edit</a></li>
                            <li><a href="#" wire:click="removeShow({{ $invoice_item->id }})"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                            @endif
                        </ul>
                    </div>
                  
            </td>
              </tr>
              @endforeach
            </tbody>
            @else
                <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
             @endif
          </table>
        @else  
        @if ($invoice->payments->count()>0)
        @else    
        <a href="" data-toggle="modal" data-target="#addInvoiceItemModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Item</a>
        @endif
        <br>
        <br>
        <table id="invoice_itemsTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th class="th-sm">Invoice#
                </th>
                <th class="th-sm">Item
                </th>
                <th class="th-sm">Quantity
                </th>
                <th class="th-sm">Currency
                </th>
                <th class="th-sm">Rate
                </th>
                <th class="th-sm">Subtotal(Excl)
                </th>
                <th class="th-sm">Tax Amt
                </th>
                <th class="th-sm">Subtotal(Incl)
                </th>
                <th class="th-sm">Action
                </th>
              </tr>
            </thead>
            @if ($invoice_items->count()>0)
            <tbody>
                @foreach ($invoice_items as $invoice_item)
              <tr>
                <td>{{$invoice_item->invoice->invoice_number}}</td>
                <td>
                  <strong>{{ucfirst($invoice_item->product ? $invoice_item->product->name : "")}}</strong>  
                  <br>
                  {{ucfirst($invoice_item->product ? $invoice_item->product->description : "")}}
                </td>
                <td>{{$invoice_item->qty}}</td>
                <td>{{$invoice_item->invoice->currency->name}}</td>
                <td>
                    @if ($invoice_item->amount)
                    {{$invoice_item->invoice->currency->symbol}}{{number_format($invoice_item->amount,2)}}        
                    @endif
                </td>
                <td>
                    @if ($invoice_item->subtotal)
                    {{$invoice_item->invoice->currency->symbol}}{{number_format($invoice_item->subtotal,2)}}
                    @endif
                </td>
                <td>
                    @if ($invoice_item->tax_amount)
                    {{$invoice_item->invoice->currency->symbol}}{{number_format($invoice_item->tax_amount,2)}}
                    @endif
                </td>
                <td>
                    @if ($invoice_item->subtotal_incl)
                    {{$invoice_item->invoice->currency->symbol}}{{number_format($invoice_item->subtotal_incl,2)}}
                    @endif
                </td>
                
                <td class="w-10 line-height-35 table-dropdown">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars"></i>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            @if ($invoice->payments->count()>0)
                            @else    
                                {{-- <li><a href="#" wire:click="edit({{$invoice_item->id }})"><i class="fa fa-edit color-success"></i>Edit</a></li> --}}
                                <li><a href="#" wire:click="removeShow({{ $invoice_item->id }})"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                            @endif
                        </ul>
                    </div>
                  
            </td>
              </tr>
              @endforeach
            </tbody>
            @else
                <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
             @endif
          </table>
        @endif
       
       
          <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal fade" id="removeModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-danger">
                    <div class="modal-body">
                       <center> <strong>Are you sure you want to delete this Invoice Item</strong> </center>
                    </div>
                    <form wire:submit.prevent="removeInvoiceItem()" >
                    <div class="modal-footer no-border">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn bg-white btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                            <button type="submit" class="btn bg-black btn-wide btn-rounded" ><i class="fa fa-trash"></i>Delete</button>
                        </div>
                        <!-- /.btn-group -->
                    </div>
                </form>
                </div>
            </div>
        </div>

        @if ($invoice->reason == "Trip")

        <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="addInvoiceItemModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
            <div class="modal-dialog" role="cargo">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Add Invoice Item(s) <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <form wire:submit.prevent="store()" >
                    <div class="modal-body">
                        @php
                        $invoice_items = App\Models\InvoiceItem::all();
                        foreach($invoice_items as $invoice_item){
                                $trip_ids[] = $invoice_item->trip_id;
                        }   
                        @endphp
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="subheading">Trips<span class="required" style="color: red">*</span></label>
                            <select wire:model.debounce.300ms="selectedTrip.0"  class="form-control" required size="4">
                                <option value="">Select Trip</option>
                                  
                                    @foreach ($trips as $trip)
                                        @if ($trip->currency_id == $invoice->currency_id)
                                            @if (isset($trip_ids))
                                                @if (in_array($trip->id,$trip_ids))
                                                <option value="{{$trip->id}}" style="color: orange">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }}{{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} |  {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} </option> 
                                                @else
                                                    <option value="{{$trip->id}}">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }} {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} | {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}}</option>
                                                @endif
                                            @else
                                            <option value="{{$trip->id}}">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }} {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} |  {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} | {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}}</option>
                                            @endif
                                        @endif
                                    @endforeach  
                                   
                                </select>
                                <small style="color: green">NB: All invoiced trips will appear in orange</small>
                                <br>
                                <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                            @error('selectedTrip.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Qty<span class="required" style="color: red">*</span></label>
                            <input type="number"  class="form-control" wire:model.debounce.300ms="qty.0"  placeholder="Enter Qty" required>
                            @error('qty.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subheading">Amount<span class="required" style="color: red">*</span></label>
                            <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount.0"  placeholder="Enter Amount" required/>
                            @error('amount.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subheading">Taxes</label>
                            <select wire:model.debounce.300ms="selectedTax.0"  class="form-control">
                                <option value="">Select Tax</option>
                                    @foreach ($tax_accounts as $tax)
                                       <option value="{{$tax->id}}">{{$tax->abbreviation}} {{$tax->rate ? $tax->rate."%" : ""}}</option> 
                                    @endforeach
                                </select>
                                <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                            @error('selectedTax.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                @foreach ($inputs as $key => $value)
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="subheading">Trips<span class="required" style="color: red">*</span></label>
                            <select wire:model.debounce.300ms="selectedTrip.{{$value}}"  class="form-control" required size="4">
                                <option value="">Select Trip</option>
                                  
                                    @foreach ($trips as $trip)
                                        @if ($trip->currency_id == $invoice->currency_id)
                                            @if (isset($trip_ids))
                                                @if (in_array($trip->id,$trip_ids))
                                                <option value="{{$trip->id}}" style="color: orange">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }}{{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} |  {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} </option> 
                                                @else
                                                    <option value="{{$trip->id}}">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }} {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} | {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}}</option>
                                                @endif
                                            @else
                                            <option value="{{$trip->id}}">{{$trip->trip_number ? $trip->trip_number."|" : ""}} {{ $trip->trip_ref ? "| ".$trip->trip_ref." |" : "" }} {{ isset($pod) ? "| ".$pod->document_number." | " : "" }} {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}} |  {{$trip->horse ? $trip->horse->registration_number : ""}} | {{$trip->customer ? $trip->customer->name : ""}} | {{$trip->currency ? $trip->currency->name : ""}} {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover ? $trip->turnover : 0,2)}}</option>
                                            @endif
                                        @endif
                                    @endforeach  
                                   
                                </select>
                                <small style="color: green">NB: All invoiced trips will appear in orange</small>
                                <br>
                                <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                            @error('selectedTrip.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Qty<span class="required" style="color: red">*</span></label>
                            <input type="number"  class="form-control" wire:model.debounce.300ms="qty.{{$value}}"  placeholder="Enter Qty" required>
                            @error('qty.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="subheading">Amount<span class="required" style="color: red">*</span></label>
                            <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount.{{$value}}"  placeholder="Enter Amount" required/>
                            @error('amount.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="subheading">Taxes</label>
                            <select wire:model.debounce.300ms="selectedTax.{{$value}}"  class="form-control">
                                <option value="">Select Tax</option>
                                    @foreach ($tax_accounts as $tax)
                                       <option value="{{$tax->id}}">{{$tax->abbreviation}} {{$tax->rate ? $tax->rate."%" : ""}}</option> 
                                    @endforeach
                                </select>
                                <small><a href="{{ route('taxes.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Tax</a></small> 
                            @error('selectedTax.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
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
                            <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i>Trip</button>
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
        <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="editInvoiceItemModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
            <div class="modal-dialog" role="cargo">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal4Label"><i class="fa fa-edit"></i> Edit Invoice Item<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <form wire:submit.prevent="update()" >
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Trip(s)<span class="required" style="color: red">*</span></label>
                            <select wire:model.debounce.300ms="selectedTrip" class="form-control"  required disabled>
                                <option value="">Select Trip</option>
                                 @foreach ($trips as $trip)
                                     <option value="{{$trip->id}}">Trip#: {{$trip->trip_number}}, Customer: {{$trip->customer ? $trip->customer->name : ""}}, [From: {{$trip->loading_point ? $trip->loading_point->name : "undefined"}} To: {{$trip->offloading_point ? $trip->offloading_point->name : ""}} ]</option>
                                 @endforeach
                            </select>
                            @error('selectedTrip') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Details</label>
                            <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="5"></textarea>
                            @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
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

        @else  

        <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="addInvoiceItemModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
            <div class="modal-dialog" role="cargo">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Add Item(s) <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <form wire:submit.prevent="save()" >
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                        <select wire:model.debounce.300ms="selectedproduct.0" class="form-control" required>
                                        <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option> 
                                            @endforeach
                                        </select>
                                    @error('selectedproduct.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Qty<span class="required" style="color: red">*</span></label>
                                    <input type="number" class="form-control" wire:model.debounce.300ms="qty.0" placeholder="Enter Qty" required >
                                    @error('qty.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Amount<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount.0" placeholder="Enter Amount" required >
                                    @error('amount.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Taxes<span class="required" style="color: red">*</span></label>
                                        <select wire:model.debounce.300ms="selectedTax.0" class="form-control" required>
                                        <option value="">Select Tax</option>
                                            @foreach ($tax_accounts as $tax)
                                            <option value="{{$tax->id}}">{{$tax->abbreviation}} {{$tax->rate ? $tax->rate."%" : ""}}</option> 
                                            @endforeach
                                        </select>
                                    @error('selectedTax.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                           
                        @foreach ($inputs as $key => $value)
                            <div class="row">  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Product(s)<span class="required" style="color: red">*</span></label>
                                            <select wire:model.debounce.300ms="selectedproduct.{{ $value }}" class="form-control" required>
                                            <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                <option value="{{$product->id}}">{{$product->name}}</option> 
                                                @endforeach
                                            </select>
                                        @error('selectedproduct.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Qty<span class="required" style="color: red">*</span></label>
                                            <input type="number" class="form-control" wire:model.debounce.300ms="qty.{{ $value }}" placeholder="Enter Qty" required >
                                            @error('qty.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="name">Amount<span class="required" style="color: red">*</span></label>
                                            <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount.{{ $value }}" placeholder="Enter Amount" required >
                                            @error('amount.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="country">Taxes<span class="required" style="color: red">*</span></label>
                                                <select wire:model.debounce.300ms="selectedTax.{{ $value }}" class="form-control" required>
                                                <option value="">Select Tax</option>
                                                    @foreach ($tax_accounts as $tax)
                                                    <option value="{{$tax->id}}">{{$tax->abbreviation}} {{$tax->rate ? $tax->rate."%" : ""}}</option> 
                                                    @endforeach
                                                </select>
                                            @error('selectedTax.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
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
                                    <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i>Item</button>
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
        @endif

        
   
</div>
