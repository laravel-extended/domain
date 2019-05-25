@extends('domain::domain.security')

@section('content')
<div class='container'>
  <m-card>
    <template #default>
      <form action="{{ route('admin.users.store') }}" method='POST' class='card-content'>
        @csrf
        <x-form-group error="{{ $errors->first('username') }}">
          <m-text-field label='Username' name='username' value="{{ old('username') }}"></m-text-field>
        </x-form-group>
        <x-form-group error="{{ $errors->first('email') }}">
          <m-text-field label='Email' name='email' value="{{ old('email') }}"></m-text-field>
        </x-form-group>
        <x-form-group error="{{ $errors->first('password') }}">
          <m-text-field label='Password' type='password' name='password' value="{{ old('password') }}"></m-text-field>
        </x-form-group>
        <x-form-group error="{{ $errors->first('password') }}">
          <m-text-field label='Password' type='password' name='password_confirmation' value="{{ old('password') }}"></m-text-field>
        </x-form-group>

        {{ $service->view()->render('create') }}

        <m-button type='submit'>Save</m-button>
      </form>
    </template>
  </m-card>
</div>
@endsection
