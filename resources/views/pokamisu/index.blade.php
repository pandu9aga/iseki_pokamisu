@extends('layouts.master')

@section('title', 'Data Table')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendors/choices.js/choices.min.css') }}">
<style>
    #table1 { width: 100%; }
    #table1 thead tr.filters th { vertical-align: top; padding: 6px 4px; }
    #table1 thead tr.filters th input,
    #table1 thead tr.filters th .choices { width: 100% !important; margin-bottom: 2px; }
    #table1 thead tr.filters th .choices__inner {
        min-height: 22px; font-size: 11px; padding: 1px 4px;
        background: #fff; border-radius: 3px;
    }
    #table1 thead tr.filters th .choices__inner .choices__input { font-size: 11px; }
    #table1 thead tr.filters th select { width: 100%; font-size: 11px; padding: 2px 3px; margin-top: 1px; border: 1px solid #ddd; border-radius: 3px; }
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
    #table1 .edit-options .edit-option.highlight { background: #eef1ff; }
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
                                <th><input type="date" class="column-filter" placeholder="Cari Tgl"><select class="color-filter dt-col-filter" data-column="tanggal"><option value="">All Colors</option></select></th>
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
                                    <input type="text" class="column-filter" data-column="kategori" placeholder="Cari Kategori" list="dl-kategori">
                                    <datalist id="dl-kategori">@foreach($kategoriList as $k)<option value="{{ $k }}">@endforeach</datalist>
                                    <select class="color-filter dt-col-filter" data-column="kategori"><option value="">All Colors</option></select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" data-column="team" placeholder="Cari Team" list="dl-team">
                                    <datalist id="dl-team">@foreach($teamList as $t)<option value="{{ $t }}">@endforeach</datalist>
                                    <select class="color-filter dt-col-filter" data-column="team"><option value="">All Colors</option></select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" data-column="pic" placeholder="Cari PIC" list="dl-pic">
                                    <datalist id="dl-pic">@foreach($picList as $p)<option value="{{ $p }}">@endforeach</datalist>
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
            var $sel = $('.color-filter[data-column="' + col + '"]');
            colors.forEach(function(c) { $sel.append('<option value="' + c + '">' + c + '</option>'); });
        });
    });

    table = $('#table1').DataTable({
        processing: true, serverSide: true, autoWidth: false,
        ajax: {
            url: '{{ route('data.table') }}',
            data: function(d) {
                d.colorFilters = {};
                $('.color-filter').each(function() {
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

    $('.column-filter').on('keyup change', function() {
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
        var tdBottom = off.top + $td.outerHeight();
        var spaceBelow = (scrollTop + viewH) - tdBottom;
        var spaceAbove = off.top - scrollTop;
        var t;
        if (spaceBelow >= ph + 10) {
            t = tdBottom + 8;
        } else if (spaceAbove >= ph + 10) {
            t = off.top - ph - 8;
        } else {
            t = scrollTop + 10;
        }
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
