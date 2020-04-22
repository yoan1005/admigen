<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admigen | Administration</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

      <!-- Bootstrap core CSS     -->
      <link href="{{url('/vendor/admigen/css/bootstrap.min.css')}}" rel="stylesheet" />
      <link href="{{url('/vendor/admigen/css/adminlte.css')}}" rel="stylesheet" />

      <!--     Fonts and icons     -->
      <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
      <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" style="background: #eaeaea">
<div class="login-box" >
  <div class="login-logo">
	  {{-- <img src="../vendor/admigen/img/logo_ww.svg" height="50" alt="" ><br> --}}
   <a href="">Admin <b>Admigen</b> </a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Veuillez vous connecter grâce à votre compte <b>administrateur</b></p>


    <form action="{{route('loginAdmin')}}" method="post">
      {{ csrf_field() }}
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="email" placeholder="E-mail">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Mot de passe">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        @if(is_string($errors))
          <p class="text-danger">{{ $errors }}</p>
        @endif

        <div class="col-12 text-center">
          <button type="submit" class="btn btn-primary btn-inline-block btn-flat">Connexion</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="{{url('/vendor/admigen/js/jquery-3.2.1.min.js')}}" type="text/javascript"></script>

<script src="{{url('/vendor/admigen/js/bootstrap.min.js')}}" type="text/javascript"></script>

</body>
</html>
