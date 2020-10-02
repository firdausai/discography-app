@extends('templates.default')
@section('content')
<form action = "{{ url('/login') }}" method = "POST" class="form-signin">
    @csrf
    <h1 class="h3 mb-3 font-weight-normal text-center">Login</h1>
    <label for="inputEmail" class="sr-only">Username</label>
    <input type="text" id="inputEmail" class="form-control" placeholder="Username" required="" autofocus="" name = "username">
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="" name = "password">
    <button class="btn btn-lg btn-primary btn-block mt-3" type="submit">Sign in</button>
</form>
@stop