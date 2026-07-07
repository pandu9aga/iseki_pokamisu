@extends('layouts.master')

@section('title', 'Data Table')

@push('styles')
<style>
    .color-swatch {
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 3px;
        border: 1px solid #ddd;
        cursor: pointer;
        vertical-align: middle;
        margin-left: 4px;
    }
    .color-swatch:hover {
        transform: scale(1.2);
    }
    #table1 thead tr.filters th {
        vertical-align: top;
        padding: 6px 4px;
    }
    #table1 thead tr.filters th input {
        width: 100%;
        font-size: 12px;
        padding: 3px 5px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }
    #table1 thead tr.filters th select {
        width: 100%;
        font-size: 11px;
        padding: 2px 3px;
        margin-top: 2px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }
    #table1 .cell-content {
        display: inline-block;
        min-height: 20px;
        cursor: pointer;
        border-bottom: 1px dashed transparent;
        transition: border-color 0.2s;
    }
    #table1 .cell-content:hover {
        border-bottom-color: #aaa;
    }
    #table1 .cell-content.editing {
        border-bottom-color: transparent;
    }
    #table1 .cell-content .edit-input {
        width: 100%;
        border: 1px solid #435ebe;
        border-radius: 3px;
        padding: 2px 5px;
        font-size: inherit;
        background: #fff;
        min-width: 60px;
    }
    .batch-toolbar {
        display: none;
        background: #f0f4ff;
        border: 1px solid #d0d9f0;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 16px;
        align-items: center;
        gap: 12px;
    }
    .batch-toolbar.show {
        display: flex;
    }
    .color-popup {
        display: none;
        position: fixed;
        z-index: 9999;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        padding: 12px;
        min-width: 240px;
    }
    .color-popup.show {
        display: block;
    }
    .color-popup .preset-colors {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 8px;
    }
    .color-popup .preset-colors .color-btn {
        width: 28px;
        height: 28px;
        border-radius: 4px;
        border: 2px solid transparent;
        cursor: pointer;
    }
    .color-popup .preset-colors .color-btn:hover,
    .color-popup .preset-colors .color-btn.active {
        border-color: #435ebe;
    }
    .color-popup .custom-color {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .color-popup .custom-color input[type="color"] {
        width: 40px;
        height: 32px;
        border: none;
        padding: 0;
        cursor: pointer;
    }
    .dt-col-filter {
        margin-top: 2px;
        width: 100%;
    }
    .dataTables_wrapper .dt-buttons {
        margin-bottom: 8px;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Table</h3>
                <p class="text-subtitle text-muted">Kelola data import dengan filter, edit, dan color</p>
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
                    <h4 class="card-title mb-0">Data Import</h4>
                    <a href="{{ route('data.import.form') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Import Excel
                    </a>
                </div>
            </div>
            <div class="card-body">

                <div class="batch-toolbar" id="batchToolbar">
                    <span id="selectedCount" class="fw-bold">0 selected</span>
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label mb-0">Change Color:</label>
                        <input type="color" id="batchColorPicker" value="#ff0000">
                        <select id="batchColumnSelect" class="form-select form-select-sm" multiple style="min-width:160px;max-height:120px">
                            <option value="no">No</option>
                            <option value="instruksi">Instruksi</option>
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
                                <th>No</th>
                                <th>Instruksi</th>
                                <th>Tipe Traktor</th>
                                <th>No Produksi</th>
                                <th>Sign</th>
                                <th>Permasalahan</th>
                                <th>Keterangan</th>
                                <th>Jenis Penanganan</th>
                                <th>PIC Repair</th>
                                <th>Kategori</th>
                                <th>Team</th>
                                <th>PIC</th>
                                <th style="width:60px">Aksi</th>
                            </tr>
                            <tr class="filters">
                                <th></th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari No">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="no">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari Instruksi">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="instruksi">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari Tipe">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="tipe_traktor">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari No Produksi">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="no_produksi">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari Sign">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="sign">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari Permasalahan">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="permasalahan">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari Keterangan">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="keterangan">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari Penanganan">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="jenis_penanganan">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari PIC Repair">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="pic_repair">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari Kategori">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="kategori">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari Team">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="team">
                                        <option value="">All Colors</option>
                                    </select>
                                </th>
                                <th>
                                    <input type="text" class="column-filter" placeholder="Cari PIC">
                                    <select class="color-filter form-select form-select-sm dt-col-filter" data-column="pic">
                                        <option value="">All Colors</option>
                                    </select>
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
    <div class="custom-color">
        <label class="form-label mb-0 me-1">Custom:</label>
        <input type="color" id="customColorPicker" value="#000000">
        <button id="applyCustomColor" class="btn btn-sm btn-primary">Apply</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    let selectedIds = new Set();
    let table;
    let activeCell = null;
    let clickTimer = null;

    const dataColumns = [
        'no', 'instruksi', 'tipe_traktor', 'no_produksi',
        'sign', 'permasalahan', 'keterangan', 'jenis_penanganan',
        'pic_repair', 'kategori', 'team', 'pic'
    ];

    dataColumns.forEach(function(col) {
        $.get('{{ url('colors') }}/' + col, function(colors) {
            var $select = $('.color-filter[data-column="' + col + '"]');
            colors.forEach(function(c) {
                $select.append('<option value="' + c + '" style="color:' + c + '">' + c + '</option>');
            });
        });
    });

    table = $('#table1').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('data.table') }}',
            data: function(d) {
                d.colorFilters = {};
                $('.color-filter').each(function() {
                    var val = $(this).val();
                    if (val) {
                        d.colorFilters[$(this).data('column')] = val;
                    }
                });
            }
        },
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'no', name: 'no' },
            { data: 'instruksi', name: 'instruksi' },
            { data: 'tipe_traktor', name: 'tipe_traktor' },
            { data: 'no_produksi', name: 'no_produksi' },
            { data: 'sign', name: 'sign' },
            { data: 'permasalahan', name: 'permasalahan' },
            { data: 'keterangan', name: 'keterangan' },
            { data: 'jenis_penanganan', name: 'jenis_penanganan' },
            { data: 'pic_repair', name: 'pic_repair' },
            { data: 'kategori', name: 'kategori' },
            { data: 'team', name: 'team' },
            { data: 'pic', name: 'pic' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        drawCallback: function() {
            $('.row-checkbox').each(function() {
                var id = parseInt($(this).val());
                $(this).prop('checked', selectedIds.has(id));
            });
            var total = table.rows({ filter: 'applied' }).count();
            var checked = $('.row-checkbox:checked').length;
            $('#selectAll').prop('checked', total > 0 && checked === total);
            updateBatchToolbar();
            bindCellEvents();
        }
    });

    $('.column-filter').on('keyup change', function() {
        var colIndex = $(this).closest('th').index();
        table.column(colIndex).search($(this).val()).draw();
    });

    $('.color-filter').on('change', function() {
        table.draw();
    });

    $('#selectAll').on('change', function() {
        var isChecked = $(this).prop('checked');
        if (isChecked) {
            table.rows().every(function() {
                var rowData = this.data();
                if (rowData && rowData.id) {
                    selectedIds.add(rowData.id);
                }
            });
        } else {
            selectedIds.clear();
        }
        $('.row-checkbox').prop('checked', isChecked);
        updateBatchToolbar();
    });

    $(document).on('change', '.row-checkbox', function() {
        var id = parseInt($(this).val());
        if ($(this).prop('checked')) {
            selectedIds.add(id);
        } else {
            selectedIds.delete(id);
        }
        var totalRows = table.rows().count();
        var checkedCount = $('.row-checkbox:checked').length;
        $('#selectAll').prop('checked', checkedCount === totalRows && totalRows > 0);
        updateBatchToolbar();
    });

    function updateBatchToolbar() {
        var count = selectedIds.size;
        $('#selectedCount').text(count + ' selected');
        if (count > 0) {
            $('#batchToolbar').addClass('show');
        } else {
            $('#batchToolbar').removeClass('show');
        }
    }

    $('#applyBatchColor').on('click', function() {
        var ids = Array.from(selectedIds);
        if (ids.length === 0) return;

        var color = $('#batchColorPicker').val();
        var columns = $('#batchColumnSelect').val();
        if (!columns || columns.length === 0) {
            Swal.fire('Warning', 'Pilih minimal satu kolom', 'warning');
            return;
        }

        $.ajax({
            url: '{{ route('data.batch.color') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: ids,
                columns: columns,
                color: color
            },
            success: function(res) {
                Swal.fire('Success', 'Warna berhasil diupdate untuk ' + res.updated + ' baris', 'success');
                table.ajax.reload(null, false);
            },
            error: function() {
                Swal.fire('Error', 'Gagal update warna', 'error');
            }
        });
    });

    function bindCellEvents() {
        $('.cell-content').off('click dblclick').on('click', function(e) {
            var $this = $(this);
            if ($this.hasClass('editing')) return;

            if (clickTimer) {
                clearTimeout(clickTimer);
                clickTimer = null;
                return;
            }

            clickTimer = setTimeout(function() {
                clickTimer = null;
                showColorPicker($this, e);
            }, 200);
        }).on('dblclick', function(e) {
            if (clickTimer) {
                clearTimeout(clickTimer);
                clickTimer = null;
            }
            var $this = $(this);
            if ($this.hasClass('editing')) return;
            enableInlineEdit($this);
        });
    }

    function showColorPicker($cell, event) {
        var $popup = $('#colorPopup');
        activeCell = $cell;

        var offset = $cell.offset();
        var popupWidth = 260;
        var left = offset.left - popupWidth / 2 + $cell.outerWidth() / 2;
        var top = offset.top + $cell.outerHeight() + 8;

        if (left < 10) left = 10;
        if (left + popupWidth > $(window).width() - 10) {
            left = $(window).width() - popupWidth - 10;
        }

        var currentColor = $cell.data('color') || '#000000';
        $('#customColorPicker').val(currentColor);
        $('.color-btn').removeClass('active');
        $('.color-btn[data-color="' + currentColor + '"]').addClass('active');

        $popup.css({ left: left + 'px', top: top + 'px' }).addClass('show');
    }

    $('#colorPopup .color-btn').on('click', function() {
        var color = $(this).data('color');
        applyColorToActiveCell(color);
        $('#colorPopup').removeClass('show');
    });

    $('#applyCustomColor').on('click', function() {
        var color = $('#customColorPicker').val();
        applyColorToActiveCell(color);
        $('#colorPopup').removeClass('show');
    });

    function applyColorToActiveCell(color) {
        if (!activeCell) return;
        var id = activeCell.data('id');
        var column = activeCell.data('column');

        activeCell.css('color', color);
        activeCell.data('color', color);

        $.ajax({
            url: '{{ route('data.cell.color') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                column: column,
                color: color
            },
            error: function() {
                Swal.fire('Error', 'Gagal update warna', 'error');
            }
        });

        activeCell = null;
    }

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#colorPopup').length && !$(e.target).closest('.cell-content').length) {
            $('#colorPopup').removeClass('show');
        }
    });

    function enableInlineEdit($cell) {
        var currentText = $cell.text().trim();
        var currentColor = $cell.data('color') || '#000000';
        var id = $cell.data('id');
        var column = $cell.data('column');

        $cell.addClass('editing');
        $cell.html('<input type="text" class="edit-input" value="' + $('<span>').text(currentText).html() + '" style="color:' + currentColor + '">');
        var $input = $cell.find('.edit-input');
        $input.focus().select();

        var saveTimer;

        $input.on('blur', function() {
            saveValue($cell, id, column, $input.val());
        });

        $input.on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $input.trigger('blur');
            } else if (e.key === 'Escape') {
                $cell.removeClass('editing');
                $cell.text(currentText);
                bindCellEvents();
            }
        });
    }

    function saveValue($cell, id, column, newValue) {
        $cell.removeClass('editing');
        var color = $cell.data('color') || '#000000';

        $.ajax({
            url: '{{ route('data.cell.value') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                column: column,
                value: newValue
            },
            success: function() {
                $cell.text(newValue);
                $cell.css('color', color);
                bindCellEvents();
            },
            error: function() {
                Swal.fire('Error', 'Gagal update nilai', 'error');
                $cell.text(newValue);
                $cell.css('color', color);
                bindCellEvents();
            }
        });
    }

    $(document).on('click', '.delete-row', function() {
        var id = $(this).data('id');
        var $btn = $(this);

        Swal.fire({
            title: 'Hapus data ini?',
            text: 'Data yang dihapus tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url('delete') }}/' + id,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function() {
                        Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                        table.ajax.reload(null, false);
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus data', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
