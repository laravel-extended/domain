@extends('material::layouts.admin')

@section('title', 'Security')

@section('subtitle', 'email@material.io')

@section('sidebar')
  @parent
  <m-list tag='nav'>
    <m-list-item tag='a' href='/admin/users' text='Users' graphic='people' aria-current='page' activated></m-list-item>
    <m-list-item tag='a' href='/admin/roles' text='Roles' graphic='people' aria-current='page'></m-list-item>
    <!-- <a v-list-item href='#'>Settings</a> -->
  </m-list>
  <hr class="mdc-list-divider">
@endsection
