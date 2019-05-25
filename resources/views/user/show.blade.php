@extends('domain::domain.security')

@section('content')
<div class='container'>
  <h1>{{ $user->name }}</h1>
  <div>{{ $service->view()->render('show') }}</div>
</div>
@endsection
