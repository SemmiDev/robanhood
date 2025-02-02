@extends('layouts.master-without-nav')
@section('title') Job landing @endsection
@section('css')
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('body')

<body data-bs-spy="scroll" data-bs-target="#navbar-example">
    @endsection
    @section('content')

    <!-- Begin page -->
    <div class="layout-wrapper landing">
        <nav class="navbar navbar-expand-lg navbar-landing fixed-top job-navbar" id="navbar">
            <div class="container-fluid custom-container">
                <a class="navbar-brand" href="index">
                    <img src="{{URL::asset('build/images/logo-dark.png')}}" class="card-logo card-logo-dark" alt="logo dark" height="17">
                    <img src="{{URL::asset('build/images/logo-light.png')}}" class="card-logo card-logo-light" alt="logo light" height="17">
                </a>
                <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="mdi mdi-menu"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                        <li class="nav-item">
                            <a class="nav-link fs-14 active" href="#hero">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" href="#process">Process</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" href="#categories">Categories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" href="#findJob">Find Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" href="#candidates">Candidates</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-14" href="#blog">Blog</a>
                        </li>
                    </ul>

                    <div class="">
                        <a href="/login" class="btn btn-soft-primary"><i class="ri-user-3-line align-bottom me-1"></i>Masuk</a>
                    </div>
                </div>

            </div>
        </nav>
        <!-- end navbar -->

        <!-- start hero section -->
        <section class="section job-hero-section bg-light pb-0" id="hero">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-6">
                        <div>
                            <h1 class="display-6 fw-semibold text-capitalize mb-3 lh-base">Find your next job and build your dream here</h1>
                            <p class="lead text-muted lh-base mb-4">Find jobs, create trackable resumes and enrich your applications. Carefully crafted after analyzing the needs of different industries.</p>
                            <form action="#" class="job-panel-filter">
                                <div class="row g-md-0 g-2">
                                    <div class="col-md-4">
                                        <div>
                                            <input type="search" id="job-title" class="form-control filter-input-box" placeholder="Job, Company name...">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-4">
                                        <div>
                                            <select class="form-control" data-choices>
                                                <option value="">Select job type</option>
                                                <option value="Full Time">Full Time</option>
                                                <option value="Part Time">Part Time</option>
                                                <option value="Freelance">Freelance</option>
                                                <option value="Internship">Internship</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-4">
                                        <div class="h-100">
                                            <button class="btn btn-primary submit-btn w-100 h-100" type="submit"><i class="ri-search-2-line align-bottom me-1"></i> Find Job</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>

                            <ul class="treding-keywords list-inline mb-0 mt-3 fs-13">
                                <li class="list-inline-item text-danger fw-semibold"><i class="mdi mdi-tag-multiple-outline align-middle"></i> Trending Keywords:</li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Design,</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Development,</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Manager,</a></li>
                                <li class="list-inline-item"><a href="javascript:void(0)">Senior</a></li>
                            </ul>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-lg-4">
                        <div class="position-relative home-img text-center mt-5 mt-lg-0">
                            <div class="card p-3 rounded shadow-lg inquiry-box">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm flex-shrink-0 me-3">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded fs-18">
                                            <i class="ri-mail-send-line"></i>
                                        </div>
                                    </div>
                                    <h5 class="fs-15 lh-base mb-0">Work Inquiry from velzon</h5>
                                </div>
                            </div>

                            <div class="card p-3 rounded shadow-lg application-box">
                                <h5 class="fs-15 lh-base mb-3">Applications</h5>
                                <div class="avatar-group">
                                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                        <div class="avatar-xs">
                                            <img src="{{URL::asset('build/images/users/avatar-3.jpg')}}" alt="" class="rounded-circle img-fluid">
                                        </div>
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Ellen Smith">
                                        <div class="avatar-xs">
                                            <div class="avatar-title rounded-circle bg-danger">
                                                S
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Ellen Smith">
                                        <div class="avatar-xs">
                                            <img src="{{URL::asset('build/images/users/avatar-10.jpg')}}" alt="" class="rounded-circle img-fluid">
                                        </div>
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                                        <div class="avatar-xs">
                                            <div class="avatar-title rounded-circle bg-success">
                                                Z
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                        <div class="avatar-xs">
                                            <img src="{{URL::asset('build/images/users/avatar-9.jpg')}}" alt="" class="rounded-circle img-fluid">
                                        </div>
                                    </a>
                                    <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="More Appliances">
                                        <div class="avatar-xs">
                                            <div class="avatar-title fs-13 rounded-circle bg-light border-dashed border text-primary">
                                                2k+
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <img src="{{URL::asset('build/images/job-profile2.png')}}" alt="" class="user-img">

                            <div class="circle-effect">
                                <div class="circle"></div>
                                <div class="circle2"></div>
                                <div class="circle3"></div>
                                <div class="circle4"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </section>
        <!-- end hero section -->


        <!-- start blog -->
        <section class="section" id="blog">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h1 class="mb-3 ff-secondary fw-semibold text-capitalize lh-base">Our Latest <span class="text-primary">News</span></h1>
                            <p class="text-muted mb-4">We thrive when coming up with innovative ideas but also understand that a smart concept should be supported with faucibus sapien odio measurable results.</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <img src="{{URL::asset('build/images/small/img-8.jpg')}}" alt="" class="img-fluid rounded" />
                            </div>
                            <div class="card-body">
                                <ul class="list-inline fs-14 text-muted">
                                    <li class="list-inline-item">
                                        <i class="ri-calendar-line align-bottom me-1"></i> 30 Oct, 2021
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="ri-message-2-line align-bottom me-1"></i> 364 Comment
                                    </li>
                                </ul>
                                <a href="javascript:void(0);">
                                    <h5>Design your apps in your own way ?</h5>
                                </a>
                                <p class="text-muted fs-14">One disadvantage of Lorum Ipsum is that in Latin certain letters appear more frequently than others.</p>

                                <div>
                                    <a href="#!" class="link-success">Learn More <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <img src="{{URL::asset('build/images/small/img-6.jpg')}}" alt="" class="img-fluid rounded" />
                            </div>
                            <div class="card-body">
                                <ul class="list-inline fs-14 text-muted">
                                    <li class="list-inline-item">
                                        <i class="ri-calendar-line align-bottom me-1"></i> 02 Oct, 2021
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="ri-message-2-line align-bottom me-1"></i> 245 Comment
                                    </li>
                                </ul>
                                <a href="javascript:void(0);">
                                    <h5>Smartest Applications for Business ?</h5>
                                </a>
                                <p class="text-muted fs-14">Due to its widespread use as filler text for layouts, non-readability is of great importance: human perception.</p>

                                <div>
                                    <a href="#!" class="link-success">Learn More <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <img src="{{URL::asset('build/images/small/img-9.jpg')}}" alt="" class="img-fluid rounded" />
                            </div>
                            <div class="card-body">
                                <ul class="list-inline fs-14 text-muted">
                                    <li class="list-inline-item">
                                        <i class="ri-calendar-line align-bottom me-1"></i> 23 Sept, 2021
                                    </li>
                                    <li class="list-inline-item">
                                        <i class="ri-message-2-line align-bottom me-1"></i> 354 Comment
                                    </li>
                                </ul>
                                <a href="javascript:void(0);">
                                    <h5>How apps is changing the IT world</h5>
                                </a>
                                <p class="text-muted fs-14">Intrinsically incubate intuitive opportunities and real-time potentialities Appropriately communicate one-to-one technology.</p>

                                <div>
                                    <a href="#!" class="link-success">Learn More <i class="ri-arrow-right-line align-bottom ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end container -->
        </section>
        <!-- end blog -->

        <!-- Start footer -->
        <footer class="custom-footer bg-dark py-5 position-relative">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mt-4">
                        <div>
                            <div>
                                <img src="{{URL::asset('build/images/logo-light.png')}}" alt="logo light" height="17" />
                            </div>
                            <div class="mt-4 fs-15">
                                <p>Premium Multipurpose Admin & Dashboard Template</p>
                                <p>You can build any type of web application like eCommerce, CRM, CMS, Project management apps, Admin Panels, etc using Velzon.</p>
                                <ul class="list-inline mb-0 footer-social-link">
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-facebook-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-github-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-linkedin-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-google-fill"></i>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="javascript: void(0);" class="avatar-xs d-block">
                                            <div class="avatar-title rounded-circle">
                                                <i class="ri-dribbble-line"></i>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 ms-lg-auto">
                        <div class="row">
                        </div>
                    </div>

                </div>

                <div class="row text-center text-sm-start align-items-center mt-5">
                    <div class="col-sm-6">
                        <div>
                            <p class="copy-rights mb-0">
                                <script>
                                    document.write(new Date().getFullYear())

                                </script> © Velzon - Themesbrand
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end mt-3 mt-sm-0">
                            <ul class="list-inline mb-0 footer-list gap-4 fs-14">
                                <li class="list-inline-item">
                                    <a href="#">Privacy Policy</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#">Terms & Conditions</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#">Security</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end footer -->

        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-info btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

    </div>
    <!-- end layout wrapper -->

    @endsection
    @section('script')
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/job-lading.init.js') }}"></script>
    @endsection
