@extends('layouts.master')

@section('title', 'Manage Users')

@push('styles')
<style>
    .card { border-radius: 1rem; border: none; box-shadow: 0 0 30px rgba(0,0,0,.04); }
    .table-users th { font-weight: 700; color: #374151; border-top: none; }
    .table-users td { vertical-align: middle; }
    .badge-role { background: #f0f0f5; color: #555; padding: 4px 12px; border-radius: 20px; font-size: .8rem; }
    .btn-sm-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
    .modal-confirm .modal-header { border: none; padding-bottom: 0; }
    .modal-confirm .modal-footer { border: none; padding-top: 0; }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title-headings mb-3">
        <h3>Manage Users</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah User
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-users table-hover mb-0">
            <thead>
                <tr>
                    <th class="ps-4" style="width:60px">#</th>
                    <th>Username</th>
                    <th style="width:180px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $i => $user)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $user->username }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light-primary btn-sm-icon me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-light-danger btn-sm-icon" title="Hapus"
                                onclick="confirmDelete({{ $user->id }}, '{{ $user->username }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Belum ada user</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<form id="delete-form" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
function confirmDelete(id, username) {
    Swal.fire({
        title: 'Hapus User?',
        text: 'Yakin ingin menghapus "' + username + '"?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            var form = document.getElementById('delete-form');
            form.action = '{{ url('users') }}/' + id;
            form.submit();
        }
    });
}
</script>
@endpush
