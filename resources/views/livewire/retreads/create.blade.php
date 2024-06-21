<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Add Retread</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="store()" >

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Tyre Retread Number<span class="required" style="color: red">*</span></label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="retread_number" placeholder="Enter Tyre Number" required>
                                        @error('retread_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="country">Vendors<span class="required" style="color: red">*</span></label>
                                           <select wire:model.debounce.300ms="vendor_id" class="form-control" required>
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
                                            <label for="name">Currencies<span class="required" style="color: red">*</span></label>
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


                            <h5 class="underline mt-30">Tyre Details</h5>
                            <small style="color: red">205 / 65 R 15 </small>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Tyres<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="tyre_dispatch_id.0" class="form-control" required>
                                           <option value="">Select Tyre</option>
                                         @foreach ($tyre_dispatches as $tyre_dispatch)
                                            <option value="{{$tyre_dispatch->id}}">{{$tyre_dispatch->tyre_detail->tyre_number}} {{$tyre_dispatch->tyre_detail->product->name}} [{{$tyre_dispatch->tyre_detail->width}} / {{$tyre_dispatch->tyre_detail->aspect_ratio}} R {{$tyre_dispatch->tyre_detail->diameter}}]</option>
                                         @endforeach
                                       </select>
                                        @error('tyre_dispatch_id.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Horses</label>
                                        <select class="form-control" wire:model.debounce.300ms="horse_id.0">
                                            <option value="">Select Horse</option>
                                            @foreach ($horses as $horse)
                                                 <option value="{{$horse->id}}">{{$horse->registration_number}} {{$horse->horse_make ? $horse->horse_make->name : "undefined make"}} {{$horse->horse_model ? $horse->horse_model->name : "& model"}}</option>
                                            @endforeach

                                        </select>
                                        @error('horse_id.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Vehicles</label>
                                        <select class="form-control" wire:model.debounce.300ms="vehicle_id.0">
                                            <option value="">Select vehicle</option>
                                            @foreach ($vehicles as $vehicle)
                                                 <option value="{{$vehicle->id}}">{{$vehicle->registration_number}} {{$vehicle->vehicle_make ? $vehicle->vehicle_make->name : "undefined make"}} {{$vehicle->vehicle_model ? $vehicle->vehicle_model->name : "& model"}}</option>
                                            @endforeach

                                        </select>
                                        @error('vehicle_id.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Trailers</label>
                                        <select class="form-control" wire:model.debounce.300ms="trailer_id.0">
                                            <option value="">Select Trailer</option>
                                            @foreach ($trailers as $trailer)
                                                 <option value="{{$trailer->id}}">{{$trailer->registration_number}} {{$trailer->make}} {{$trailer->model}} </option>
                                            @endforeach

                                        </select>
                                        @error('trailer_id.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="width">Rate</label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="rate.0"  placeholder="Enter Tyre Width " required>
                                        @error('rate.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <!-- /.col-md-6 -->
                            </div>

                            @foreach ($inputs as $key => $value)
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="country">Tyres<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="tyre_dispatch_id.{{$value}}" class="form-control" required>
                                           <option value="">Select Tyre</option>
                                         @foreach ($tyre_dispatches as $tyre_dispatch)
                                            <option value="{{$tyre_dispatch->id}}">{{$tyre_dispatch->tyre_detail->tyre_number}} {{$tyre_dispatch->tyre_detail->product->name}} [{{$tyre_dispatch->tyre_detail->width}} / {{$tyre_dispatch->tyre_detail->aspect_ratio}} R {{$tyre_dispatch->tyre_detail->diameter}}]</option>
                                         @endforeach
                                       </select>
                                        @error('tyre_dispatch_id.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Horses</label>
                                        <select class="form-control" wire:model.debounce.300ms="horse_id.{{$value}}">
                                            <option value="">Select Horse</option>
                                            @foreach ($horses as $horse)
                                                 <option value="{{$horse->id}}">{{$horse->registration_number}} {{$horse->horse_make ? $horse->horse_make->name : "undefined make"}} {{$horse->horse_model ? $horse->horse_model->name : "& model"}}</option>
                                            @endforeach

                                        </select>
                                        @error('horse_id.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Vehicles</label>
                                        <select class="form-control" wire:model.debounce.300ms="vehicle_id.{{$value}}">
                                            <option value="">Select vehicle</option>
                                            @foreach ($vehicles as $vehicle)
                                                 <option value="{{$vehicle->id}}">{{$vehicle->registration_number}} {{$vehicle->vehicle_make ? $vehicle->vehicle_make->name : "undefined make"}} {{$vehicle->vehicle_model ? $vehicle->vehicle_model->name : "& model"}}</option>
                                            @endforeach

                                        </select>
                                        @error('vehicle_id.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="name">Trailers</label>
                                        <select class="form-control" wire:model.debounce.300ms="trailer_id.{{$value}}">
                                            <option value="">Select Trailer</option>
                                            @foreach ($trailers as $trailer)
                                                 <option value="{{$trailer->id}}">{{$trailer->registration_number}} {{$trailer->make}} {{$trailer->model}} </option>
                                            @endforeach

                                        </select>
                                        @error('trailer_id.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="width">Rate</label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="rate.{{$value}}"  placeholder="Enter Amount " required>
                                        @error('rate.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button class="btn btn-danger btn-rounded xs" style="margin-top:23px"  wire:click.prevent="remove({{$key}})"> <i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <!-- /.col-md-6 -->
                            </div>
                            @endforeach
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i> Retread</button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Date</label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="date" placeholder="Date"/>
                                        @error('date') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Collection Date</label>
                                        <input type="date" class="form-control" wire:model.debounce.300ms="date" placeholder="Collection Date" />
                                        @error('collection_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Damage Description</label>
                                      <textarea wire:model.debounce.300ms="description" class="form-control" cols="30" rows="5"></textarea>
                                        @error('description') <span class="text-danger error">{{ $message }}</span>@enderror
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
