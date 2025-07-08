@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile Information') }}</div>

                <div class="card-body">
                    <p class="text-muted mb-4">Update your account's profile information and email address.</p>

                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success" role="alert">
                            Profile saved successfully.
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <!-- Avatar Display & Upload -->
                        <div class="mb-3 text-center">
                            @if (Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Current Avatar" class="rounded-circle mb-2" width="100" height="100">
                            @else
                                <img src="https://via.placeholder.com/100" alt="Default Avatar" class="rounded-circle mb-2">
                            @endif
                            <label for="avatar" class="form-label">Change Profile Picture</label>
                            <input class="form-control" type="file" id="avatar" name="avatar">
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Back</a>
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- You can add the Update Password and Delete Account cards here later --}}

        </div>
    </div>
</div>
@endsection