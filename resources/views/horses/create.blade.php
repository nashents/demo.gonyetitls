@extends('layouts.app')
@section('content')

@section('extra-css')
    @if (Auth::user()->employee)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->employee->company->logo)!!}">
    @elseif (Auth::user()->company)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->company->logo)!!}">
    @elseif (Auth::user()->transporter)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->transporter->company->logo)!!}">
    @endif
@endsection
@section('title')
    Horse | @if (Auth::user()->employee)
    {{Auth::user()->employee->company->name}}
    @elseif (Auth::user()->company)
    {{Auth::user()->company->name}}
    @elseif (Auth::user()->transporter)
    {{Auth::user()->transporter->company->name}}
    @endif
@endsection

@section('body-id')
<body class="top-navbar-fixed">
@endsection

            <!-- ========== TOP NAVBAR ========== -->
            @if (!Auth::user()->employee && Auth::user()->transporter)
            @include('includes.third_party_navbar')
            @else   
            @include('includes.navbar')
            @endif
          

            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                    @if (!Auth::user()->employee && Auth::user()->transporter)
                    @include('includes.third_party_sidebar')
                    @else   
                    @include('includes.sidebar')
                    @endif
                    
                    
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                              @include('includes.top-message')
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
            							<li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> Home</a></li>
            							<li><a href="{{route('horses.index')}}"><i class="fa fa-list"></i> Horses</a> </li>
            							<li class="active"> <i class="fa fa-plus"></i> Create</li>
            						</ul>
                                </div>
                                <!-- /.col-md-6 -->

                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->

                        @livewire('horses.create')
                        <!-- /.section -->

                    </div>
                    <!-- /.main-page -->

                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->


        <!-- ========== PAGE JS FILES ========== -->


@endsection

@section('extra-js')
    <script>
    $(document).ready( function () {
        $('#horsesTable').DataTable();
    } );
    </script>
@endsection
