<x-default-layout>
    @section('title', 'Progress Kerja')

    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark d-flex align-items-center">
                <i class="bi bi-graph-up me-2"></i>
                <h3 class="mb-0 fw-bold text-white">Progress Kerja</h3>
            </div>
            <div class="card-body">
                @php
                    $currentMonth = now()->format('m');
                    $currentYear = now()->year;
                @endphp

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Filter Bulan</label>
                        <select id="filterBulan" class="form-select">
                            <option value="">Semua Bulan</option>
                            @for ($m = 1; $m <= 12; $m++)
                                @php $val = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                                <option value="{{ $val }}" {{ $val == $currentMonth ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Filter Tahun</label>
                        <select id="filterTahun" class="form-select">
                            <option value="">Semua Tahun</option>
                            @for ($y = now()->year; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="progressTable" class="table table-bordered table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>PIC</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Lokasi</th>
                                <th>Uraian Masalah</th>
                                <th>Rencana</th>
                                <th>Selesai</th>
                                <th>Tindakan</th>
                                <th>Status</th>
                                <th>Bukti</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .highlight-title {
            background-color: #f8f9fa;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-block;
            font-weight: bold;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }
        #tableLaporanHarian tbody tr:hover {
            background-color: #f2f2f2;
            cursor: pointer;
        }
        .custom-button {
            display: block;
            text-align: center;
        }

        .dataTables_wrapper .dataTable {
            border-collapse: collapse;
            width: 100%;
            font-size: 0.9rem;
            color: #333;
        }

        .dataTables_wrapper .dataTable thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: bold;
        }

        .dataTables_wrapper .dataTable tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .custom-button {
            font-size: 0.875rem;
            padding: 6px 12px;
            border-radius: 4px;
        }

        .custom-button:hover {
            color: #fff;
            background-color: #0056b3;
            border-color: #0056b3;
        }

        @media (max-width: 768px) {
            .dataTables_wrapper .dataTable {
                font-size: 0.8rem;
            }

            .custom-buttons-container {
                justify-content: center;
                margin-bottom: 10px;
            }

            .custom-button {
                margin-bottom: 5px;
            }
        }
        .table-responsive {
            position: relative;
            overflow: visible;
        }

        .relative .dropdown-menu {
            position: absolute !important;
            transform: translate3d(0, 38px, 0) !important;
            z-index: 1050;
            will-change: transform;
        }
        #previewImage.zoomed {
            transform: scale(2);
            cursor: zoom-out;
            transition: transform 0.3s ease;
        }

        .modal-content {
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 1.25rem;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.2rem;
        }

        .modal-footer {
            border-top: 1px solid #e0e0e0;
            padding: 1rem 1.25rem;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .modal-body label {
            font-weight: 500;
            margin-bottom: 0.3rem;
        }

        .modal-body input,
        .modal-body select {
            font-size: 0.95rem;
            padding: 0.45rem 0.75rem;
        }

        .modal-body h6 {
            margin-top: 1rem;
            font-weight: 600;
            color: #495057;
        }

        .modal-body small.text-muted {
            font-size: 0.8rem;
            display: block;
            margin-top: 0.25rem;
            margin-left: 2px;
        }

        .btn-xs {
            font-size: 0.75rem;
            padding: 4px 10px;
            line-height: 1.3;
            min-width: 90px;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-xs i {
            font-size: 0.8rem;
        }

        .btn-xs:hover {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
            transition: all 0.2s ease;
        }

        @media (max-width: 576px) {
            .opsi-buttons {
                flex-direction: column;
                align-items: stretch;
            }
        }

        fieldset {
            border: 1px dashed #999 !important;
            padding-top: 1.5rem;
            margin-top: 1rem;
            position: relative;
        }

        legend {
            font-size: 1rem;
            font-weight: 600;
            padding: 0 10px;
            width: auto;
            color: #000000;
        }

        #previewImageModal.zoomed {
            transform: scale(2);
            cursor: zoom-out;
            transition: transform 0.3s ease;
        }

        fieldset {
            border: 1px dashed #999 !important;
            padding-top: 1.5rem;
            margin-top: 1rem;
            position: relative;
        }

        legend {
            font-size: 1rem;
            font-weight: 600;
            padding: 0 10px;
            width: auto;
            color: #000000;
        }

        fieldset.border {
            border: 1px dashed #e3e3e3 !important;
            padding: 1.5rem;
            margin-top: 1rem;
            position: relative;
        }

        fieldset.border legend {
            float: unset;
            background: #fff;
            padding: 0 0.5rem;
            margin-left: 1rem;
        }
        .section-title h6 {
            font-weight: 800;
            font-size: 1rem;
        }
        .bg-light.fw-bold {
            background-color: #f0f2f5 !important;
            font-size: 1rem;
        }

        table.dataTable tbody tr.dtrg-group {
            text-align: left !important;
            padding-left: 12px;
            font-weight: bold;
            background-color: #f8f9fa !important;
            color: #000;
            text-transform: uppercase;
        }

        .pekerjaan-column {
            min-width: 250px;
            max-width: 400px;
            white-space: normal;
            word-wrap: break-word;
            text-align: left !important;
        }

        .periodic-column {
            min-width: 200px;
            max-width: 300px;
            white-space: normal;
            word-wrap: break-word;
            text-align: left !important;
        }

        .keterangan-column {
            min-width: 200px;
            max-width: 400px;
            white-space: normal;
            word-wrap: break-word;
            text-align: left !important;
        }

        .nomor-column {
            width: 40px;
            max-width: 50px;
            text-align: center !important;
            vertical-align: middle !important;
            font-weight: 500;
        }

        .hari-libur {
            background-color: #ffe5e5;
            color: #d10000 !important;
            font-weight: bold;
        }

        .img-paraf-preview {
            height: 50px;
            width: auto;
            object-fit: contain;
        }

        #modalPreviewImage {
            transition: transform 0.3s ease;
            max-width: 100%;
            height: auto;
        }

        .btn-outline-danger i {
            transition: color 0.2s ease;
        }

        .btn-outline-danger:hover i {
            color: white;
        }

        .signature-container {
            position: relative;
            width: 100%;
        }

        .signature-canvas {
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            width: 100%;
            height: 150px;
            border-radius: 4px;
            display: block;
        }

        .input-group .btn-outline-danger {
            border-radius: 0 0.375rem 0.375rem 0;
        }

        table img {
            max-height: 60px;
            object-fit: cover;
            border: 1px solid #ddd;
            padding: 2px;
            background-color: #fff;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.1.5/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>

    <script>
        $(function () {
            $('#progressTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                paging: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: '{{ route('progresskerja.data') }}',
                    data: function (d) {
                        d.bulan = $('#filterBulan').val();
                        d.tahun = $('#filterTahun').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'pic', name: 'pic' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'jenis_barang', name: 'jenis_barang' },
                    { data: 'lokasi', name: 'lokasi' },
                    { data: 'uraian_masalah', name: 'uraian_masalah' },
                    { data: 'rencana_tindakan_perbaikan', name: 'rencana_tindakan_perbaikan' },
                    { data: 'selesai_perbaikan', name: 'selesai_perbaikan' },
                    { data: 'tindakan_perbaikan', name: 'tindakan_perbaikan' },
                    { data: 'status_perbaikan', name: 'status_perbaikan' },
                    { data: 'bukti_upload', name: 'bukti_upload', orderable: false, searchable: false },
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Cari Laporan Kerusakan",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>',
                    },
                },
                initComplete: function () {
                    const searchBox = $('.dataTables_filter input');
                    searchBox.wrap('<div class="input-group"></div>');
                    searchBox.before('<span class="input-group-text"><i class="fas fa-search"></i></span>');
                }
            });

            $('#filterBulan, #filterTahun').on('change', function () {
                $('#progressTable').DataTable().ajax.reload();
            });
        });
    </script>
</x-default-layout>
