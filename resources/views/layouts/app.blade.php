<!DOCTYPE html>
<html lang="en" data-topbar-color="brand">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    <!-- Plugins css -->
    <link href="/assets/libs/mohithg-switchery/switchery.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/quill/quill.core.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/quill/quill.bubble.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/quill/quill.snow.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet" type="text/css">
    <link href="/assets/libs/clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/libs/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css">
    <link href="/assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

    <!-- icons -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <link href="/assets/css/main.css" rel="stylesheet" type="text/css" />

    <!-- Theme Config Js -->
    <script src="/assets/js/config.js"></script>
    <link href="/assets/css/custom.css" rel="stylesheet" type="text/css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>

    <!-- Begin page -->
    <div id="wrapper">


        <!-- Topbar Start -->
        <div class="navbar-custom">
            <div class="container-fluid">

                <ul class="list-unstyled topnav-menu float-end mb-0">



                    <li class="dropdown d-inline-block d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <i class="fe-search noti-icon"></i>
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                            <form class="p-3">
                                <input type="text" class="form-control" placeholder="Search ..."
                                    aria-label="Search">
                            </form>
                        </div>
                    </li>

                    <li class="d-none d-md-inline-block">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" id="light-dark-mode"
                            href="#">
                            <i class="fe-moon noti-icon"></i>
                        </a>
                    </li>


                    <li class="dropdown d-none d-lg-inline-block">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light"
                            data-toggle="fullscreen" href="#">
                            <i class="fe-maximize noti-icon"></i>
                        </a>
                    </li>




                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="fe-bell noti-icon"></i>
                            <span class="badge bg-danger rounded-circle noti-icon-badge unreadCount">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-lg">

                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="m-0">
                                    <span class="float-end">
                                        <a href="" class="text-dark">
                                            <small>Clear All</small>
                                        </a>
                                    </span>Notification
                                </h5>
                            </div>

                            <div class="noti-scroll" id="noti-scroll" data-simplebar>

                            </div>

                            <!-- All-->
                            <a href="{{ route('notifications') }}"
                                class="dropdown-item text-center text-primary notify-item notify-all">
                                View all
                                <i class="fe-arrow-right"></i>
                            </a>

                        </div>
                    </li>

                    <script>
                        $(document).ready(function() {
                            $('.dropdown.notification-list.topbar-dropdown').on('click', function() {
                                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                console.log('first');
                                // Thực hiện yêu cầu AJAX GET tại đây
                                $.ajax({
                                    url: '{{ route('notification.read') }}',
                                    method: 'GET',
                                    data: {
                                        _token: '{{ csrf_token() }}' // Thêm mã CSRF vào yêu cầu
                                    },
                                    success: function(response) {
                                        // Xử lý kết quả trả về từ yêu cầu GET tại đây
                                    },
                                    error: function(xhr, status, error) {
                                        // Xử lý lỗi tại đây
                                    }
                                });
                            });
                            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            $.ajax({

                                url: '{{ route('notification.new') }}',
                                method: 'GET',
                                data: {
                                    _token: '{{ csrf_token() }}' // Thêm mã CSRF vào yêu cầu
                                },
                                success: function(response) {
                                    console.log('first')
                                    // Hiển thị HTML trả về
                                    $('#noti-scroll').html(response.html);
                                    $('.unreadCount').html(response.unreadCount);
                                },
                                error: function(xhr, status, error) {
                                    console.log(error);
                                }
                            });
                        });
                    </script>

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <?php
                            if($currentUser->getFirstMedia('avatar')){
                                ?>
                                <img src="<?php echo $currentUser->getFirstMedia('avatar')->getFullUrl() ?>" alt="user-image" class="rounded-circle">
                                <?php
                            } else {
                                ?>
                                <img src="/assets/images/users/avatar-basic.jpg" alt="user-image" class="rounded-circle">
                                <?php
                            }
                            ?>

                            <span class="pro-user-name ms-1">
                                <?php echo $currentUser->name ?> <i class="mdi mdi-chevron-down"></i>

                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a href="{{ route('user.detail', $currentUser->id) }}" class="dropdown-item notify-item">
                                <i class="ri-account-circle-line"></i>
                                <span>{{ __('messages.my_account') }}</span>
                            </a>

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                                <i class="ri-logout-box-line"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </li>



                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="index.html" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="/assets/images/logo-sm-dark.png" alt="" height="24">
                            <!-- <span class="logo-lg-text-light">Minton</span> -->
                        </span>
                        <span class="logo-lg">
                            <img src="/assets/images/logo-dark.png" alt="" height="20">
                            <!-- <span class="logo-lg-text-light">M</span> -->
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="/assets/images/logo-sm.png" alt="" height="24">
                        </span>
                        <span class="logo-lg">
                            <img src="/assets/images/logo-light.png" alt="" height="20">
                        </span>
                    </a>
                </div>

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="fe-menu"></i>
                        </button>
                    </li>

                    <li>
                        <!-- Mobile menu toggle (Horizontal Layout)-->
                        <a class="navbar-toggle nav-link" data-bs-toggle="collapse"
                            data-bs-target="#topnav-menu-content">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>




                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- Topbar End -->

        <!-- ========== Left Sidebar Start ========== -->
        @include('layouts.left_side_menu')

        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            @yield('content')
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->



    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>



    <!-- Vendor js -->
    <script src="/assets/js/vendor.min.js"></script>

    <!-- Plugins js -->
    <script src="/assets/libs/dropzone/min/dropzone.min.js"></script>
    <script src="/assets/libs/selectize/js/standalone/selectize.min.js"></script>
    <script src="/assets/libs/mohithg-switchery/switchery.min.js"></script>
    <script src="/assets/libs/multiselect/js/jquery.multi-select.js"></script>
    <script src="/assets/libs/jquery.quicksearch/jquery.quicksearch.min.js"></script>
    <script src="/assets/libs/select2/js/select2.min.js"></script>
    <script src="/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script src="/assets/libs/quill/quill.min.js"></script>

    <script src="/assets/libs/spectrum-colorpicker2/spectrum.min.js"></script>
    <script src="/assets/libs/clockpicker/bootstrap-clockpicker.min.js"></script>
    <script src="/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/assets/libs/moment/min/moment.min.js"></script>
    <script src="/assets/libs/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Footable js -->
    <script src="/assets/libs/footable/footable.all.min.js"></script>

    <!-- Init js -->
    <!-- <script src="/assets/js/pages/foo-tables.init.js"></script> -->
    <script src="/assets/js/pages/form-advanced.init.js"></script>
    <script src="/assets/js/pages/form-quilljs.init.js"></script>
    <script src="/assets/js/pages/form-fileuploads.init.js"></script>
    <!-- <script src="/assets/js/vendor.min.js"></script> -->

    <!-- App js -->
    <script src="/assets/js/app.min.js"></script>
    <script src="/assets/js/main.js"></script>

</body>

</html>
