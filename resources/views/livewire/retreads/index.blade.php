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
                                <a href="{{route('retreads.create')}}"  class="btn btn-default"><i class="fa fa-plus-square-o"></i>Retread</a>
                            </div>
                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                            <table id="retread_tyresTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Retread#
                                    </th>
                                    <th class="th-sm">Tyre#
                                    </th>
                                    <th class="th-sm">Name
                                    </th>
                                    <th class="th-sm">Specifications
                                    </th>
                                    <th class="th-sm">Rate
                                    </th>
                                    <th class="th-sm">Action
                                    </th>
                                  </tr>
                                </thead>
                                @if ($retread_tyres->count()>0)
                                <tbody>
                                    @foreach ($retread_tyres as $retread_tyre)
                                  <tr>
                                    <td>{{$retread_tyre->retread->retread_number}}</td>
                                    <td>{{$retread_tyre->tyre_number}}</td>
                                    <td>{{$retread_tyre->name}}</td>
                                    <td>{{$retread_tyre->width}} / {{$retread_tyre->aspect_ratio}} R {{$retread_tyre->diameter}}</td>
                                    <td>${{number_format($retread_tyre->rate,2)}}</td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-toggle="modal" data-target="#retread_tyresDeleteModal{{ $retread_tyre->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                        @include('retread_tyres.delete')
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

