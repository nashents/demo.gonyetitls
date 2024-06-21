
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
                                <a href="{{route('quotations.create')}}"  class="btn btn-default"><i class="fa fa-plus-square-o"></i>Quotation</a>
                            </div>
                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                            <table id="quotationsTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>

                                    <th class="th-sm">Quotation#
                                    </th>
                                    <th class="th-sm">Customer
                                    </th>
                                    <th class="th-sm">Date
                                    </th>
                                    <th class="th-sm">Expiry
                                    </th>
                                    <th class="th-sm">Currency
                                    </th>
                                    <th class="th-sm">Vat
                                    </th>
                                    <th class="th-sm">Subtotal
                                    </th>
                                    <th class="th-sm">Total
                                    </th>
                                    <th class="th-sm">Action
                                    </th>
                                  </tr>
                                </thead>
                                @if ($quotations->count()>0)
                                <tbody>
                                    @foreach ($quotations as $quotation)
                                  <tr>
                                    @php
                                        $expiry = $quotation->expiry;
                                        $now = new DateTime();
                                        $datetime2 = new DateTime($expiry);
                                    @endphp
                                    <td>{{$quotation->quotation_number}}</td>
                                    <td>{{$quotation->customer ? $quotation->customer->name : ""}}</td>
                                    <td>
                                        @if ($quotation->date)
                                        {{\Carbon\Carbon::parse($quotation->date)->format('j F Y')}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($quotation->expiry)
                                            <span class="label label-{{$now < $datetime2 ? 'success' : 'danger' }}">{{\Carbon\Carbon::parse($quotation->expiry)->format('j F Y')}}</span>
                                        @endif 
                                    </td>
                                    <td>{{$quotation->currency ? $quotation->currency->name : ""}}</td>  
                                    <td>
                                     @if ($quotation->vat != "")
                                        {{$quotation->vat}}%
                                    @endif
                                      </td>
                                    <td>
                                        @if ($quotation->subtotal)
                                        {{$quotation->currency ? $quotation->currency->symbol : ""}}{{number_format($quotation->subtotal,2)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($quotation->total)
                                        {{$quotation->currency ? $quotation->currency->symbol : ""}}{{number_format($quotation->total,2)}}
                                        @endif
                                    </td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('quotations.show',$quotation->id)}}"  ><i class="fa fa-eye color-default"></i>View</a></li>
                                                <li><a href="{{route('quotations.preview',$quotation->id)}}"  ><i class="fa fa-file-invoice color-primary"></i> Preview</a></li>
                                                @if ($quotation->trips->count()>0)

                                                @else   
                                                <li><a href="{{route('quotations.edit',$quotation->id)}}" ><i class="fa fa-edit color-success"></i> Edit</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#quotationDeleteModal{{ $quotation->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                        @include('quotations.delete')
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

