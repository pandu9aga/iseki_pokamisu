@extends('layouts.master')

@section('title', 'Data Table')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}">
<style>
    #table1 thead tr.filters th { vertical-align: top; padding: 6px 4px; }
    #table1 thead tr.filters th input,
    #table1 thead tr.filters th .choices { width: 100% !important; margin-bottom: 2px; }
    #table1 thead tr.filters th .choices__inner {
        min-height: 22px; font-size: 11px; padding: 1px 4px;
        background: #fff; border-radius: 3px;
    }
    #table1 thead tr.filters th .choices__inner .choices__input { font-size: 11px; }
    #table1 thead tr.filters th select { width: 100%; font-size: 11px; padding: 2px 3px; margin-top: 1px; border: 1px solid #ddd; border-radius: 3px; }
    #table1 td { position: relative; min-height: 28px; }
    #table1 .cell-wrap { display: block; min-height: 24px; width: 100%; cursor: pointer; }
    #table1 .cell-wrap .cell-content { border-bottom: 1px dashed transparent; transition: border-color 0.2s; }
    #table1 .cell-wrap:hover .cell-content { border-bottom-color: #aaa; }
    #table1 .cell-wrap.editing .cell-content { border-bottom-color: transparent; }
    #table1 .cell-wrap .edit-input { width: 100%; border: 1px solid #435ebe; border-radius: 3px; padding: 2px 5px; font-size: inherit; background: #fff; min-width: 60px; }
    #table1 .cell-wrap .edit-select { width: 100%; border: 1px solid #435ebe; border-radius: 3px; padding: 2px; font-size: inherit; background: #fff; min-width: 60px; }
    .batch-toolbar { display: none; background: #f0f4ff; border: 1px solid #d0d9f0; border-radius: 6px; padding: 12px 16px; margin-bottom: 16px; align-items: center; gap: 12px; }
    .batch-toolbar.show { display: flex; }
    .color-popup { display: none; position: fixed; z-index: 9999; background: #fff; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); padding: 12px; min-width: 240px; }
    .color-popup.show { display: block; }
    .color-popup .preset-colors { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 8px; }
    .color-popup .preset-colors .color-btn { width: 28px; height: 28px; border-radius: 4px; border: 2px solid transparent; cursor: pointer; }
    .color-popup .preset-colors .color-btn:hover, .color-popup .preset-colors .color-btn.active { border-color: #435ebe; }
    .color-popup .custom-color { display: flex; align-items: center; gap: 8px; }
    .color-popup .custom-color input[type="color"] { width: 40px; height: 32px; border: none; padding: 0; cursor: pointer; }
    .dt-col-filter { margin-top: 2px; width: 100%; }
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
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label mb-0">Change Color:</label>
                        <input type="color" id="batchColorPicker" value="#ff0000">
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
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th style="width:40px"><input type="checkbox" id="selectAll"></th>
                                <th>Tanggal</th><th>No</th><th>No Instruksi</th><th>Tipe Traktor</th>
                                <th>No Produksi</th><th>Sign</th><th>Permasalahan</th><th>Keterangan</th>
                                <th>Jenis Penanganan</th><th>PIC Repair</th>
                                <th style="min-width:100px">Kategori</th><th style="min-width:90px">Team</th>
                                <th style="min-width:120px">PIC</th><th style="width:60px">Aksi</th>
                            </tr>
                            <tr class="filters">
                                <th></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Tgl"><select class="color-filter dt-col-filter" data-column="tanggal"><option value="">All Colors</option></select></th>
                                <th><input type="text" class="column-filter" placeholder="Cari No"><select class="color-filter dt-col-filter" data-column="no"><option value="">All Colors</option></select></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Instruksi"><select class="color-filter dt-col-filter" data-column="no_instruksi"><option value="">All Colors</option></select></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Tipe"><select class="color-filter dt-col-filter" data-column="tipe_traktor"><option value="">All Colors</option></select></th>
                                <th><input type="text" class="column-filter" placeholder="Cari No Produksi"><select class="color-filter dt-col-filter" data-column="no_produksi"><option value="">All Colors</option></select></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Sign"><select class="color-filter dt-col-filter" data-column="sign"><option value="">All Colors</option></select></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Permasalahan"><select class="color-filter dt-col-filter" data-column="permasalahan"><option value="">All Colors</option></select></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Keterangan"><select class="color-filter dt-col-filter" data-column="keterangan"><option value="">All Colors</option></select></th>
                                <th><input type="text" class="column-filter" placeholder="Cari Penanganan"><select class="color-filter dt-col-filter" data-column="jenis_penanganan"><option value="">All Colors</option></select></th>
                                <th><input type="text" class="column-filter" placeholder="Cari PIC Repair"><select class="color-filter dt-col-filter" data-column="pic_repair"><option value="">All Colors</option></select></th>
                                <th>
                                    <select class="column-filter-select" data-column="kategori" data-placeholder="Cari Kategori"><option value="">All</option>
                                    @foreach($kategoriList as $k)<option value="{{ $k }}">{{ $k }}</option>@endforeach</select>
                                    <select class="color-filter dt-col-filter" data-column="kategori"><option value="">All Colors</option></select>
                                </th>
                                <th>
                                    <select class="column-filter-select" data-column="team" data-placeholder="Cari Team"><option value="">All</option>
                                    @foreach($teamList as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach</select>
                                    <select class="color-filter dt-col-filter" data-column="team"><option value="">All Colors</option></select>
                                </th>
                                <th>
                                    <select class="column-filter-select" data-column="pic" data-placeholder="Cari PIC"><option value="">All</option>
                                    @foreach($picList as $p)<option value="{{ $p }}">{{ $p }}</option>@endforeach</select>
                                    <select class="color-filter dt-col-filter" data-column="pic"><option value="">All Colors</option></select>
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
    <div class="fw-bold mb-2">Change Cell Color</div>
    <div class="preset-colors">
        <button class="color-btn" style="background:#000000" data-color="#000000"></button>
        <button class="color-btn" style="background:#ff0000" data-color="#ff0000"></button>
        <button class="color-btn" style="background:#ff6600" data-color="#ff6600"></button>
        <button class="color-btn" style="background:#ffcc00" data-color="#ffcc00"></button>
        <button class="color-btn" style="background:#00cc00" data-color="#00cc00"></button>
        <button class="color-btn" style="background:#0066ff" data-color="#0066ff"></button>
        <button class="color-btn" style="background:#6600cc" data-color="#6600cc"></button>
        <button class="color-btn" style="background:#cc0066" data-color="#cc0066"></button>
        <button class="color-btn" style="background:#996633" data-color="#996633"></button>
        <button class="color-btn" style="background:#666666" data-color="#666666"></button>
        <button class="color-btn" style="background:#00cccc" data-color="#00cccc"></button>
        <button class="color-btn" style="background:#ff66b2" data-color="#ff66b2"></button>
    </div>
    <div class="custom-color"><label class="form-label mb-0 me-1">Custom:</label><input type="color" id="customColorPicker" value="#000000"><button id="applyCustomColor" class="btn btn-sm btn-primary">Apply</button></div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendors/choices.js/choices.min.js') }}"></script>
<script>
$(function() {
    let selectedIds = new Set();
    let table, activeCell = null, clickTimer = null;
    let choiceInstances = {};

    const dataColumns = [
        'tanggal', 'no', 'no_instruksi', 'tipe_traktor', 'no_produksi',
        'sign', 'permasalahan', 'keterangan', 'jenis_penanganan',
        'pic_repair', 'kategori', 'team', 'pic'
    ];

    dataColumns.forEach(function(col) {
        $.get('{{ url('colors') }}/' + col, function(colors) {
            var $sel = $('.color-filter[data-column="' + col + '"]');
            colors.forEach(function(c) { $sel.append('<option value="' + c + '">' + c + '</option>'); });
        });
    });

    $('.column-filter-select').each(function() {
        var $this = $(this);
        var ch = new Choices(this, {
            searchEnabled: true, searchPlaceholderValue: $this.data('placeholder') || 'Cari...',
            itemSelectText: '', placeholder: true, allowHTML: false
        });
        choiceInstances[$this.data('column')] = ch;
    });

    table = $('#table1').DataTable({
        processing: true, serverSide: true,
        ajax: {
            url: '{{ route('data.table') }}',
            data: function(d) {
                d.colorFilters = {};
                $('.color-filter').each(function() {
                    var val = $(this).val();
                    if (val) d.colorFilters[$(this).data('column')] = val;
                });
                d.selectFilters = {};
                $('.column-filter-select').each(function() {
                    var val = $(this).val();
                    if (val) d.selectFilters[$(this).data('column')] = val;
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
        order: [[2, 'asc']], pageLength: 25,
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

    $('.column-filter').on('keyup change', function() {
        table.column($(this).closest('th').index()).search($(this).val()).draw();
    });

    $('.column-filter-select').on('change', function() {
        table.column($(this).closest('th').index()).search($(this).val()).draw();
    });

    $('.color-filter').on('change', function() { table.draw(); });

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

    $('#table1 tbody').on('click', 'td:not(:first-child):not(:last-child)', function(e) {
        if (!$(this).find('.cell-content').length) return;
        if (clickTimer) { clearTimeout(clickTimer); clickTimer = null; return; }
        var cellData = getCellData(this);
        if (!cellData) return;
        clickTimer = setTimeout(function() { clickTimer = null; showColorPicker(cellData, e); }, 200);
    });

    $('#table1 tbody').on('dblclick', 'td:not(:first-child):not(:last-child)', function(e) {
        if (clickTimer) { clearTimeout(clickTimer); clickTimer = null; }
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
        var off = $td.offset(), pw = 260;
        var l = off.left - pw/2 + $td.outerWidth()/2, t = off.top + $td.outerHeight() + 8;
        if (l < 10) l = 10; if (l + pw > $(window).width()-10) l = $(window).width()-pw-10;
        $('#customColorPicker').val(cellData.color);
        $('.color-btn').removeClass('active');
        $('.color-btn[data-color="' + cellData.color + '"]').addClass('active');
        $popup.css({ left: l+'px', top: t+'px' }).addClass('show');
    }

    $('#colorPopup .color-btn').on('click', function() { applyColorToActiveCell($(this).data('color')); $('#colorPopup').removeClass('show'); });
    $('#applyCustomColor').on('click', function() { applyColorToActiveCell($('#customColorPicker').val()); $('#colorPopup').removeClass('show'); });

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
        var opts = cellData.options.map(function(o) {
            var sel = o === cellData.text ? 'selected' : '';
            return '<option value="' + o + '" ' + sel + '>' + o + '</option>';
        }).join('');
        $td.find('.cell-content').html('<select class="edit-select">' + opts + '</select>');
        var $sel = $td.find('.edit-select').focus();
        $sel.on('change', function() { saveValue($td, cellData.id, cellData.column, $(this).val()); });
        $sel.on('blur', function() { saveValue($td, cellData.id, cellData.column, $(this).val()); });
        $sel.on('keydown', function(e) {
            if (e.key === 'Escape') { $td.find('.cell-wrap').removeClass('editing'); $td.find('.cell-content').text(cellData.text); }
        });
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
