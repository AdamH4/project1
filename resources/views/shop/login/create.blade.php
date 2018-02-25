@extends('master')

@section('body')
    <form method="POST" action="{{ route('login.create') }}">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="email">Your E-mail address:</label>
            <input class="form-control" type="email" name="email" id="email" value="{{old('mame')}}" required>
        </div>
        <div class="form-group">
            <label for="password">Your password:</label>
            <input class="form-control" type="password" name="password" id="password" required>
            <br>
            <button type="submit" class="btn btn-primary">Sign in</button>
        </div>
    </form>
    <form action="{{ route('password.request') }}" method="GET">
        <button type="submit" class="btn btn-primary">Forgot my password</button>
    </form>
    <form action="{{ route('registration.index') }}" method="GET">
        <button type="submit" class="btn btn-primary">Go and register</button>
    </form>
    @include('shop.errors.error')


@endsection()