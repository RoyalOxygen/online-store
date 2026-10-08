@extends('layouts.blank')

@section('title', 'Software Update')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <div class="card mt-3">
                <div class="card-body">
                    <div class="mar-ver pad-btm text-center mb-4">
                        <h1 class="h3">{{ "Software Update" }}</h1>
                    </div>

                    <form method="POST" action="{{route('update-system')}}">
                        @csrf
                        <p class="text-center mb-4">
                            {{ "Apply pending database migrations and finish the software update. No license key is required." }}
                        </p>
                        <div class="text-center">
                            <button type="submit" class="btn btn-dark px-sm-5">{{ "Update" }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
