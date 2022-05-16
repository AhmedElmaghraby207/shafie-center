<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Website Title -->
    <title> Dr Ahmed El-shafie App </title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700&display=swap&subset=latin-ext"
          rel="stylesheet">
    <link href="{{url('/app-assets/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{url('/app-assets/css/fontawesome.min.css')}}" rel="stylesheet">

    <!-- Favicon  -->
    <link rel="icon"
          href="{{url(App\Setting::where('key', 'icon_image')->first() ? App\Setting::where('key', 'icon_image')->first()->value : "")}}">

    <style>

        /******************************/
        /*     General Styles     */
        /******************************/
        body,
        html {
            width: 100%;
            height: 100%;
        }

        body, p {
            color: #555;
            font: 400 1rem/1.625rem "Open Sans", sans-serif;;
        }

        .p-large {
            font: 400 1.125rem/1.75rem "Open Sans", sans-serif;
        }

        .p-small {
            font: 400 0.875rem/1.5rem "Open Sans", sans-serif;
        }

        h1 {
            color: #333;
            font: 700 2.5rem/3.125rem "Open Sans", sans-serif;
            letter-spacing: -0.2px;
        }

        h2 {
            color: #333;
            font: 700 2rem/2.625rem "Open Sans", sans-serif;
            letter-spacing: -0.2px;
        }

        h4 {
            color: #333;
            font: 700 1.375rem/1.75rem "Open Sans", sans-serif;
            letter-spacing: -0.1px;
        }

        .li-space-lg li {
            margin-bottom: 0.375rem;
        }

        a.white {
            color: #fff;
        }

        /*********************/
        /*    Header     */
        /*********************/
        .header {
            background-color: #4F9873;
        }

        .header .header-content {
            padding-top: 8rem;
            padding-bottom: 4rem;
            text-align: center;
        }

        .header .text-container {
            margin-bottom: 3rem;
        }

        .header h1 {
            margin-bottom: 1rem;
            color: #fff;
            font-size: 2.5rem;
            line-height: 3rem;
        }

        .header .p-large {
            margin-bottom: 2rem;
            color: #f3f7fd;
        }

        .header-frame {
            margin-top: -1px; /* To remove white margin in FF */
            width: 100%;
            height: 2.25rem;
        }

        /***********************/
        /*     Details     */
        /***********************/
        .basic-1 {
            padding-top: 7.5rem;
            padding-bottom: 8rem;
        }

        .basic-1 .text-container {
            margin-bottom: 3.75rem;
        }

        .basic-1 .list-unstyled {
            margin-bottom: 1.375rem;
        }

        .basic-1 .list-unstyled .fas {
            color: #4F9873;
            font-size: 0.5rem;
            line-height: 1.625rem;
        }

        .basic-1 .list-unstyled .media-body {
            margin-left: 0.625rem;
        }

        /**********************/
        /*     Footer     */
        /**********************/
        .footer-frame {
            width: 100%;
            height: 1.5rem;
        }

        .footer {
            padding-top: 3rem;
            padding-bottom: 0.5rem;
            background-color: #4F9873;
        }

        .footer .footer-col {
            margin-bottom: 2.25rem;
        }

        .footer h4 {
            margin-bottom: 0.625rem;
            color: #fff;
        }

        .footer .list-unstyled,
        .footer p {
            color: #f3f7fd;
        }

        .footer .footer-col.middle .list-unstyled .fas {
            color: #fff;
            font-size: 0.5rem;
            line-height: 1.5rem;
        }

        /*****************************/
        /*     Media Queries     */
        /*****************************/
        /* Min-width width 768px */
        @media (min-width: 768px) {
            .header .text-container {
                margin-bottom: 4rem;
            }

            .header h1 {
                font-size: 3.5rem;
                line-height: 4.125rem;
            }

            .header-frame {
                height: 5.5rem;
            }

            .footer-frame {
                height: 5rem;
                margin-bottom: -10px;
            }
        }

        /* end of min-width width 768px */
    </style>
</head>

<!-- Header -->
<header id="header" class="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="text-container mt-5">
                    <h1>Dr Ahmed El-Shafie App</h1>
                </div> <!-- end of text-container -->
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</header> <!-- end of header -->
<svg class="header-frame" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
     viewBox="0 0 1920 310">
    <defs>
        <style>.cls-1 {
                fill: #4F9873;
            }</style>
    </defs>
    <title>header-frame</title>
    <path class="cls-1"
          d="M0,283.054c22.75,12.98,53.1,15.2,70.635,14.808,92.115-2.077,238.3-79.9,354.895-79.938,59.97-.019,106.17,18.059,141.58,34,47.778,21.511,47.778,21.511,90,38.938,28.418,11.731,85.344,26.169,152.992,17.971,68.127-8.255,115.933-34.963,166.492-67.393,37.467-24.032,148.6-112.008,171.753-127.963,27.951-19.26,87.771-81.155,180.71-89.341,72.016-6.343,105.479,12.388,157.434,35.467,69.73,30.976,168.93,92.28,256.514,89.405,100.992-3.315,140.276-41.7,177-64.9V0.24H0V283.054Z"/>
</svg>
<!-- end of header -->

<!-- Details -->
<div id="details" class="basic-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="text-container">
                    <h2>DATA DELETION INSTRUCTIONS</h2>
                    <p> Dr Ahmed El-shafie App does not store your personal data, It is used only for login according to
                        the Facebook
                        plat-form rules, we have to provide <span style="color:black;"><strong> User Data Deletion Callback URL or Data Deletion Instructions URL.</strong></span>
                    </p>
                    <p> If you want to delete your activities for Dr Ahmed El-shafie App, follow these instructions:</p>
                    <ul class="list-unstyled li-space-lg">
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">Go to Your Facebook Account's Setting & Privacy. Click "Setting"
                            </div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">Then, go to • Apps and Websites and you will see all of your Apps
                                activities
                            </div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body"> Select the option box for Dr Ahmed El-shafie App</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body"> Click "Remove" button</div>
                        </li>
                    </ul>
                </div> <!-- end of text-container -->
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of basic-1 -->
<!-- end of details -->
<!-- Footer -->
<svg class="footer-frame" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
     viewBox="0 0 1920 79">
    <defs>
        <style>.cls-2 {
                fill: #4F9873;
            }</style>
    </defs>
    <title>footer-frame</title>
    <path class="cls-2"
          d="M0,72.427C143,12.138,255.5,4.577,328.644,7.943c147.721,6.8,183.881,60.242,320.83,53.737,143-6.793,167.826-68.128,293-60.9,109.095,6.3,115.68,54.364,225.251,57.319,113.58,3.064,138.8-47.711,251.189-41.8,104.012,5.474,109.713,50.4,197.369,46.572,89.549-3.91,124.375-52.563,227.622-50.155A338.646,338.646,0,0,1,1920,23.467V79.75H0V72.427Z"
          transform="translate(0 -0.188)"/>
</svg>
<div class="footer">
</div> <!-- end of footer -->
<!-- end of footer -->


<!-- Scripts -->
<script src="{{url('/app-assets/js/core/libraries/jquery.min.js')}}"></script>
<script src="{{url('/app-assets/js/core/libraries/bootstrap.min.js')}}"></script>
</body>
</html>
