@extends('layouts.blank')

@section('title', "Software Ready")

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-body">
                    <div class="mar-ver pad-btm text-center mb-4">
                        <h1 class="h3">{{ "Software Ready" }}</h1>
                    </div>
                    <p class="text-center mb-4">
                        {{ "No license activation is required. You can continue using the software." }}
                    </p>
                    <div class="text-center">
                        <a href="{{ url('/') }}" class="btn btn-dark px-sm-5">{{ "Continue" }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
