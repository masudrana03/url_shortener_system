@extends('backend.layouts.app')
@section('title', 'Url Stats')

@section('content')

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Short URL Stats</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Short URL Stats</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Original URL : <a href="{{ url($shortenedUrl->original_url) }}" target="_blank">{{ Str::limit($shortenedUrl->original_url, 50) }}</a>
                                </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Short URL</th>
                                        <th>Clicked At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($shortenedUrl->clicks as $click)
                                        <tr class="align-middle">
                                            <td>{{ $loop->iteration }}</td>
                                            <td><a href="{{ url($shortenedUrl->short_url) }}" target="_blank">{{ url($shortenedUrl->short_url) }}</a></td>
                                            <td>{{ $click->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No clicks recorded for this URL.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Custom styles can go here */
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Additional JavaScript can go here
        });
    </script>
@endpush
