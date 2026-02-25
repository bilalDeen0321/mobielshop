<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Admin Login - {{ config('app.name') }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/css/components.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/theme/assets/pages/css/login.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body class=" login">
    <div class="logo">
        <a href="{{ route('admin.login') }}">
            <img src="{{ asset('admin/theme/assets/layouts/layout2/img/logo-default.png') }}" alt="Logo" style="max-height: 60px;" />
        </a>
    </div>
    <div class="content">
        <form class="login-form" action="{{ route('admin.login') }}" method="POST" id="admin-login-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <h3 class="form-title font-green">Sign In</h3>
            @error('email')
                <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    <span>{{ $message }}</span>
                </div>
            @enderror
            @if(session('status') === '419' || $errors->has('_token'))
                <div class="alert alert-danger">
                    <span>Your session expired. Please try again.</span>
                </div>
            @endif
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Email</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="email" autocomplete="off" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus />
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" required />
            </div>
            <div class="form-actions">
                <button type="submit" class="btn green uppercase" id="admin-login-btn">Login</button>
                <label class="rememberme check">
                    <input type="checkbox" name="remember" value="1" /> Remember
                </label>
            </div>
        </form>
    </div>
    <div class="copyright"> {{ date('Y') }} &copy; {{ config('app.name') }} Admin. </div>
    <script src="{{ asset('admin/theme/assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/theme/assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
    <script>
        document.getElementById('admin-login-form').addEventListener('submit', function() {
            var btn = document.getElementById('admin-login-btn');
            if (btn) { btn.disabled = true; btn.textContent = 'Logging in...'; }
        });
    </script>
</body>
</html>
