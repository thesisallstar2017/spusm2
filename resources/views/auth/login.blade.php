@extends('layout-clean')

@section('internal_stylesheet')
    <style type="text/css">
        .form-login, .form-reset {
            max-width: 330px;
            margin: 100px auto 0;
            background: #fff;
            border-radius: 5px;
            -webkit-border-radius: 5px;
        }

        .form-login h2.form-login-heading {
            margin: 0 0 -40px;
            padding: 40px 5px;
            text-align: center;
            border-radius: 5px 5px 0 0;
            -webkit-border-radius: 5px 5px 0 0;
            color: #fff;
            font-size: 20px;
            text-transform: uppercase;
            font-weight: 300;
        }

        .form-reset h2.form-reset-heading {
            margin: 0 0 10px;
            padding: 20px 5px;
            text-align: center;
            border-radius: 5px 5px 0 0;
            -webkit-border-radius: 5px 5px 0 0;
            color: #fff;
            font-size: 20px;
            text-transform: uppercase;
            font-weight: 300;
        }

        .login-wrap {
            padding: 20px;
        }
        .login-wrap .registration {
            text-align: center;
        }
        .login-social-link {
            display: block;
            margin-top: 20px;
            margin-bottom: 15px;
        }

    </style>
@stop

@section('content')
<div class="container">
    <div class="row">
        <form class="form-login" role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}
            <h2 class="form-login-heading"><img src="images/logo.png" style="width:200px"></h2>

            <div class="login-wrap">
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                <input id="email"  class="form-control" name="email" placeholder="Email" value="{{ old('email') }}">

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input id="password" type="password" class="form-control" name="password"
                           placeholder="Password">

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
            </div>

            {{--<div class="form-group">--}}
                    {{--<div class="checkbox">--}}
                        {{--<label>--}}
                            {{--<input type="checkbox" name="remember"><span class="text-success"> Remember Me </span>--}}
                        {{--</label>--}}
                    {{--</div>--}}
            {{--</div>--}}

            <div class="form-group">
                    <button type="submit" class="btn btn-success form-control">
                        <i class="fa fa-btn fa-sign-in"></i> SIGN IN
                    </button>
                    {{--<div style="text-align: center">--}}
                    {{--<a class="btn btn-link" style="color:#5cb85c"  href="{{ url('/password/reset') }}">Forgot Your--}}
                                                                                                 {{--Password?</a>--}}
                    {{--</div>--}}
            </div>
            </div>

        </form>

    </div>
</div>
@endsection

@section('page_js')
    <script type="text/javascript" src="js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("images/library.jpg", {speed: 500});
    </script>
@endsection


