<x-default-layout>
    @section('title', 'List Laporan Kerusakan')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark d-flex align-items-center">
                <i class="bi bi-tools me-2 fs-4"></i>
                <h3 class="mb-0 fw-bold text-white">Daftar Laporan Kerusakan</h3>
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
                    <table id="laporanTable" class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tanggal Dibuat</th>
                                <th>Barang</th>
                                <th>Lokasi</th>
                                <th>Masalah</th>
                                <th>Status TTD</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal Lengkapi TTD -->
        <div class="modal fade" id="modalLengkapiTtd" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <form method="POST" action="{{ route('listlaporankerusakan.update-ttd', ['id' => 0]) }}" id="formLengkapiTtd">
                        @csrf

                        <!-- Header -->
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title" id="modalLabel">Lengkapi Tanda Tangan Laporan Kerusakan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <!-- Body -->
                        <div class="modal-body px-4 py-3">
                            <!-- Informasi Laporan -->
                            <fieldset class="border rounded-3 p-3 mb-4">
                                <legend class="fw-bold px-2">Informasi Laporan</legend>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="modalTanggal" class="form-label fw-semibold">Tanggal Dibuat</label>
                                        <input type="text" id="modalTanggal" class="form-control shadow-sm" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="modalNama" class="form-label fw-semibold">Nama Pelapor</label>
                                        <input type="text" id="modalNama" class="form-control shadow-sm" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="modalDept" class="form-label fw-semibold">Departemen / Divisi</label>
                                        <input type="text" id="modalDept" class="form-control shadow-sm" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="modalJenis" class="form-label fw-semibold">Jenis Barang</label>
                                        <input type="text" id="modalJenis" class="form-control shadow-sm" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="modalLokasi" class="form-label fw-semibold">Lokasi</label>
                                        <input type="text" id="modalLokasi" class="form-control shadow-sm" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="modalKeterangan" class="form-label fw-semibold">Keterangan Tambahan</label>
                                        <input type="text" id="modalKeterangan" class="form-control shadow-sm" readonly>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="modalUraian" class="form-label fw-semibold">Uraian Masalah</label>
                                        <textarea id="modalUraian" class="form-control shadow-sm" rows="4" readonly></textarea>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Tanda Tangan -->
                            <div class="row text-center g-4">
                                @foreach(['dilaporkan', 'diketahui', 'diterima'] as $role)
                                    <div class="col-md-4">
                                        <div class="border rounded-3 p-3 h-100 shadow-sm bg-white">
                                            <h6 class="fw-bold text-capitalize">{{ $role }} oleh</h6>

                                            <!-- WRAPPER: Elemen interaktif (canvas + input) -->
                                            <div class="ttd-interactive" id="ttd_group_{{ $role }}">
                                                <!-- Canvas -->
                                                <canvas id="canvas_{{ $role }}" class="border w-100 rounded-3 bg-light" height="300"></canvas>

                                                <!-- Input nama + tombol hapus -->
                                                <div class="input-group mt-2 shadow-sm">
                                                    <input type="text" name="nama_{{ $role }}_oleh" id="nama_{{ $role }}_oleh" class="form-control" placeholder="Nama">
                                                    <button type="button" class="btn btn-outline-danger" onclick="clearSignature('canvas_{{ $role }}')" title="Hapus TTD">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- WRAPPER: Preview tanda tangan -->
                                            <div class="ttd-preview-only mt-3" id="ttd_preview_{{ $role }}" style="display: none;">
                                                <label class="form-label fw-semibold text-muted">Sudah Ditandatangani:</label>
                                                <small id="status_{{ $role }}" class="fw-semibold d-block mt-1 text-success">
                                                    <i class="bi bi-check-circle-fill me-1"></i>Sudah Ditandatangani
                                                </small>
                                            </div>

                                            <!-- Hidden input -->
                                            <input type="hidden" name="ttd_{{ $role }}_oleh" id="ttd_{{ $role }}_oleh">
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="modal-footer">
                            <input type="hidden" name="laporan_id" id="modalLaporanId">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> Simpan TTD
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

        img[id^="preview_"] {
            background-color: #fff;
            border: 1px solid #ddd;
            height: 75px;
            object-fit: contain;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.1.5/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>

    <script>
    $(document).ready(function () {
        // DataTable setup
        $('#laporanTable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            paging: true,
            searching: true,
            ordering: false,
            ajax: {
                url: '{{ route("listlaporankerusakan.data") }}',
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
                }
            ],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama', name: 'nama' },
                { data: 'created_at', name: 'created_at' },
                { data: 'jenis_barang', name: 'jenis_barang' },
                { data: 'lokasi', name: 'lokasi' },
                { data: 'uraian_masalah', name: 'uraian_masalah' },
                { data: 'status_ttd', name: 'status_ttd', orderable: false, searchable: false },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
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
            $('#laporanTable').DataTable().ajax.reload();
        });

        const signaturePads = {};

        function resizeCanvas(canvas) {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }

        function initSignaturePad(canvasId) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            if (signaturePads[canvasId]) return;

            setTimeout(() => {
                resizeCanvas(canvas);
                const pad = new SignaturePad(canvas, {
                    minWidth: 1,
                    maxWidth: 2,
                });
                signaturePads[canvasId] = pad;
            }, 200);
        }

        function clearSignature(id) {
            if (signaturePads[id]) {
                signaturePads[id].clear();
                $(`#ttd_${id.replace('canvas_', '')}_oleh`).val('');
                $(`#preview_${id.replace('canvas_', '')}`).attr('src', '');
            }
        }

        window.clearSignature = clearSignature;

        function populateModal(data) {
            $('#modalLaporanId').val(data.id);
            $('#modalTanggal').val(data.created_at);
            $('#modalNama').val(data.nama);
            $('#modalJenis').val(data.jenis_barang);
            $('#modalLokasi').val(data.lokasi);
            $('#modalUraian').val(data.uraian);
            $('#modalDept').val(data.dept_divisi || '-');
            $('#modalKeterangan').val(data.keterangan || '-');

            ['dilaporkan', 'diketahui', 'diterima'].forEach(role => {
                const canvasId = `canvas_${role}`;
                const inputNama = $(`#nama_${role}_oleh`);
                const hiddenTtd = $(`#ttd_${role}_oleh`);
                const preview = $(`#preview_${role}`);
                const statusLabel = $(`#status_${role}`);

                const interactiveGroup = $(`#ttd_group_${role}`);
                const previewGroup = $(`#ttd_preview_${role}`);

                initSignaturePad(canvasId);

                const nama = data[`nama_${role}_oleh`] || '';
                const ttd = data[`ttd_${role}_oleh`] || '';

                setTimeout(() => {
                    const pad = signaturePads[canvasId];
                    if (pad) pad.clear();

                    inputNama.val(nama);
                    hiddenTtd.val(ttd);

                    const isBase64 = ttd.startsWith('data:image/');

                    if (ttd && nama) {
                        inputNama.prop('readonly', true);
                        if (pad) {
                            pad.off();
                            if (isBase64) pad.fromDataURL(ttd);
                        }

                        preview.attr('src', ttd);
                        statusLabel
                            .html(`<i class="bi bi-check-circle-fill me-1"></i>Sudah Ditandatangani oleh ${nama}`)
                            .removeClass('text-danger')
                            .addClass('text-success');

                        interactiveGroup.hide();
                        previewGroup.show();
                    } else {
                        inputNama.prop('readonly', false);
                        if (pad) pad.on();
                        preview.attr('src', '');
                        statusLabel
                            .html('<i class="bi bi-x-circle-fill me-1"></i>Belum Ditandatangani')
                            .removeClass('text-success')
                            .addClass('text-danger');

                        interactiveGroup.show();
                        previewGroup.hide();
                    }
                }, 250);
            });
        }

        $(document).on('click', '.lengkapi-ttd-btn', function () {
            const data = {
                id: $(this).data('id'),
                created_at: $(this).data('created_at'),
                nama: $(this).data('nama'),
                jenis_barang: $(this).data('jenis_barang'),
                lokasi: $(this).data('lokasi'),
                dept_divisi: $(this).data('dept_divisi'),
                keterangan: $(this).data('keterangan'),
                uraian: $(this).data('uraian'),

                nama_dilaporkan_oleh: $(this).data('nama_dilaporkan_oleh'),
                ttd_dilaporkan_oleh: $(this).data('ttd_dilaporkan_oleh'),
                nama_diketahui_oleh: $(this).data('nama_diketahui_oleh'),
                ttd_diketahui_oleh: $(this).data('ttd_diketahui_oleh'),
                nama_diterima_oleh: $(this).data('nama_diterima_oleh'),
                ttd_diterima_oleh: $(this).data('ttd_diterima_oleh'),
            };

            populateModal(data);

            const action = `{{ url('list-laporan-kerusakan/update-ttd') }}/${data.id}`;
            $('#formLengkapiTtd').attr('action', action);

            const modal = new bootstrap.Modal(document.getElementById('modalLengkapiTtd'));
            modal.show();
        });

        $('#formLengkapiTtd').on('submit', function (e) {
            e.preventDefault();

            ['dilaporkan', 'diketahui', 'diterima'].forEach(role => {
                const canvasId = `canvas_${role}`;
                const pad = signaturePads[canvasId];
                const inputNama = $(`#nama_${role}_oleh`);
                const hiddenTtd = $(`#ttd_${role}_oleh`);

                if (pad && !pad.isEmpty() && !inputNama.prop('readonly')) {
                    const dataUrl = pad.toDataURL();
                    hiddenTtd.val(dataUrl);
                }
            });

            const form = this;
            const formData = new FormData(form);
            const actionUrl = $(form).attr('action');

            $.ajax({
                url: actionUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalLengkapiTtd'));
                    modal.hide();

                    $('#laporanTable').DataTable().ajax.reload(null, false);

                    Swal.fire({
                        icon: 'success',
                        title: 'TTD Disimpan',
                        text: 'Tanda tangan berhasil disimpan.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '';

                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                errorMessages += `${errors[key][0]}<br>`;
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: errorMessages,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyimpan TTD.',
                        });
                    }
                }
            });
        });

        window.addEventListener("resize", () => {
            ['dilaporkan', 'diketahui', 'diterima'].forEach(role => {
                const canvas = document.getElementById(`canvas_${role}`);
                if (canvas) resizeCanvas(canvas);
            });
        });
    });
</script>
</x-default-layout>
