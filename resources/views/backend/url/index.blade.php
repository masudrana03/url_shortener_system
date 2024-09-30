@extends('backend.layouts.app')
@section('title', 'Url Management')

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Short Url</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Short Url</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <div class="card-title">Short Url Form</div>
                        </div>
                        <form action="{{ route('short-url.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="original_url" class="form-label me-2">Original Url</label>
                                    <input type="text" class="form-control @error('original_url') is-invalid @enderror" name="original_url" id="original_url" required value="{{ old('original_url') }}">
                                    @error('original_url')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="custom_url" class="form-label me-2">Custom Url</label>
                                    <input type="text" class="form-control @error('custom_url') is-invalid @enderror" name="custom_url" id="custom_url"  value="{{ old('custom_url') }}">
                                    @error('custom_url')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-8 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Url List</h3>
                            <div class="card-tools">
                                <form method="GET" action="{{ route('short-url.index') }}" class="mb-3">
                                    <div class="input-group">
                                        <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search URLs...">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                </form>
                              </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Original URL</th>
                                        <th>Short URL</th>
                                        <th>Clicks</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($urls as $url)
                                        <tr class="align-middle">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ Str::limit($url->original_url, 50) }}</td>
                                            <td><a href="{{ route('url.shortener',$url->short_url ) }}" target="_blank">{{ route('url.shortener',$url->short_url )  }}</a></td>
                                            <td>{{ $url->click_count }}</td>
                                            <td>{{ $url->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('short-url.stats', $url->slug) }}" class="btn btn-info btn-sm">Stats</a>
                                                <form action="{{ route('short-url.destroy', $url->slug) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this URL?');">Delete</button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3" >
                                {{ $urls->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>

    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
