<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Boons</title>
    <link rel="stylesheet" href="/web/css/admin.min.css">
</head>

<body class="hold-transition skin-purple login-page">
    <div class="login-box">
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
                @if (session('error'))
                           <span style="color:red;" >Wrong details</span> 
                @endif
            <form action="{{ route('admin.login') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input name="username" class="form-control" placeholder="Username" value="{{ old('username') }}">
                    @if ($errors->has('username'))
                    <span class="field-validation-error">{{ $errors->first('username') }}</span>
                    @endif
                </div>
                <div class="form-group has-feedback">
                    <input name="password" type="password" class="form-control" placeholder="Password">
                    @if ($errors->has('password'))
                    <span class="field-validation-error">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="form-group">
                     <img style="width:85%;" src="/captcha.html">
                    <input id="captcha" name="captcha" type="text" class="form-control" placeholder="Captcha">
                    @if ($errors->has('captcha'))
                    <span class="field-validation-error">{{ $errors->first('captcha') }}</span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</body>

</html>