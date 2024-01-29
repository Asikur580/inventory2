@extends('layout.sidenav-layout')
@section('content')
    @include('components.user.user-list')
    @include('components.user.user-delete')
    @include('components.user.user-create')
    @include('components.user.user-update')
@endsection