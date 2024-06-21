@extends('layouts.app')
@section('content')

@section('extra-css')
    @if (isset(Auth::user()->employee->company))
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->employee->company->logo)!!}">
    @elseif (Auth::user()->company)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->company->logo)!!}">
    @endif
@endsection
@section('title')
    Account|@if (isset(Auth::user()->employee->company))
    {{Auth::user()->employee->company->name}}
    @elseif (Auth::user()->company)
    {{Auth::user()->company->name}}
    @endif
@endsection
@section('body-id')
<body class="top-navbar-fixed">
@endsection

            <!-- ========== TOP NAVBAR ========== -->
           @include('includes.navbar')

            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                  @include('includes.sidebar')
                    <!-- /.left-sidebar -->
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
            							<li><a href="{{route('accounts.index')}}"><i class="fa fa-list"></i> Accounts</a></li>
            							<li class="active"> <i class="fas fa-eye"></i> Account</li>
            						</ul>
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>

                        @livewire('accounts.show',['id' => $account->id])


                    </div>


                </div>
                <!-- /.content-container -->
            </div>



@endsection

@section('extra-js')
    <script>
    $(document).ready( function () {
        $('#accountsTable').DataTable();
    } );
    </script>
@endsection

