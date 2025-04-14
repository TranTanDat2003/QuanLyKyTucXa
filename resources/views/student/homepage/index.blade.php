@extends('layouts.student')

@section('title', 'Quản lý Báo cáo Lỗi')

@section('content')
    <!-- Banner -->
    @include('student.homepage.banner')

    <!-- Quick Access -->
    @include('student.homepage.quickAccess')

    <!-- News Section -->
    @include('student.homepage.new')
@endsection

@push('styles')
<style>
    #dataTable td:nth-child(6) {
        width: 80px;
        height: 80px;
    }
</style>
@endpush