@extends('layouts.master')

@section('title', 'Import Excel')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Import Excel</h3>
                <p class="text-subtitle text-muted">Upload file Excel untuk mengimport data</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('data.index') }}">DataTable</a></li>
                        <li class="breadcrumb-item active">Import</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Upload File Excel</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Format Excel:</strong><br>
                    <ul class="mb-0">
                        <li>Cell <strong>B7</strong> berisi: <code>List Permasalahan Traktor Tgl: YYYY-MM-DD</code> (tanggal otomatis diekstrak)</li>
                        <li>Header baris <strong>8</strong>: No | No Instruksi | Tipe Traktor | No. Produksi | Sign | Permasalahan | Keterangan | Jenis Penanganan | PIC Repair | Kategori | Team</li>
                        <li>Data mulai dari <strong>baris 9</strong> (B9 = No, C9 = No Instruksi, ..., J9 = PIC Repair, K9 = Kategori, L9 = Team)</li>
                        <li>Warna untuk kolom <strong>Kategori, Team</strong> diambil dari <strong>warna background</strong> cell Excel (bukan warna font)</li>
                    </ul>
                </div>

                <form action="{{ route('data.import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel (.xlsx / .xls)</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Import Data
                    </button>
                    <a href="{{ route('data.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
