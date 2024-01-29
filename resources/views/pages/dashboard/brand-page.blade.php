@extends('layout.sidenav-layout')
@section('content')
    @include('components.brand.brand-list')
    @include('components.brand.brand-delete')
    @include('components.brand.brand-create')
    @include('components.brand.brand-update')
@endsection
