<div>
    <x-loading/>
    <a href="" data-toggle="modal" data-target="#fitnessModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Reminder</a>
    <br>
    <br>
    <table id="fitnessesTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
        <thead >
            <th class="th-sm">Name
            </th>
            <th class="th-sm">Issued @
            </th>
            <th class="th-sm">Expires At
            </th>
            <th class="th-sm">1st Reminder @
            </th>
            <th class="th-sm">2nd Reminder @
            </th>
            <th class="th-sm">3rd Reminder @
            </th>
           
            <th class="th-sm">Status
            </th>
            <th class="th-sm">Action
            </th>
          </tr>
        </thead>
        <tbody>
            @foreach ($fitnesses as $fitness)
          <tr>
            <td>{{$fitness->reminder_item ? $fitness->reminder_item->name : ""}}</td>
            <td>{{$fitness->issued_at}}</td>
            <td>{{$fitness->expires_at}}</td>
            <td>{{$fitness->first_reminder_at}}</td>
            <td>{{$fitness->second_reminder_at}}</td>
            <td>{{$fitness->third_reminder_at}}</td>
            <td>
                @if ($fitness->expires_at >= now()->toDateTimeString())
                <span class="badge bg-success">Active</span>
                @else
                <span class="badge bg-danger">Expired</span>        
                @endif
            </td>
            <td class="w-10 line-height-35 table-dropdown">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars"></i>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="#" wire:click="edit({{$fitness->id}})"><i class="fa fa-edit color-success"></i> Edit</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#fitnessDeleteModal{{$fitness->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                    </ul>
                    @include('fitnesses.delete')
                </div>
        </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="fitnessModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Add Reminder <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="store()" >
                <div class="modal-body">
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Reminder Item(s)<span class="required" style="color: red">*</span></label>
                            <select wire:model.lazy="reminder_item_id.0" class="form-control" required>
                                <option value="">Select Reminder</option>
                               @foreach ($reminder_items as $reminder_item)
                                   <option value="{{ $reminder_item->id }}">{{ $reminder_item->name }}</option>
                               @endforeach
                            </select>
                            <small>  <a href="{{ route('reminder_items.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Reminder Item</a></small> 
                            @error('reminder_item_id.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="number">Issued@<span class="required" style="color: red">*</span></label>
                            <input type="datetime-local" class="form-control"  wire:model.debounce.300ms="issued_at.0" placeholder="Issue Date" required>
                            @error('issued_at.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="number">Expires@<span class="required" style="color: red">*</span></label>
                            <input type="datetime-local" class="form-control"  wire:model.debounce.300ms="expires_at.0" placeholder="Expiry Date" required>
                            @error('expires_at.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                        </div>
                        @foreach ($inputs as $key => $value)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Reminder Item(s)<span class="required" style="color: red">*</span></label>
                                    <select wire:model.lazy="reminder_item_id.{{ $value }}" class="form-control" required>
                                        <option value="">Select Reminder</option>
                                       @foreach ($reminder_items as $reminder_item)
                                           <option value="{{ $reminder_item->id }}">{{ $reminder_item->name }}</option>
                                       @endforeach
                                    </select>
                                    @error('reminder_item_id.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="number">Issued@<span class="required" style="color: red">*</span></label>
                                    <input type="datetime-local" class="form-control"  wire:model.debounce.300ms="issued_at.{{$value}}" placeholder="Issue Date" required>
                                    @error('issued_at.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="number">Expires@<span class="required" style="color: red">*</span></label>
                                    <input type="datetime-local" class="form-control"  wire:model.debounce.300ms="expires_at.{{$value}}" placeholder="Expiry Date" required>
                                    @error('expires_at.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
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
                                    <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i> Reminder</button>
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
      <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="fitnessEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-edit"></i> Edit Reminder <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="update()" >
                <div class="modal-body">
                    <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Reminder Item(s)<span class="required" style="color: red">*</span></label>
                            <select wire:model.lazy="reminder_item_id" class="form-control" required>
                                <option value="">Select Reminder</option>
                               @foreach ($reminder_items as $reminder_item)
                                   <option value="{{ $reminder_item->id }}">{{ $reminder_item->name }}</option>
                               @endforeach
                            </select>
                            @error('reminder_item_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="number">Issued@<span class="required" style="color: red">*</span></label>
                            <input type="datetime-local" class="form-control"  wire:model.debounce.300ms="issued_at" placeholder="Issue Date" required>
                            @error('issued_at') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="number">Expires@<span class="required" style="color: red">*</span></label>
                            <input type="datetime-local" class="form-control"  wire:model.debounce.300ms="expires_at" placeholder="Expiry Date" required>
                            @error('expires_at') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-save"></i>Update</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>

</div>
