<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Layout</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Task Manager</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#"> @if(auth()->check()) {{ Auth::user()->user_role }} {{ Auth::user()->name }} @endif<span class="sr-only">(current)</span></a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
    @if(auth()->check())
        <a class="btn btn-primary my-2 mr-sm-2 my-sm-0 " href="{{ route('tasks.create') }}">Create Task</a>
        <a class="btn btn-primary my-2 mr-sm-2 my-sm-0 " href="{{ route('logout') }}">Logout</a>
      @else
        <a class="btn btn-primary my-2 mr-sm-2 my-sm-0 " href="{{ route('login') }}">login</a>
    @endif
    </form>
  </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

<!-- Add Bootstrap JS with Popper -->
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>

