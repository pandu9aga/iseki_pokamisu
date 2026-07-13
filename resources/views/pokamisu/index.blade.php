@extends('layouts.master')

@section('title', 'Data Table')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}">
<style>
    #table1 { width: 100%; }
    #table1 thead tr.filters th { vertical-align: top; padding: 6px 4px; }
    #table1 thead tr.filters th input { width: 100% !important; margin-bottom: 2px; box-sizing: border-box; }
    #table1 thead tr.filters th select { width: 100%; font-size: 11px; padding: 2px 3px; margin-top: 1px; border: 1px solid #ddd; border-radius: 3px; }
    #table1 thead tr.filters th .color-wrap { position: relative; width: 100%; margin-top: 2px; height: 22px; }
    #table1 thead tr.filters th .color-wrap select.cf-select { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }
    #table1 thead tr.filters th .color-wrap .cf-trigger { display: flex; align-items: center; gap: 3px; border: 1px solid #ddd; border-radius: 3px; padding: 2px 4px; background: #fff; min-height: 20px; font-size: 11px; cursor: pointer; box-sizing: border-box; height: 22px; }
    #table1 thead tr.filters th .color-wrap .cf-trigger .dot { width: 14px; height: 14px; border-radius: 2px; flex-shrink: 0; }
    #table1 thead tr.filters th .color-wrap .cf-trigger .arrow { margin-left: auto; font-size: 8px; color: #999; }
    #table1 thead tr.filters th .color-wrap .cf-dropdown { display: none; position: absolute; top: 100%; left: 0; min-width: 100%; background: #fff; border: 1px solid #ccc; border-radius: 3px; z-index: 9999; max-height: 160px; overflow-y: auto; }
    #table1 thead tr.filters th .color-wrap.open .cf-dropdown { display: block; }
    #table1 thead tr.filters th .color-wrap .cf-option { display: flex; align-items: center; gap: 4px; padding: 3px 6px; cursor: pointer; font-size: 11px; }
    #table1 thead tr.filters th .color-wrap .cf-option:hover { background: #eef1ff; }
    #table1 thead tr.filters th .color-wrap .cf-option .dot { width: 14px; height: 14px; border-radius: 2px; flex-shrink: 0; }
    #table1 thead tr.filters th .btn-date-trigger { flex-shrink: 0; padding: 1px 6px; border: 1px solid #ddd; border-radius: 3px; background: #fff; cursor: pointer; line-height: 1; display: flex; align-items: center; }
    #table1 thead tr.filters th     .btn-date-trigger:hover { background: #eef1ff; }
    .preset-batch { width: 22px; height: 22px; border-radius: 3px; border: 2px solid transparent; cursor: pointer; padding: 0; flex-shrink: 0; }
    .preset-batch.selected { border-color: #435ebe; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.2rem 0.5rem; margin: 0; }
    div.dt-paging .pagination { margin: 0 !important; flex-wrap: nowrap !important; gap: 0 !important; }
    div.dt-paging .page-item { margin: 0 !important; padding: 0 !important; list-style: none; }
    div.dt-paging .page-item:first-child { margin-right: 0 !important; }
    div.dt-paging .page-item:last-child { margin-left: 0 !important; }
    div.dt-paging .page-link { padding: 0.375rem 0.45rem !important; font-size: 0.875rem !important; margin: 0 !important; min-width: 0 !important; line-height: 1.3 !important; }
    #table1 td { position: relative; min-height: 28px; word-break: break-word; white-space: normal; }
    #table1 td .cell-wrap { display: block; min-height: 24px; width: 100%; cursor: pointer; }
    #table1 .cell-wrap .cell-content { border-bottom: 1px dashed transparent; transition: border-color 0.2s; }
    #table1 .cell-wrap:hover .cell-content { border-bottom-color: #aaa; }
    #table1 .cell-wrap.editing .cell-content { border-bottom-color: transparent; }
    #table1 .cell-wrap .edit-input { width: 100%; border: 1px solid #435ebe; border-radius: 3px; padding: 2px 5px; font-size: inherit; background: #fff; min-width: 60px; box-sizing: border-box; }
    #table1 .edit-select-wrap { position: relative; }
    #table1 .edit-select-wrap .edit-input { width: 100%; box-sizing: border-box; }
    #table1 .edit-options { list-style: none; margin: 2px 0 0 0; padding: 0; max-height: 180px; overflow-y: auto; border: 1px solid #435ebe; border-radius: 3px; background: #fff; }
    #table1 .edit-options .edit-option { padding: 3px 6px; cursor: pointer; font-size: 12px; }
    #table1 .edit-options .edit-option:hover,
    #table1 .edit-options .edit-option.highlight { background: var(--pink-light); }
    #table1 .edit-options .edit-option.selected { background: #d0d9f0; font-weight: bold; }
    .batch-toolbar { display: none; background: #f0f4ff; border: 1px solid #d0d9f0; border-radius: 6px; padding: 12px 16px; margin-bottom: 16px; align-items: center; gap: 12px; }
    .batch-toolbar.show { display: flex; }
    .color-popup { display: none; position: fixed; z-index: 9999; background: #fff; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); padding: 12px; min-width: 240px; }
    .color-popup.show { display: block; }
    .color-popup .preset-colors { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 8px; }
    .color-popup .preset-colors .color-btn { width: 28px; height: 28px; border-radius: 4px; border: 2px solid transparent; cursor: pointer; }
    .color-popup .preset-colors .color-btn:hover, .color-popup .preset-colors .color-btn.active { border-color: #435ebe; }
    .color-popup .custom-color { display: flex; align-items: center; gap: 8px; }
    .color-popup .custom-color input[type="color"] { width: 40px; height: 32px; border: none; padding: 0; cursor: pointer; }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Table</h3>
                <p class="text-subtitle text-muted">Kelola data pokamisu</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('data.index') }}">DataTable</a></li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Data Pokamisu</h4>
                    <a href="{{ route('data.import.form') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Import Excel</a>
                </div>
            </div>
            <div class="card-body">
                <div class="batch-toolbar" id="batchToolbar">
                    <span id="selectedCount" class="fw-bold">0 selected</span>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span class="fw-bold me-1">Warna:</span>
                        <button class="color-btn preset-batch" style="background:#C00000" data-color="#C00000"></button>
                        <button class="color-btn preset-batch" style="background:#FF0000" data-color="#FF0000"></button>
                        <button class="color-btn preset-batch" style="background:#FFC000" data-color="#FFC000"></button>
                        <button class="color-btn preset-batch" style="background:#FFFF00" data-color="#FFFF00"></button>
                        <button class="color-btn preset-batch" style="background:#00FF00" data-color="#00FF00"></button>
                        <button class="color-btn preset-batch" style="background:#00B050" data-color="#00B050"></button>
                        <button class="color-btn preset-batch" style="background:#00B0F0" data-color="#00B0F0"></button>
                        <button class="color-btn preset-batch" style="background:#0000FF" data-color="#0000FF"></button>
                        <button class="color-btn preset-batch" style="background:#7030A0" data-color="#7030A0"></button>
                        <button class="color-btn preset-batch" style="background:#000000" data-color="#000000"></button>
                        <input type="color" id="batchColorPicker" value="#0000FF" title="Custom color">
                        <select id="batchColumnSelect" class="form-select form-select-sm" multiple style="min-width:180px;max-height:150px">
                            <option value="tanggal">Tanggal</option>
                            <option value="no">No</option>
                            <option value="no_instruksi">No Instruksi</option>
                            <option value="tipe_traktor">Tipe Traktor</option>
                            <option value="no_produksi">No Produksi</option>
                            <option value="sign">Sign</option>
                            <option value="permasalahan">Permasalahan</option>
                            <option value="keterangan">Keterangan</option>
                            <option value="jenis_penanganan">Jenis Penanganan</option>
                            <option value="pic_repair">PIC Repair</option>
                            <option value="kategori">Kategori</option>
                            <option value="team">Team</option>
                            <option value="pic">PIC</option>
                        </select>
                        <button id="applyBatchColor" class="btn btn-primary btn-sm">Apply</button>
                        <button id="unselectAll" class="btn btn-secondary btn-sm">Unselect</button>
                    </div>
                </div>
                <div>
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th style="width:40px"><input type="checkbox" id="selectAll"></th>
                                <th>Tanggal</th><th>No</th><th>No Instruksi</th><th>Tipe Traktor</th>
                                <th>No Produksi</th><th>Sign</th><th>Permasalahan</th><th>Keterangan</th>
                                <th>Jenis Penanganan</th><th>PIC Repair</th>
                                <th>Kategori</th><th>Team</th>
                                <th>PIC</th><th style="width:60px">Aksi</th>
                            </tr>
                            <tr class="filters">
                                <th></th>
                                <th><div class="d-flex" style="gap:2px"><input type="date" class="column-filter" id="dateFilter" style="flex:1;min-width:0;min-height:24px"><button type="button" class="btn-date-trigger" title="Pilih Tanggal"><i class="bi bi-calendar3"></i></button></div><div class="color-wrap"><select class="cf-select" data-column="tanggal"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div></th>
                                <th><input type="text" class="column-filter" placeholder="Cari No"><div class="color-wrap"><select class="cf-select" data-column="no"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Instruksi"><div class="color-wrap"><select class="cf-select" data-column="no_instruksi"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Tipe"><div class="color-wrap"><select class="cf-select" data-column="tipe_traktor"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div></th>
                                <th><input type="text" class="column-filter" placeholder="Cari No Produksi"><div class="color-wrap"><select class="cf-select" data-column="no_produksi"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Sign"><div class="color-wrap"><select class="cf-select" data-column="sign"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Permasalahan"><div class="color-wrap"><select class="cf-select" data-column="permasalahan"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Keterangan"><div class="color-wrap"><select class="cf-select" data-column="keterangan"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Penanganan"><div class="color-wrap"><select class="cf-select" data-column="jenis_penanganan"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div></th>
                                <th><input type="text" class="column-filter" placeholder="Cari PIC Repair"><div class="color-wrap"><select class="cf-select" data-column="pic_repair"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div></th>
                                <th>
                                    <input type="text" class="column-filter" data-column="kategori" placeholder="Cari Kategori" list="dl-kategori">
                                    <datalist id="dl-kategori">@foreach($kategoriList as $k)<option value="{{ $k }}">@endforeach</datalist>
                                    <div class="color-wrap"><select class="cf-select" data-column="kategori"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" data-column="team" placeholder="Cari Team" list="dl-team">
                                    <datalist id="dl-team">@foreach($teamList as $t)<option value="{{ $t }}">@endforeach</datalist>
                                    <div class="color-wrap"><select class="cf-select" data-column="team"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" data-column="pic" placeholder="Cari PIC" list="dl-pic">
                                    <datalist id="dl-pic">@foreach($picList as $p)<option value="{{ $p }}">@endforeach</datalist>
                                    <div class="color-wrap"><select class="cf-select" data-column="pic"><option value="">All</option></select><div class="cf-trigger"><span class="dot" style="background:#fff"></span><span class="arrow">&#9660;</span></div><div class="cf-dropdown"><div class="cf-option" data-color=""><span class="dot" style="background:#fff;border:1px solid #ccc"></span>All</div></div></div>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="color-popup" id="colorPopup">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-bold">Change Cell Color</span>
        <button type="button" class="btn-close btn-sm" id="closeColorPopup"></button>
    </div>
    <div class="preset-colors">
        <button class="color-btn" style="background:#C00000" data-color="#C00000"></button>
        <button class="color-btn" style="background:#FF0000" data-color="#FF0000"></button>
        <button class="color-btn" style="background:#FFC000" data-color="#FFC000"></button>
        <button class="color-btn" style="background:#FFFF00" data-color="#FFFF00"></button>
        <button class="color-btn" style="background:#00FF00" data-color="#00FF00"></button>
        <button class="color-btn" style="background:#00B050" data-color="#00B050"></button>
        <button class="color-btn" style="background:#00B0F0" data-color="#00B0F0"></button>
        <button class="color-btn" style="background:#0000FF" data-color="#0000FF"></button>
        <button class="color-btn" style="background:#7030A0" data-color="#7030A0"></button>
        <button class="color-btn" style="background:#000000" data-color="#000000"></button>
    </div>
    <div class="custom-color"><label class="form-label mb-0 me-1">Custom:</label><input type="color" id="customColorPicker" value="#0000FF"><button id="applyCustomColor" class="btn btn-sm btn-primary">Apply</button></div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    let selectedIds = new Set();
    let table, activeCell = null;

    const dataColumns = [
        'tanggal', 'no', 'no_instruksi', 'tipe_traktor', 'no_produksi',
        'sign', 'permasalahan', 'keterangan', 'jenis_penanganan',
        'pic_repair', 'kategori', 'team', 'pic'
    ];

    dataColumns.forEach(function(col) {
        $.get('{{ url('colors') }}/' + col, function(colors) {
            var $wrap = $('.color-wrap').has('.cf-select[data-column="' + col + '"]');
            var $sel = $wrap.find('.cf-select');
            var $dd = $wrap.find('.cf-dropdown');
            colors.forEach(function(c) {
                $sel.append('<option value="' + c + '">' + c + '</option>');
                $dd.append('<div class="cf-option" data-color="' + c + '"><span class="dot" style="background:' + c + '"></span>' + c + '</div>');
            });
        });
    });

    $('#table1 thead tr.filters th').on('click', '.cf-trigger', function(e) {
        e.stopPropagation();
        var $wrap = $(this).closest('.color-wrap');
        $('.color-wrap').not($wrap).removeClass('open');
        $wrap.toggleClass('open');
    });

    $('#table1 thead tr.filters th').on('click', '.cf-option', function() {
        var $wrap = $(this).closest('.color-wrap');
        var color = $(this).data('color') || '';
        $wrap.find('.cf-select').val(color).trigger('change');
        $wrap.removeClass('open');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.color-wrap').length) {
            $('.color-wrap').removeClass('open');
        }
    });

    $(document).on('change', '.cf-select', function() {
        var $wrap = $(this).closest('.color-wrap');
        var val = $(this).val();
        $wrap.find('.cf-trigger .dot').css('background', val || '#fff');
        table.draw();
    });

    table = $('#table1').DataTable({
        processing: true, serverSide: true, autoWidth: false,
        ajax: {
            url: '{{ route('data.table') }}',
            data: function(d) {
                d.colorFilters = {};
                $('.cf-select').each(function() {
                    var val = $(this).val();
                    if (val) d.colorFilters[$(this).data('column')] = val;
                });
            }
        },
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'tanggal_display', name: 'tanggal' },
            { data: 'no', name: 'no' }, { data: 'no_instruksi', name: 'no_instruksi' },
            { data: 'tipe_traktor', name: 'tipe_traktor' }, { data: 'no_produksi', name: 'no_produksi' },
            { data: 'sign', name: 'sign' }, { data: 'permasalahan', name: 'permasalahan' },
            { data: 'keterangan', name: 'keterangan' }, { data: 'jenis_penanganan', name: 'jenis_penanganan' },
            { data: 'pic_repair', name: 'pic_repair' },
            { data: 'kategori', name: 'kategori' }, { data: 'team', name: 'team' }, { data: 'pic', name: 'pic' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[2, 'asc']], pageLength: 100,
        drawCallback: function() {
            $('.row-checkbox').each(function() {
                $(this).prop('checked', selectedIds.has(parseInt($(this).val())));
            });
            var total = table.rows({ filter: 'applied' }).count();
            var checked = $('.row-checkbox:checked').length;
            $('#selectAll').prop('checked', total > 0 && checked === total);
            updateBatchToolbar();
        }
    });

    $('#table1 thead tr.filters').on('click', function(e) { e.stopPropagation(); });

    $(document).on('click', '.btn-date-trigger', function() {
        var inp = $(this).closest('th').find('input[type="date"]')[0];
        if (inp && inp.showPicker) inp.showPicker();
        else if (inp) inp.focus();
    });

    $('.column-filter').on('keyup change', function() {
        table.column($(this).closest('th').index()).search($(this).val()).draw();
    });

    $('#selectAll').on('change', function() {
        var isChecked = $(this).prop('checked');
        selectedIds.clear();
        if (isChecked) {
            table.rows({ search: 'applied' }).every(function() {
                var d = this.data();
                if (d && d.id) selectedIds.add(parseInt(d.id));
            });
        }
        $('.row-checkbox').prop('checked', isChecked);
        updateBatchToolbar();
    });

    $('#unselectAll').on('click', function() {
        selectedIds.clear();
        $('#selectAll').prop('checked', false);
        $('.row-checkbox').prop('checked', false);
        updateBatchToolbar();
    });

    $(document).on('change', '.row-checkbox', function() {
        var id = parseInt($(this).val());
        $(this).prop('checked') ? selectedIds.add(id) : selectedIds.delete(id);
        var total = table.rows({ filter: 'applied' }).count();
        var checked = $('.row-checkbox:checked').length;
        $('#selectAll').prop('checked', total > 0 && checked === total);
        updateBatchToolbar();
    });

    function updateBatchToolbar() {
        var c = selectedIds.size;
        $('#selectedCount').text(c + ' selected');
        $('#batchToolbar').toggleClass('show', c > 0);
    }

    $(document).on('click', '.preset-batch', function() {
        $('.preset-batch').removeClass('selected');
        $(this).addClass('selected');
        $('#batchColorPicker').val($(this).data('color'));
    });

    $('#applyBatchColor').on('click', function() {
        var ids = Array.from(selectedIds);
        if (!ids.length) return;
        var color = $('#batchColorPicker').val();
        var columns = $('#batchColumnSelect').val();
        if (!columns || !columns.length) { Swal.fire('Warning', 'Pilih minimal satu kolom', 'warning'); return; }
        $.ajax({
            url: '{{ route('data.batch.color') }}', method: 'POST',
            data: { _token: '{{ csrf_token() }}', ids: ids, columns: columns, color: color },
            success: function(res) { Swal.fire('Success', 'Warna diupdate untuk ' + res.updated + ' baris', 'success'); table.ajax.reload(null, false); },
            error: function() { Swal.fire('Error', 'Gagal update warna', 'error'); }
        });
    });

    function getCellData(td) {
        var $span = $(td).find('.cell-content');
        if (!$span.length) return null;
        return {
            id: $span.data('id'), column: $span.data('column'),
            color: $span.data('color') || '#000000',
            text: $span.text().trim(), $span: $span,
            isSelect: $span.hasClass('is-select'),
            options: ($span.data('options') || '').split(',')
        };
    }

    $('#table1 tbody').on('contextmenu', 'td:not(:first-child):not(:last-child)', function(e) {
        e.preventDefault();
        if (!$(this).find('.cell-content').length) return;
        var cellData = getCellData(this);
        if (!cellData) return;
        showColorPicker(cellData, e);
    });

    $('#table1 tbody').on('dblclick', 'td:not(:first-child):not(:last-child)', function(e) {
        if (!$(this).find('.cell-content').length) return;
        var cellData = getCellData(this);
        if (!cellData) return;
        if (cellData.isSelect) { enableSelectEdit(cellData); }
        else { enableInlineEdit(cellData); }
    });

    function showColorPicker(cellData, event) {
        var $popup = $('#colorPopup');
        activeCell = cellData;
        var $td = $(event.currentTarget);
        var off = $td.offset(), pw = 260, ph = 180;
        var l = off.left - pw/2 + $td.outerWidth()/2;
        if (l < 10) l = 10; if (l + pw > $(window).width()-10) l = $(window).width()-pw-10;
        var scrollTop = $(window).scrollTop();
        var viewH = $(window).height();
        var tdTopVp = off.top - scrollTop;
        var tdBottomVp = tdTopVp + $td.outerHeight();
        var spaceBelow = viewH - tdBottomVp;
        var spaceAbove = tdTopVp;
        var t;
        if (spaceBelow >= ph + 10) {
            t = tdBottomVp + 8;
        } else if (spaceAbove >= ph + 10) {
            t = tdTopVp - ph - 8;
        } else {
            t = 10;
        }
        $('#customColorPicker').val(cellData.color);
        $('.color-btn').removeClass('active');
        $('.color-btn[data-color="' + cellData.color + '"]').addClass('active');
        $popup.css({ left: l+'px', top: t+'px' }).addClass('show');
    }

    $('#colorPopup .color-btn').on('click', function() { applyColorToActiveCell($(this).data('color')); $('#colorPopup').removeClass('show'); });
    $('#applyCustomColor').on('click', function() { applyColorToActiveCell($('#customColorPicker').val()); $('#colorPopup').removeClass('show'); });
    $('#closeColorPopup').on('click', function() { $('#colorPopup').removeClass('show'); });

    function applyColorToActiveCell(color) {
        if (!activeCell) return;
        activeCell.$span.css('color', color).data('color', color);
        $.ajax({
            url: '{{ route('data.cell.color') }}', method: 'POST',
            data: { _token: '{{ csrf_token() }}', id: activeCell.id, column: activeCell.column, color: color },
            error: function() { Swal.fire('Error', 'Gagal update warna', 'error'); }
        });
        activeCell = null;
    }

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#colorPopup').length && !$(e.target).closest('td').length) $('#colorPopup').removeClass('show');
    });

    function enableInlineEdit(cellData) {
        var $td = cellData.$span.closest('td');
        $td.find('.cell-wrap').addClass('editing');
        $td.find('.cell-content').html('<input type="text" class="edit-input" value="' + $('<span>').text(cellData.text).html() + '" style="color:' + cellData.color + '">');
        var $input = $td.find('.edit-input').focus().select();
        $input.on('blur', function() { saveValue($td, cellData.id, cellData.column, $input.val()); });
        $input.on('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); $input.trigger('blur'); }
            else if (e.key === 'Escape') { $td.find('.cell-wrap').removeClass('editing'); $td.find('.cell-content').text(cellData.text); }
        });
    }

    function enableSelectEdit(cellData) {
        var $td = cellData.$span.closest('td');
        $td.find('.cell-wrap').addClass('editing');
        var currentVal = $('<span>').text(cellData.text).html();
        var optsHtml = cellData.options.map(function(o) {
            var display = o === '' ? '(kosong)' : $('<span>').text(o).html();
            var sel = o === cellData.text ? ' class="selected"' : '';
            var val = $('<span>').text(o).html();
            return '<li class="edit-option"' + sel + ' data-value="' + val + '">' + display + '</li>';
        }).join('');
        $td.find('.cell-content').html(
            '<div class="edit-select-wrap">' +
                '<input type="text" class="edit-input" placeholder="Cari..." value="' + currentVal + '" style="color:' + cellData.color + '">' +
                '<ul class="edit-options">' + optsHtml + '</ul>' +
            '</div>'
        );
        var $input = $td.find('.edit-input');
        var $options = $td.find('.edit-option');
        $input.on('input', function() {
            var q = $(this).val().toLowerCase();
            $options.each(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(q) >= 0);
            });
        });
        $options.on('click', function() {
            saveValue($td, cellData.id, cellData.column, $(this).data('value'));
        });
        $input.on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                var $highlighted = $options.filter('.highlight').first();
                if ($highlighted.length) {
                    saveValue($td, cellData.id, cellData.column, $highlighted.data('value'));
                } else {
                    saveValue($td, cellData.id, cellData.column, $input.val());
                }
            } else if (e.key === 'Escape') {
                $td.find('.cell-wrap').removeClass('editing');
                $td.find('.cell-content').text(cellData.text);
            } else if (e.key === 'ArrowDown') {
                e.preventDefault();
                var $vis = $options.filter(':visible');
                var idx = $vis.index($options.filter('.highlight'));
                $options.removeClass('highlight');
                if (idx < $vis.length - 1) $vis.eq(idx + 1).addClass('highlight');
                else $vis.first().addClass('highlight');
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                var $vis = $options.filter(':visible');
                var idx = $vis.index($options.filter('.highlight'));
                $options.removeClass('highlight');
                if (idx > 0) $vis.eq(idx - 1).addClass('highlight');
                else $vis.last().addClass('highlight');
            }
        });
        $input.focus().select();
    }

    function saveValue($td, id, column, newValue) {
        $td.find('.cell-wrap').removeClass('editing');
        $.ajax({
            url: '{{ route('data.cell.value') }}', method: 'POST',
            data: { _token: '{{ csrf_token() }}', id: id, column: column, value: newValue },
            success: function() { $td.find('.cell-content').text(newValue); },
            error: function() { Swal.fire('Error', 'Gagal update nilai', 'error'); $td.find('.cell-content').text(newValue); }
        });
    }

    $(document).on('click', '.delete-row', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus data ini?', text: 'Data yang dihapus tidak bisa dikembalikan!', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal'
        }).then(function(r) {
            if (r.isConfirmed) {
                $.ajax({
                    url: '{{ url('delete') }}/' + id, method: 'POST',
                    data: { _token: '{{ csrf_token() }}', _method: 'POST' },
                    success: function() { Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success'); selectedIds.delete(parseInt(id)); table.ajax.reload(null, false); },
                    error: function() { Swal.fire('Error', 'Gagal menghapus data', 'error'); }
                });
            }
        });
    });
});
</script>
@endpush
