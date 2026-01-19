<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>@yield('title')</title>

    <!-- Fontfaces CSS-->
    <link href="{{asset('backend')}}/css/font-face.css" rel="stylesheet" media="all">
    <link href="{{asset('backend')}}/vendor/fontawesome-7.1.0/css/all.min.css" rel="stylesheet" media="all">
    <link href="{{asset('backend')}}/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{{asset('backend')}}/vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{asset('backend')}}/css/aos.css" rel="stylesheet" media="all">
    <link href="{{asset('backend')}}/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="{{asset('backend')}}/css/swiper-bundle-12.0.3.min.css" rel="stylesheet" media="all">
    <link href="{{asset('backend')}}/vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{asset('backend')}}/css/theme.css" rel="stylesheet" media="all">
    @stack("style")

</head>

<body>
    <div class="page-wrapper">
        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <a href="{{ route('device.dashboard') }}" class="form-header">
                                <i class="fas fa-home"></i>
                            </a>
                            <div class="header-button">
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="{{ asset('nouser.png') }}" alt="{{auth()->user()->name}}" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">{{auth()->user()->name}}</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="{{ asset('nouser.png') }}" alt="{{auth()->user()->name}}" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#">{{auth()->user()->name}}</a>
                                                    </h5>
                                                    <span class="email">{{auth()->user()->email}}</span>
                                                </div>
                                            </div>
                                            <!-- <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="{{ route('company.profile') }}">
                                                        <i class="fas fa-cog"></i>Company Profile</a>
                                                </div>
                                            </div> -->
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="fas fa-user"></i>Profile</a>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="{{ route('logout') }}">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    @yield('content')
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="{{asset('backend')}}/js/vanilla-utils.js"></script>
    <!-- Bootstrap JS-->
    <script src="{{asset('backend')}}/vendor/bootstrap-5.3.8.bundle.min.js"></script>
    <!-- Vendor JS       -->
    <script src="{{asset('backend')}}/vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js"></script>
    <script src="{{asset('backend')}}/vendor/chartjs/chart.umd.js-4.5.1.min.js"></script>

    <!-- Main JS-->
    <script src="{{asset('backend')}}/js/bootstrap5-init.js"></script>
    <script src="{{asset('backend')}}/js/main-vanilla.js"></script>
    <script src="{{asset('backend')}}/js/swiper-bundle-12.0.3.min.js"></script>
    <script src="{{asset('backend')}}/js/aos.js"></script>
    <script src="{{asset('backend')}}/js/modern-plugins.js"></script>
    @stack("script")

</body>

</html>
<!-- end document-->