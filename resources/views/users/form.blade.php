@extends('layouts.master')

@section('title', isset($user) ? 'Edit User' : 'Tambah User')

@push('styles')
<style>
    .card-form { border-radius: 1rem; border: none; box-shadow: 0 0 30px rgba(0,0,0,.04); max-width: 500px; }
    .form-label { font-weight: 600; color: #374151; font-size: .9rem; }
    .form-control { border-radius: .6rem; border: 1.5px solid #e5e7eb; padding: .6rem 1rem; font-size: .95rem; transition: all .2s; }
    .form-control:focus { border-color: var(--pink); box-shadow: 0 0 0 3px rgba(214,51,132,.12); }
    .form-text { font-size: .8rem; color: #9ca3af; }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title-headings mb-3">
        <h3>{{ isset($user) ? 'Edit User' : 'Tambah User' }}</h3>
        <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="card card-form">
    <div class="card-body p-4">
        <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
            @csrf
            @isset($user) @method('PUT') @endisset

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username', $user->username ?? '') }}" placeholder="Masukkan username" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="text" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="{{ isset($user) ? 'Kosongkan jika tidak diubah' : 'Masukkan password' }}"
                    {{ isset($user) ? '' : 'required' }}>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @isset($user)
                    <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                @endisset
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i>{{ isset($user) ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
