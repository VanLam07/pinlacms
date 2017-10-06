<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Pinla CMS - @yield('title', 'Pinla CMS')</title>
        
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        
        <link rel="stylesheet" href="/css/select2.min.css">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/font-awesome.min.css">
        <link rel="stylesheet" href="/plugins/adminlte/css/AdminLTE.css">
        <link rel="stylesheet" href="/plugins/adminlte/css/skins/_all-skins.css">
        
        <link rel="stylesheet" href="/modules/admin/css/main.css">
        <link rel="stylesheet" href="/modules/admin/css/screen.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        @yield('head')
        
        <script>
            var _token = '{{ csrf_token() }}';
        </script>

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue sidebar-mini @yield('body_class')" @yield('body_attrs')>
        
        <div class="wrapper">

            @include('admin::parts.menubar')
            
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>@yield('title')</h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li class="active">Home</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    
                    @yield('nav_status')
                    
                    @yield('content')
                    
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 1.0
                </div>
                <strong>Copyright &copy; 2017 <a href="#">Pinla CMS</a>.</strong> All rights reserved.
            </footer>
        </div>
        <!-- ./wrapper -->

        <script>
            var file_dialog_url = '{{ route("admin::file.dialog") }}';
            var filemanager_title  = '{{ trans("admin::view.filemanager_title") }}';
        </script>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/jquery-ui.min.js"></script>
        <script src="/js/select2.min.js" ></script>
        <script src="/js/popper.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/plugins/adminlte/js/adminlte.min.js"></script>
        <script src="/js/bootbox.min.js"></script>
        
        <script src="/modules/admin/js/script.js"></script>
        
        @yield('foot')
        
    </body>
</html>
