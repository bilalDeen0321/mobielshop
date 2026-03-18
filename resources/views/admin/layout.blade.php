<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
    @stack('styles')
    <link href="{{ asset('admin/theme/assets/global/css/components.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/layouts/layout2/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/layouts/layout2/css/themes/blue.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('admin/theme/assets/layouts/layout2/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        /* Ensure modals appear above theme header/sidebar (z-index 9995-99999) and are clickable */
        .modal-backdrop { z-index: 100050 !important; opacity: 0.5 !important; }
        .modal { z-index: 100060 !important; }
    </style>
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <div class="page-header-inner">
            <div class="page-logo">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('admin/theme/assets/layouts/layout2/img/logo-default.png') }}" alt="logo" class="logo-default" />
                </a>
                <div class="menu-toggler sidebar-toggler"></div>
            </div>
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
            <div class="page-top">
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown dropdown-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <img alt="" class="img-circle" src="{{ asset('admin/theme/assets/layouts/layout2/img/avatar3_small.jpg') }}" />
                                <span class="username username-hide-on-mobile"> {{ Auth::guard('admin')->user()->name ?? 'Admin' }} </span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                        <i class="icon-key"></i> Log Out
                                    </a>
                                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <div class="page-sidebar navbar-collapse collapse">
                <ul class="page-sidebar-menu page-header-fixed page-sidebar-menu-hover-submenu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="icon-home"></i>
                            <span class="title">Dashboard</span>
                            @if(isset($lowStockCount) && $lowStockCount > 0)
                            <span class="badge badge-danger">{{ $lowStockCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.products.*') ? 'start active open' : '' }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-basket"></i>
                            <span class="title">Products</span>
                            <span class="arrow {{ request()->routeIs('admin.products.*') ? 'open' : '' }}"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('admin.products.index') }}" class="nav-link">
                                    <span class="title">List Products</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.products.create') }}" class="nav-link">
                                    <span class="title">Add Product</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'start active open' : '' }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-folder"></i>
                            <span class="title">Categories</span>
                            <span class="arrow {{ request()->routeIs('admin.categories.*') ? 'open' : '' }}"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('admin.categories.index') }}" class="nav-link">
                                    <span class="title">List Categories</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.categories.create') }}" class="nav-link">
                                    <span class="title">Add Category</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.brands.*') ? 'start active open' : '' }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="icon-tag"></i>
                            <span class="title">Brands</span>
                            <span class="arrow {{ request()->routeIs('admin.brands.*') ? 'open' : '' }}"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('admin.brands.index') }}" class="nav-link">
                                    <span class="title">List Brands</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.brands.create') }}" class="nav-link">
                                    <span class="title">Add Brand</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.pos.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.pos.index') }}" class="nav-link">
                            <i class="icon-handbag"></i>
                            <span class="title">Point of Sale</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.sales.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.sales.index') }}" class="nav-link">
                            <i class="icon-docs"></i>
                            <span class="title">Sales</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.orders.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link">
                            <i class="icon-basket-loaded"></i>
                            <span class="title">Orders</span>
                            @if(isset($pendingWebsiteOrdersCount) && $pendingWebsiteOrdersCount > 0)
                            <span class="badge badge-danger">{{ $pendingWebsiteOrdersCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.customers.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.customers.index') }}" class="nav-link">
                            <i class="icon-users"></i>
                            <span class="title">Customers</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.suppliers.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.suppliers.index') }}" class="nav-link">
                            <i class="icon-briefcase"></i>
                            <span class="title">Suppliers</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.purchases.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.purchases.index') }}" class="nav-link">
                            <i class="icon-basket-loaded"></i>
                            <span class="title">Purchases</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.inventory.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.inventory.index') }}" class="nav-link">
                            <i class="icon-layers"></i>
                            <span class="title">Inventory</span>
                            @if(isset($lowStockCount) && $lowStockCount > 0)
                            <span class="badge badge-danger">{{ $lowStockCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.returns.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.returns.index') }}" class="nav-link">
                            <i class="icon-arrow-left"></i>
                            <span class="title">Returns</span>
                        </a>
                    </li>
                    @php
                        try {
                            $sellRequestsTotal = \App\Models\SellRequest::count();
                        } catch (\Throwable $e) {
                            $sellRequestsTotal = 0;
                        }
                    @endphp
                    <li class="nav-item {{ request()->routeIs('admin.sell-requests.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.sell-requests.index') }}" class="nav-link">
                            <i class="icon-call-in"></i>
                            <span class="title">Sell requests</span>
                            @if($sellRequestsTotal > 0)
                                <span class="badge badge-info">{{ $sellRequestsTotal }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.reports.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link">
                            <i class="icon-graph"></i>
                            <span class="title">Reports</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'start active open' : '' }}">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link">
                            <i class="icon-settings"></i>
                            <span class="title">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
        <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->
    <div class="page-footer">
        <div class="page-footer-inner"> {{ date('Y') }} &copy; {{ config('app.name') }} Admin. </div>
        <div class="scroll-to-top"><i class="icon-arrow-up"></i></div>
    </div>
    <script src="{{ asset('admin/theme/assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/layouts/layout2/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/layouts/layout2/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script>window.adminCurrencySymbol = @json($currency ?? '£');</script>
    @stack('scripts')
</body>
</html>
