<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Admigen • Dashboard</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="/vendor/admigen/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Animation library for notifications   -->
    <link href="/vendor/admigen/css/animate.min.css" rel="stylesheet"/>
    <!--  Light Bootstrap Table core CSS    -->
    <link href="/vendor/admigen/css/light-bootstrap-dashboard.css" rel="stylesheet"/>
    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="/vendor/admigen/css/demo.css" rel="stylesheet" />

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="/vendor/admigen/css/pe-icon-7-stroke.css" rel="stylesheet" />
    @yield('styles')
		<script src="/vendor/admigen/js/jquery-3.2.1.min.js" type="text/javascript"></script>
		<script src="/vendor/admigen/js/bootstrap.min.js" type="text/javascript"></script>


</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="blue">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    	<div class="sidebar-wrapper">


            <ul class="nav">
                <!-- <li class="active">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="pe-7s-graph"></i>
                        <p>ACCUEIL</p>
                    </a>
                </li> -->
								@foreach (config('admigen.models') as $model => $name)
									  <li class="w-100 ">
											<a href="{{ route('admin.show', mb_strtolower($model)) }}" class=" p-2" >
													<i class="pe-7s-server"></i>
													<p>{{$name}}</p>
											</a>
									</li>
								@endforeach

            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Dashboard</a>
                </div>
                <div class="collapse navbar-collapse">


                    <ul class="nav navbar-nav navbar-right">
                        <li>
                           <a href="">
                               <p>{{ Auth::user()->fullname }}</p>
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="document.getElementById('logout').submit()">
                                <p>Déconnexion</p>
                            </a>
                            <form action="/logout" id="logout" style=" display: none;" method="post">
                                {{ csrf_field()}}
                            </form>
                        </li>
						<li class="separator hidden-lg"></li>
                    </ul>
                </div>
            </div>
        </nav>

    @yield('content')


</body>

    <!--   Core JS Files   -->

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="/vendor/admigen/js/light-bootstrap-dashboard.js"></script>
	@yield('scripts')

</html>
