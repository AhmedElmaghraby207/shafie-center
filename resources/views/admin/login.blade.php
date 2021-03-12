<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href='{{ url("/assets/images/favicon.ico")}}'>
    <title>PHLOG Admin| Log in</title>
    <!-- Bootstrap Core CSS -->
    <link href='{{ url("/assets/plugins/bootstrap/css/bootstrap.min.css") }}' rel="stylesheet">
    <!-- Custom CSS -->
    <link href='{{ url("css/style.css") }}' rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href='{{ url("css/colors/blue.css") }}' id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn''t work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">.jqstooltip {
            position: absolute;
            left: 0px;
            top: 0px;
            visibility: hidden;
            background: rgb(0, 0, 0) transparent;
            background-color: rgba(0, 0, 0, 0.6);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
            color: white;
            font: 10px arial, san serif;
            text-align: left;
            white-space: nowrap;
            padding: 5px;
            border: 1px solid white;
            z-index: 10000;
        }

        .jqsfield {
            color: white;
            font: 10px arial, san serif;
            text-align: left;
        }</style>
</head>

<body>
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader" style="display: none;">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
    </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<section id="wrapper">
    <div class="login-register" style='background-image:url({{url("/assets/images/background/login-register.jpg")}});'>
        <div class="login-box card">
            <div class="card-body">
                <form class="form-horizontal form-material" id="loginform"
                      action="{{ route('auth.login') }}?return_url={{Request::get('return_url')}}" method="post">
                    <h3 class="box-title m-b-20">Sign In</h3>
                    {{ csrf_field() }}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input name="email" class="form-control" type="email" required="" placeholder="Email"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input name="password" class="form-control" type="password" required=""
                                   placeholder="Password"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-primary pull-left p-t-0">
                                <input id="checkbox-signup" type="checkbox" name="remember_me">
                                <label for="checkbox-signup"> Remember me </label>
                            </div>
                            <!-- <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a>  -->
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                    type="submit">Log In
                            </button>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                            <div class="social">
                                <a href="javascript:void(0)" class="btn  btn-facebook" data-toggle="tooltip" title="" data-original-title="Login with Facebook"> <i aria-hidden="true" class="fa fa-facebook"></i> </a>
                                <a href="javascript:void(0)" class="btn btn-googleplus" data-toggle="tooltip" title="" data-original-title="Login with Google"> <i aria-hidden="true" class="fa fa-google-plus"></i> </a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-b-0">
                        <div class="col-sm-12 text-center">
                            <p>Don't have an account? <a href="register.html" class="text-info m-l-5"><b>Sign Up</b></a></p>
                        </div>
                    </div> -->
                </form>
                <!-- <form class="form-horizontal" id="recoverform" action="index.html">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>Recover Password</h3>
                            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Email"> </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                        </div>
                    </div>
                </form> -->
            </div>
        </div>
    </div>

</section>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src='{{ url("/assets/plugins/jquery/jquery.min.js") }}'></script>
<!-- Bootstrap tether Core JavaScript -->
<script src='{{ url("/assets/plugins/bootstrap/js/popper.min.js") }}'></script>
<script src='{{ url("/assets/plugins/bootstrap/js/bootstrap.min.js") }}'></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src='{{ url("js/jquery.slimscroll.js") }}'></script>
<!--Wave Effects -->
<script src='{{ url("js/waves.js") }}'></script>
<!--Menu sidebar -->
<script src='{{ url("js/sidebarmenu.js") }}'></script>
<!--stickey kit -->
<script src='{{ url("/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js") }}'></script>
<script src='{{ url("/assets/plugins/sparkline/jquery.sparkline.min.js") }}'></script>
<!--Custom JavaScript -->
<script src='{{ url("js/custom.min.js") }}'></script>
<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script src='{{ url("/assets/plugins/styleswitcher/jQuery.style.switcher.js") }}'></script>


</body>
</html>


 