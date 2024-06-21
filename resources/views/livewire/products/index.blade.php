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
                                <a href="{{route('products.create')}}" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Product</a>
                            </div>

                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                            <table id="productsTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Product#
                                    </th>
                                    <th class="th-sm">Category
                                    </th>
                                    <th class="th-sm">Sub Category
                                    </th>
                                    <th class="th-sm">Brand
                                    </th>
                                    <th class="th-sm">Name
                                    </th>
                                    <th class="th-sm">Model
                                    </th>
                                    <th class="th-sm">Status
                                    </th>
                                    <th class="th-sm">Action
                                    </th>
                                  </tr>
                                </thead>
                                @if ($products->count()>0)
                                <tbody>
                                    @foreach ($products as $product)
                                  <tr>
                                    <td>{{$product->product_number}}</td>
                                    <td>{{$product->category ? $product->category->name : "undefined category"}}</td>
                                    <td>{{$product->category_value ? $product->category_value->name : "undefined sub cat"}}</td>
                                    <td>{{$product->brand ? $product->brand->name : "undefined"}}</td>
                                    <td>{{$product->name}}</td>
                                    <td>{{$product->model}}</td>
                                    <td><span class="badge bg-{{$product->status == 1 ? "success" : "danger"}}">{{$product->status == 1 ? "Active" : "Inactive"}}</span></td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('products.show',$product->id )}}"  ><i class="fa fa-eye color-default"></i> View</a></li>
                                                <li><a href="{{route('products.edit',$product->id )}}"  ><i class="fa fa-edit color-success"></i> Edit</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#productDeleteModal{{ $product->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                        @include('products.delete')
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

