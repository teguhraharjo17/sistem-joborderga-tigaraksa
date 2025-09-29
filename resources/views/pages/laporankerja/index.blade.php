<x-default-layout>
    @section('title', 'List Progress GA')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark d-flex align-items-center">
                <i class="bi bi-file-earmark-text me-2 fs-4"></i>
                <h3 class="mb-0 fw-bold text-white">Laporan Kerja</h3>
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
                                @php $formatted = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                                <option value="{{ $formatted }}" {{ $formatted == $currentMonth ? 'selected' : '' }}>
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
                    <table id="laporanKerjaTable" class="table table-bordered table-hover align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>PIC</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Lokasi</th>
                                <th>Uraian Masalah</th>
                                <th>Rencana Tindakan</th>
                                <th>Selesai Perbaikan</th>
                                <th>Tindakan Perbaikan</th>
                                <th>Status</th>
                                <th><strong>Status TTD</strong></th>
                                <th>Bukti Kerja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal Update Laporan GA -->
        <div class="modal fade" id="modalLaporanGA" tabindex="-1" aria-labelledby="modalLaporanLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form id="formLaporanGA" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title" id="modalLaporanLabel">Update Laporan Kerja GA</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Informasi Laporan Kerusakan -->
                            <fieldset class="border rounded-3 p-3 mb-4">
                                <legend class="fw-bold px-2">Informasi Laporan Kerusakan</legend>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nama</label>
                                        <input type="text" id="modalNama" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Tanggal</label>
                                        <input type="text" id="modalTanggal" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Jenis Barang</label>
                                        <input type="text" id="modalBarang" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Lokasi</label>
                                        <input type="text" id="modalLokasi" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Uraian Masalah</label>
                                        <textarea id="modalUraian" class="form-control" rows="4" readonly></textarea>
                                    </div>
                                </div>
                            </fieldset>


                            <!-- Form GA -->
                            <fieldset class="border rounded-3 p-3">
                                <legend class="fw-bold px-2">Form Laporan GA</legend>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Rencana Tindakan Perbaikan</label>
                                        <input type="date" name="rencana_tindakan_perbaikan" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Selesai Perbaikan</label>
                                        <input type="date" name="selesai_perbaikan" class="form-control">
                                    </div>
                                    <div class="row g-4 align-items-end">
                                        <!-- PIC -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">PIC</label>
                                            <input type="text" name="pic" class="form-control">
                                        </div>

                                        <!-- Internal / External -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold d-block">Internal / External</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="internal_external" value="internal">
                                                <label class="form-check-label">Internal</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="internal_external" value="external">
                                                <label class="form-check-label">External</label>
                                            </div>
                                        </div>

                                        <!-- Status Perbaikan -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold d-block">Status</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input status-radio" type="radio" name="status_perbaikan" value="belum_mulai">
                                                <label class="form-check-label">Belum Mulai</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input status-radio" type="radio" name="status_perbaikan" value="progress">
                                                <label class="form-check-label">Progress</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input status-radio" type="radio" name="status_perbaikan" value="selesai">
                                                <label class="form-check-label">Selesai</label>
                                            </div>
                                        </div>

                                        <!-- Upload Bukti -->
                                        <div class="col-md-3" id="buktiUploadWrapper" style="display: none;">
                                            <label class="form-label fw-semibold">Upload Bukti Perbaikan</label>
                                            <input type="file" name="bukti_upload" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Tindakan Perbaikan</label>
                                        <textarea name="tindakan_perbaikan" class="form-control" rows="3"></textarea>
                                    </div>

                                    <!-- Dikerjakan Oleh -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold mb-2">Dikerjakan Oleh</label>
                                        <div class="signature-container">
                                            <div id="block_dikerjakan"> <!-- Tambahkan wrapper -->
                                                <canvas id="canvas_dikerjakan" class="signature-canvas"></canvas>
                                                <div class="input-group mt-2">
                                                    <input type="text" name="nama_dikerjakan_oleh" class="form-control" placeholder="Nama Penanda Tangan">
                                                    <button type="button" id="clear_dikerjakan" class="btn btn-outline-danger">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <input type="hidden" name="ttd_dikerjakan_oleh" id="ttd_dikerjakan_oleh">
                                        </div>
                                    </div>

                                    <!-- Dikontrol Oleh -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold mb-2">Dikontrol Oleh</label>
                                        <div class="signature-container">
                                            <div id="block_dikontrol"> <!-- Tambahkan wrapper -->
                                                <canvas id="canvas_dikontrol" class="signature-canvas"></canvas>
                                                <div class="input-group mt-2">
                                                    <input type="text" name="nama_dikontrol_oleh" class="form-control" placeholder="Nama Penanda Tangan">
                                                    <button type="button" id="clear_dikontrol" class="btn btn-outline-danger">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <input type="hidden" name="ttd_dikontrol_oleh" id="ttd_dikontrol_oleh">
                                        </div>
                                    </div>

                                </div>
                            </fieldset>

                        </div>

                        <div class="modal-footer">
                            <input type="hidden" name="laporan_kerusakan_id" id="laporanKerusakanId">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> Simpan Laporan GA
                            </button>
                        </div>
                    </form>
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
            $('#laporanKerjaTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                paging: true,
                searching: true,
                ordering: false,
                ajax: {
                    url: "{{ route('laporankerja.data') }}",
                    data: function (d) {
                        d.bulan = $('#filterBulan').val();
                        d.tahun = $('#filterTahun').val();
                    }
                },
                dom: '<"row mb-3 align-items-center"' +
                '<"col-md-6 d-flex align-items-center gap-2"B>' +
                '<"col-md-6 text-end"f>>' +
                '<"row"<"col-sm-12 table-responsive"t>>' +
                '<"row mt-3"<"col-sm-6"l><"col-sm-6 text-end"p>>',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Column Visible',
                        className: 'btn custom-button btn-sm me-1',
                    },
                    {
                        text: '<i class="fas fa-file-excel"></i> Export Excel',
                        className: 'btn custom-button btn-sm me-1',
                        action: function () {
                            const bulan = $('#filterBulan').val();
                            const tahun = $('#filterTahun').val();
                            const params = new URLSearchParams();
                            if (bulan) params.append('bulan', bulan);
                            if (tahun) params.append('tahun', tahun);

                            const url = "{{ route('laporankerja.export') }}" + '?' + params.toString();
                            window.open(url, '_blank');
                        }
                    }
                ],
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
                    {
                    data: 'status_ttd',
                    name: 'status_ttd',
                    orderable: false,
                    searchable: false,
                    },
                    { data: 'bukti_upload', name: 'bukti_upload' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
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
                $('#laporanKerjaTable').DataTable().ajax.reload();
            });



            let signaturePads = {};

            function resizeCanvas(canvas, pad) {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const data = pad.toData();

                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);

                pad.clear();
                pad.fromData(data);
            }

            function initSignaturePad(canvasId) {
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;

                if (signaturePads[canvasId]) {
                    signaturePads[canvasId].off();
                    delete signaturePads[canvasId];
                }

                const pad = new SignaturePad(canvas, {
                    minWidth: 1,
                    maxWidth: 2,
                    penColor: "black",
                    backgroundColor: "rgba(0,0,0,0)",
                });

                resizeCanvas(canvas, pad);
                signaturePads[canvasId] = pad;
            }


            window.addEventListener("resize", () => {
                Object.keys(signaturePads).forEach(id => {
                    const canvas = document.getElementById(id);
                    const pad = signaturePads[id];
                    if (canvas && pad) {
                        resizeCanvas(canvas, pad);
                    }
                });
            });

            $(document).on('click', '.btn-update-laporan', function () {
                const data = $(this).data();

                // Set isi input form laporan kerusakan
                $('#modalNama').val(data.nama);
                $('#modalTanggal').val(data.tanggal);
                $('#modalBarang').val(data.barang);
                $('#modalLokasi').val(data.lokasi);
                $('#modalUraian').val(data.uraian);

                // Set input laporan GA
                $('input[name="rencana_tindakan_perbaikan"]').val(data.rencana);
                $('input[name="selesai_perbaikan"]').val(data.selesai_perbaikan);
                $('input[name="pic"]').val(data.pic);
                $('textarea[name="tindakan_perbaikan"]').val(data.tindakan);

                // Radio button: internal/external
                $(`input[name="internal_external"][value="${data.internal_external}"]`).prop('checked', true);

                // Radio button: status perbaikan
                $(`input[name="status_perbaikan"][value="${data.status_perbaikan}"]`).prop('checked', true);

                // Nama dan TTD
                $('input[name="nama_dikerjakan_oleh"]').val(data.nama_dikerjakan);
                $('input[name="nama_dikontrol_oleh"]').val(data.nama_dikontrol);

                // Set hidden id
                $('#laporanKerusakanId').val(data.id);

                // Selalu tampilkan blok ttd dan isi nama (kalau ada)
                $('input[name="nama_dikerjakan_oleh"]').val(data.nama_dikerjakan);
                $('input[name="nama_dikontrol_oleh"]').val(data.nama_dikontrol);

                // Tidak perlu sembunyiin atau munculin preview/status
                $('#preview_dikerjakan').hide();
                $('#preview_dikontrol').hide();

                // Tampilkan modal
                new bootstrap.Modal(document.getElementById('modalLaporanGA')).show();
            });

            $('#formLaporanGA').on('submit', function (e) {
                e.preventDefault();

                // Ambil TTD
                const dikerjakanPad = signaturePads['canvas_dikerjakan'];
                const dikontrolPad = signaturePads['canvas_dikontrol'];

                if (dikerjakanPad && !dikerjakanPad.isEmpty()) {
                    $('#ttd_dikerjakan_oleh').val(dikerjakanPad.toDataURL());
                }

                if (dikontrolPad && !dikontrolPad.isEmpty()) {
                    $('#ttd_dikontrol_oleh').val(dikontrolPad.toDataURL());
                }

                const form = $(this);
                const formData = new FormData(form[0]);

                $.ajax({
                    url: "{{ route('laporankerja.store') }}", // Nanti disesuaikan
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        $('#modalLaporanGA').modal('hide');
                        $('#laporanKerjaTable').DataTable().ajax.reload(null, false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: 'Laporan GA berhasil disimpan!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        let message = 'Terjadi kesalahan.';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            message = Object.values(errors).map(e => `<div>${e[0]}</div>`).join('');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            html: message
                        });
                    }
                });
            });

            $('#modalLaporanGA').on('shown.bs.modal', function () {
                setTimeout(() => {
                    initSignaturePad('canvas_dikerjakan');
                    initSignaturePad('canvas_dikontrol');
                }, 200);
            });

            $('#modalLaporanGA').on('hidden.bs.modal', function () {
                $('#status_dikerjakan, #status_dikontrol').hide();
                $('#preview_dikerjakan, #preview_dikontrol').hide();
                $('#block_dikerjakan, #block_dikontrol').show();
            });

            $(document).on('change', 'input[name="status_perbaikan"]', function () {
                const value = $(this).val();
                if (value === 'selesai') {
                    $('#buktiUploadWrapper').show();
                } else {
                    $('#buktiUploadWrapper').hide();
                    $('input[name="bukti_upload"]').val(null);
                }
            });

            $('#clear_dikerjakan').on('click', function () {
                if (signaturePads['canvas_dikerjakan']) {
                    signaturePads['canvas_dikerjakan'].clear();
                    $('#ttd_dikerjakan_oleh').val('');
                }
            });

            $('#clear_dikontrol').on('click', function () {
                if (signaturePads['canvas_dikontrol']) {
                    signaturePads['canvas_dikontrol'].clear();
                    $('#ttd_dikontrol_oleh').val('');
                }
            });

        });
    </script>
</x-default-layout>
