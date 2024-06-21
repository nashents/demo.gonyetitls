<nav class="navbar top-navbar bg-white box-shadow">
    <div class="container-fluid">
        <div class="row">
            <div class="navbar-header no-padding">
                <a class="navbar-brand" href="{{route('dashboard.index')}}">
                    @if (isset(Auth::user()->employee))
                    <img src="{{asset('images/uploads/'.Auth::user()->employee->company->logo)}}" alt="{{Auth::user()->employee->company->name}} "  class="logo">
                    @elseif(isset(Auth::user()->company))
                    <img src="{{asset('images/uploads/'.Auth::user()->company->logo)}}" alt="{{Auth::user()->company->name}} " class="logo">
                    @elseif(isset(Auth::user()->transporter))
                    <img src="{{asset('images/uploads/'.Auth::user()->transporter->company->logo)}}" alt="{{Auth::user()->transporter->company->name}} " class="logo">
                    @elseif(isset(Auth::user()->customer))
                    <img src="{{asset('images/uploads/'.Auth::user()->customer->company->logo)}}" alt="{{Auth::user()->customer->company->name}} " class="logo">
                    @elseif(isset(Auth::user()->agent))
                    <img src="{{asset('images/uploads/'.Auth::user()->agent->company->logo)}}" alt="{{Auth::user()->agent->company->name}} " class="logo">
                    @endif
                </a>
                <span class="small-nav-handle hidden-sm hidden-xs"><i class="fa fa-outdent"></i></span>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="fa fa-ellipsis-v"></i>
                </button>
                <button type="button" class="navbar-toggle mobile-nav-toggle" >
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <!-- /.navbar-header -->

            <div class="collapse navbar-collapse" id="navbar-collapse-1">
                <ul class="nav navbar-nav" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                    <li class="hidden-sm hidden-xs"><a href="#" class="user-info-handle"><i class="fa fa-user"></i></a></li>
                    <li class="hidden-sm hidden-xs"><a href="#" class="full-screen-handle"><i class="fa fa-arrows-alt"></i></a></li>
                    <li><a href="#">Version: {{config('app.version')}}</a></li>
                </ul>
                <!-- /.nav navbar-nav -->

                <ul class="nav navbar-nav navbar-right" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle bg-primary tour-one" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-line-chart"></i> Reports <span class="caret"></span></a>
                        <ul class="dropdown-menu" style="overflow-x:auto; width:100%; height:400px;" >
                            <li><a href="{{route('trips.reports')}}"><i class="fa fa-plus-square-o"></i>Trips</a></li>
                            {{-- <li><a href="{{route('reports.index')}}"><i class="fa fa-plus-square-o"></i>Financial Statements</a></li> --}}
                            <li><a href="{{route('debtors.reports')}}"><i class="fa fa-plus-square-o"></i>Debtors</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><strong>Age Pyramids</strong></a></li>
                            <li><a href="{{route('customers.age')}}"><i class="fa fa-plus-square-o"></i>Customers Age</a></li>
                            <li><a href="{{route('drivers.age')}}"><i class="fa fa-plus-square-o"></i>Drivers Age</a></li>
                            <li><a href="{{route('employees.age')}}"><i class="fa fa-plus-square-o"></i>Employees Age</a></li>
                            <li><a href="{{route('horses.age')}}"><i class="fa fa-plus-square-o"></i>Horses Age</a></li>
                            <li><a href="{{route('trailers.age')}}"><i class="fa fa-plus-square-o"></i>Trailers Age</a></li>
                            <li><a href="{{route('vendors.age')}}"><i class="fa fa-plus-square-o"></i>Vendors Age</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><strong>Next Service</strong></a></li>
                            <li><a href="{{route('horses.mileage')}}"><i class="fa fa-plus-square-o"></i>Horses</a></li>
                            <li><a href="{{route('trailers.mileage')}}"><i class="fa fa-plus-square-o"></i>Trailers</a></li>
                            <li><a href="{{route('vehicles.mileage')}}"><i class="fa fa-plus-square-o"></i>Vehicles</a></li>
                           
                          
                            
                           
                        </ul>
                    </li>
                    <li class="dropdown">
                        @php
                             $reminders = App\Models\Fitness::whereDate('first_reminder_at','<=', Carbon\Carbon::today())
                                ->where('first_reminder_at_status', FALSE)
                                ->where('user_id', Auth::user()->id)
                                ->where('expires_at','>=', now()->toDateTimeString())
                                ->where('closed', 0)
                                ->orWhereDate('second_reminder_at','<=', Carbon\Carbon::today())
                                ->where('second_reminder_at_status', FALSE)
                                ->where('user_id', Auth::user()->id)
                                ->where('expires_at','>=', now()->toDateTimeString())
                                ->where('closed', 0)
                                ->orWhereDate('third_reminder_at','<=', Carbon\Carbon::today())
                                ->where('third_reminder_at_status', FALSE)
                                ->where('user_id', Auth::user()->id)
                                ->where('closed', 0)
                                ->where('expires_at','>=', now()->toDateTimeString())
                                ->get();
                             $reminders_count = App\Models\Fitness::whereDate('first_reminder_at','<=', Carbon\Carbon::today())
                                ->where('first_reminder_at_status', FALSE)
                                ->where('user_id', Auth::user()->id)
                                ->where('expires_at','>=', now()->toDateTimeString())
                                ->where('closed', 0)
                                ->orWhereDate('second_reminder_at','<=', Carbon\Carbon::today())
                                ->where('second_reminder_at_status', FALSE)
                                ->where('user_id', Auth::user()->id)
                                ->where('expires_at','>=', now()->toDateTimeString())
                                 ->where('closed', 0)
                                ->orWhereDate('third_reminder_at','<=', Carbon\Carbon::today())
                                ->where('third_reminder_at_status', FALSE)
                                ->where('user_id', Auth::user()->id)
                                ->where('expires_at','>=', now()->toDateTimeString())
                                ->where('closed', 0)
                                ->get()->count();
                        @endphp
                        <a href="#" class="dropdown-toggle tour-one" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i>
                            @if ($reminders_count > 0)
                            <span class="badge badge-danger">{{ $reminders_count }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu" >
                            <li>
                                <center>
                                    All Reminders
                                </center>
                            </li>
                            <div class="clearfix"></div>
                            @foreach ($reminders as $reminder)
                            <li>
                                <a href="{{ route('fitnesses.show',$reminder->id) }}"><i class="fa fa-bell"></i>{{ $reminder->reminder_item ? $reminder->reminder_item->name : "" }}  
                                    @if ($reminder->horse)
                                    for {{ $reminder->horse ? $reminder->horse->registration_number : "" }}
                                    @elseif ($reminder->vehicle)
                                    for {{ $reminder->vehicle ? $reminder->vehicle->registration_number : "" }}
                                    @elseif ($reminder->trailer)
                                    for  {{ $reminder->trailer ? $reminder->trailer->registration_number : "" }}
                                    @elseif ($reminder->driver)
                                    for  {{ $reminder->driver->employee ? $reminder->driver->employee->name : "" }}
                                    @endif
                                    expires on {{ Carbon\Carbon::parse($reminder->expires_at)->format('Y-m-d') }}
                                </a>
                            </li>
                            @endforeach
                            
                        </ul>
                    </li>
                    {{-- <li><a href="#" class=""><i class="fa fa-bell"></i><span class="badge badge-danger">0</span></a></li> --}}
                    <!-- /.dropdown -->
                    <li class="dropdown tour-two">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ucfirst(Auth::user()->name)}} {{ucfirst(Auth::user()->surname)}}<span class="caret"></span></a>
                        <ul class="dropdown-menu profile-dropdown">
                            <li class="profile-menu bg-gray">
                                <div class="">
                                    <img src="{{asset('images/avatar.png')}}" alt="{{ucfirst(Auth::user()->name)}} {{ucfirst(Auth::user()->surname)}}" class="img-circle profile-img">
                                    <div class="profile-name">
                                        <h6>{{ucfirst(Auth::user()->name)}} {{ucfirst(Auth::user()->surname)}}</h6>
                                        {{-- <a href="{{route('profile',Auth::user()->id)}}">View Profile</a> --}}
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </li>
                            {{-- <li><a href="{{route('profile',Auth::user()->id)}}"><i class="fa fa-cog"></i> Settings</a></li> --}}
                            <li><a href="{{route('profile',Auth::user()->id)}}"><i class="fa fa-cog"></i>Account Details</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{route('logout')}}" class="color-danger text-center"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </li>
                    <!-- /.dropdown -->
                    {{-- <li><a href="#" class="hidden-xs hidden-sm open-right-sidebar"><i class="fa fa-ellipsis-v"></i></a></li> --}}
                </ul>
                <!-- /.nav navbar-nav navbar-right -->
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</nav>
