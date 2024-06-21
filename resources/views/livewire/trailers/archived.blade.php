<div>
    <style>
        .modal-lg {
        max-width: 80%;
    }
    </style>
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

                                <table id="trailersTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                    <thead >
                                        <tr>
                                        <th class="th-sm">Trailer#
                                        </th>
                                        <th class="th-sm">Fleet#
                                        </th>
                                        <th class="th-sm">Transporter
                                        </th>
                                        <th class="th-sm">Make
                                        </th>
                                        <th class="th-sm">Model
                                        </th>
                                        <th class="th-sm">TRN
                                        </th>
                                        <th class="th-sm">Year
                                        </th>
                                        <th class="th-sm">Availability
                                        </th>
                                        <th class="th-sm">Service
                                        </th>
                                        <th class="th-sm">Actions
                                        </th>
                                      </tr>
                                    </thead>
                                    @if ($trailers->count()>0)
                                    <tbody>
                                        @foreach ($trailers as $trailer)
                                      <tr>
                                        <td>{{$trailer->trailer_number}}</td>
                                        <td>{{$trailer->fleet_number}}</td>
                                        <td>{{$trailer->transporter ? $trailer->transporter->name : ""}}</td>
                                        <td>{{$trailer->make}}</td>
                                        <td>{{$trailer->model}}</td>
                                        <td>{{$trailer->registration_number}}</td>
                                        <td>{{$trailer->year}}</td>
                                        <td><span class="badge bg-{{$trailer->status == 1 ? "success" : "danger"}}">{{$trailer->status == 1 ? "Available" : "Unavailable"}}</span></td>
                                        <td><span class="badge bg-{{$trailer->service == 0 ? "success" : "danger"}}">{{$trailer->service == 0 ? "Fit for use" : "In Service"}}</span></td>
                                        <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#" wire:click="restore({{$trailer->id}})"><i class="fas fa-undo color-default"></i> Restore</a></li>
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

    </div>
