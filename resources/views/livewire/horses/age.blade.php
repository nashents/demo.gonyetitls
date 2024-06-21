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
                                <a href="#" wire:click="exportHorsesAgeExcel()"  class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-download"></i>Excel</a>
                                <a href="#" wire:click="exportHorsesAgeCSV()" class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-download"></i>CSV</a>
                                <a href="#" wire:click="exportHorsesAgePDF()" class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-download"></i>PDF</a>
                            </div>
                        </div>

                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                            <table id="horsesTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Transporter
                                    </th>
                                    <th class="th-sm">Horse
                                    </th>
                                    <th class="th-sm">Year of Manufacture
                                    </th>
                                    <th class="th-sm">Age
                                    </th>
                                    <th class="th-sm">Purchased
                                    </th>
                                    <th class="th-sm">Years Owned
                                    </th>
                                    <th class="th-sm">Disposed
                                    </th>
                                    <th class="th-sm">Total Years Used
                                    </th>
                                    <th class="th-sm">Action
                                    </th>
                                  </tr>
                                </thead>
                                @if ($horses->count()>0)
                                <tbody>
                                    @foreach ($horses as $horse)
                                  <tr>
                                    <td>{{$horse->transporter ? $horse->transporter->name : ""}}</td>
                                    <td>{{$horse->horse_make ? $horse->horse_make->name : ""}} {{$horse->horse_model ? $horse->horse_model->name : ""}} {{$horse->registration_number}} {{$horse->fleet_number ? "(".$horse->fleet_number.")" : ""}}</td>
                                    <td>{{$horse->year}}</td>
                                    @php
                                  if (isset($horse->year)) {
                                        $current_year = date('Y');
                                        $age = $current_year-$horse->year;
                                    }else {
                                        $age = "";
                                    }
                                
                                    $pattern = '/^\d{4}-\d{2}-\d{2}$/';
                                    $today = Carbon\Carbon::today();
                                    if ((preg_match($pattern, $horse->start_date)) ){
                                        $start_date = Carbon\Carbon::parse($horse->start_date);
                                        $yearsDifference = $start_date->diffInYears($today);
                                    }else {
                                        $yearsDifference = "";
                                    }
                                    if ((preg_match($pattern, $horse->end_date)) ){
                                        $end_date = Carbon\Carbon::parse($horse->end_date);
                                        $yearsOfHorseDifference = $start_date->diffInYears($end_date);
                                    }else {
                                        $yearsOfHorseDifference = "";
                                    }

                                    @endphp
                                    <td>{{$age ? $age." Year(s)" : ""}}</td>
                                    <td>{{$horse->start_date}}</td>
                                    <td>{{ $yearsDifference ? $yearsDifference."Year(s)" : ""}}</td>
                                    <td>{{$horse->end_date}}</td>
                                    <td>{{$yearsOfHorseDifference ? $yearsOfHorseDifference." Year(s)" : ""}}</td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                {{-- <li><a href="#"  wire:click="edit({{$horse->id}})" ><i class="fa fa-refresh color-success"></i> Update</a></li> --}}
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

                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
    </section>

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="horse_ageModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-plus"></i> Update {{$horse->registration_number ? $horse->registration_number : ""}} Age <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="update()" >
                <div class="modal-body">
                    <div class="form-group">
                        <label for="country">Year<span class="required" style="color: red">*</span></label>
                      <input type="date" wire:model.debounce.300ms="year" class="form-control" required>
                        @error('year') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
   



</div>

