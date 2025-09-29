<x-default-layout>
    @section('title', 'Form Laporan Kerusakan')

    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark d-flex align-items-center">
                <i class="bi bi-tools me-2 fs-4"></i>
                <h3 class="mb-0 fw-bold text-white">Form Laporan Kerusakan</h3>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('formlaporan.store') }}" method="POST" id="formLaporanKerusakan">
                    @csrf

                    <!-- Jenis Barang -->
                    <fieldset class="mb-4 border rounded-3 p-3">
                        <legend class="fw-bold px-2">Jenis Barang</legend>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_barang" value="Kendaraan">
                                <label class="form-check-label fw-semibold">Kendaraan</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_barang" value="Alat Kantor">
                                <label class="form-check-label fw-semibold">Alat Kantor</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_barang" value="Bangunan">
                                <label class="form-check-label fw-semibold">Bangunan</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="jenis_barang" value="Lain Lain">
                                <label class="form-check-label fw-semibold">Lain Lain</label>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Nama, Dept/Divisi, Lokasi -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-4">
                            <label for="nama" class="form-label fw-bold">Nama</label>
                            <select id="nama" name="nama" class="form-select shadow-sm">
                                <option selected disabled>Pilih Nama</option>
                            </select>
                            <div id="loadingNama" class="form-text text-muted">Memuat data karyawan mohon tunggu...</div>
                        </div>
                        <div class="col-md-4">
                            <label for="dept" class="form-label fw-bold">Dept/Divisi</label>
                            <input type="text" id="dept" class="form-control shadow-sm" value="-" readonly>
                            <input type="hidden" id="dept_hidden" name="dept">
                        </div>
                        <div class="col-md-4">
                            <label for="lokasi" class="form-label fw-bold">Lokasi</label>
                            <input type="text" id="lokasi" name="lokasi" class="form-control shadow-sm">
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-4">
                        <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                        <input type="text" id="keterangan" name="keterangan" class="form-control shadow-sm">
                    </div>

                    <!-- Uraian Masalah -->
                    <div class="mb-4">
                        <label for="uraian" class="form-label fw-bold">Uraian Masalah</label>
                        <textarea id="uraian" name="uraian" rows="4" class="form-control shadow-sm"></textarea>
                    </div>

                    <!-- Signature Pads -->
                    <div class="row text-center mb-4 g-3">
                        <!-- Signature 1 -->
                        <div class="col-md-4 col-12 mb-3 mb-md-0">
                            <h6 class="fw-bold">Dilaporkan Oleh</h6>
                            <canvas id="signature1" class="border w-100 rounded-3 bg-light" height="300"></canvas>

                            <div class="input-group mt-2 shadow-sm">
                                <input type="text" name="nama_dilaporkan_oleh" class="form-control" placeholder="Nama">
                                <button type="button" class="btn btn-outline-danger" onclick="clearSignature('signature1')" title="Hapus Tanda Tangan">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>

                            <label class="form-label mt-3 mb-1 fw-semibold text-muted">Preview Tanda Tangan:</label>
                            <img id="previewSignature1" class="img-fluid rounded border" style="height: 75px;" alt="Preview Tanda Tangan">
                            <input type="hidden" name="ttd_dilaporkan_oleh" id="ttd_dilaporkan_oleh">
                        </div>

                        <!-- Signature 2 -->
                        <div class="col-md-4 col-12 mb-3 mb-md-0">
                            <h6 class="fw-bold">Diketahui Oleh</h6>
                            <canvas id="signature2" class="border w-100 rounded-3 bg-light" height="300"></canvas>

                            <div class="input-group mt-2 shadow-sm">
                                <input type="text" name="nama_diketahui_oleh" class="form-control" placeholder="Nama">
                                <button type="button" class="btn btn-outline-danger" onclick="clearSignature('signature2')" title="Hapus Tanda Tangan">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>

                            <label class="form-label mt-3 mb-1 fw-semibold text-muted">Preview Tanda Tangan:</label>
                            <img id="previewSignature2" class="img-fluid rounded border" style="height: 75px;" alt="Preview Tanda Tangan">
                            <input type="hidden" name="ttd_diketahui_oleh" id="ttd_diketahui_oleh">
                        </div>

                        <!-- Signature 3 -->
                        <div class="col-md-4 col-12">
                            <h6 class="fw-bold">Diterima Oleh</h6>
                            <canvas id="signature3" class="border w-100 rounded-3 bg-light" height="300"></canvas>

                            <div class="input-group mt-2 shadow-sm">
                                <input type="text" name="nama_diterima_oleh" class="form-control" placeholder="Nama">
                                <button type="button" class="btn btn-outline-danger" onclick="clearSignature('signature3')" title="Hapus Tanda Tangan">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>

                            <label class="form-label mt-3 mb-1 fw-semibold text-muted">Preview Tanda Tangan:</label>
                            <img id="previewSignature3" class="img-fluid rounded border" style="height: 75px;" alt="Preview Tanda Tangan">
                            <input type="hidden" name="ttd_diterima_oleh" id="ttd_diterima_oleh">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="reset" class="btn btn-light me-2">Reset Form</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
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
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.1.5/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>

    <script>
         document.addEventListener("DOMContentLoaded", function () {
            const namaSelect = document.getElementById("nama");
            const deptInput = document.getElementById("dept");
            const deptHidden = document.getElementById("dept_hidden");
            const loadingIndicator = document.getElementById("loadingNama");

            if ($.fn.select2 && $('#nama').hasClass("select2-hidden-accessible")) {
                $('#nama').select2('destroy');
            }

            fetch("http://192.168.0.8:8000/api/karyawan")
                .then(res => res.json())
                .then(data => {
                    namaSelect.innerHTML = `<option disabled selected>Pilih Nama</option>`;
                    data.forEach(item => {
                        const opt = document.createElement("option");
                        opt.value = item.Nama;
                        opt.textContent = item.Nama;
                        opt.dataset.divisi = item.Divisi;
                        opt.dataset.dept = item.Dept;
                        namaSelect.appendChild(opt);
                    });

                    $('#nama').select2({
                        dropdownParent: $('#formLaporanKerusakan'),
                        placeholder: "Pilih Nama",
                        allowClear: true,
                        width: "100%"
                    });

                    loadingIndicator.style.display = "none";

                    $('#nama').on('select2:select', function (e) {
                        const selected = e.params.data.element;
                        const divisi = selected.dataset.divisi;
                        const dept = selected.dataset.dept;
                        const divisiDept = `${divisi} / ${dept}`;

                        deptInput.value = divisiDept;
                        deptHidden.value = divisiDept;
                    });
                })
                .catch(err => {
                    console.error("❌ Gagal load karyawan:", err);
                    loadingIndicator.textContent = "❌ Gagal memuat data.";
                });
        });

        function resizeCanvas(canvas) {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const oldWidth = canvas.width;
            const oldHeight = canvas.height;

            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }

        function initSignaturePad(canvasId, previewId) {
            const canvas = document.getElementById(canvasId);
            const preview = document.getElementById(previewId);

            resizeCanvas(canvas);

            const ctx = canvas.getContext("2d");
            let drawing = false;

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                return {
                    x: (e.touches ? e.touches[0].clientX : e.clientX) - rect.left,
                    y: (e.touches ? e.touches[0].clientY : e.clientY) - rect.top
                };
            }

            function updatePreview() {
                if (preview) {
                    preview.src = canvas.toDataURL();
                }
            }

            canvas.addEventListener("mousedown", e => {
                drawing = true;
                const pos = getPos(e);
                ctx.beginPath(); ctx.moveTo(pos.x, pos.y);
            });

            canvas.addEventListener("mouseup", () => {
                drawing = false; ctx.beginPath();
                updatePreview();
            });

            canvas.addEventListener("mousemove", e => {
                if (!drawing) return;
                const pos = getPos(e);
                ctx.lineWidth = 2; ctx.lineCap = "round"; ctx.strokeStyle = "#000";
                ctx.lineTo(pos.x, pos.y); ctx.stroke(); ctx.beginPath(); ctx.moveTo(pos.x, pos.y);
            });

            canvas.addEventListener("touchstart", e => {
                e.preventDefault(); drawing = true;
                const pos = getPos(e); ctx.beginPath(); ctx.moveTo(pos.x, pos.y);
            });

            canvas.addEventListener("touchend", () => {
                drawing = false; ctx.beginPath();
                updatePreview();
            });

            canvas.addEventListener("touchmove", e => {
                e.preventDefault();
                if (!drawing) return;
                const pos = getPos(e);
                ctx.lineTo(pos.x, pos.y); ctx.stroke(); ctx.beginPath(); ctx.moveTo(pos.x, pos.y);
            });
        }



        function clearSignature(id) {
            const canvas = document.getElementById(id);
            if (canvas) canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
        }

        window.addEventListener("resize", () => {
            ["signature1", "signature2", "signature3"].forEach(id => {
                const canvas = document.getElementById(id);
                if (canvas) {
                    resizeCanvas(canvas);
                }
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            initSignaturePad("signature1", "previewSignature1");
            initSignaturePad("signature2", "previewSignature2");
            initSignaturePad("signature3", "previewSignature3");
        });

        function isCanvasBlank(canvas) {
            const context = canvas.getContext('2d');
            const pixelData = context.getImageData(0, 0, canvas.width, canvas.height).data;

            for (let i = 0; i < pixelData.length; i += 4) {
                if (
                    pixelData[i] !== 0 ||
                    pixelData[i + 1] !== 0 ||
                    pixelData[i + 2] !== 0 ||
                    pixelData[i + 3] !== 0
                ) {
                    return false;
                }
            }
            return true;
        }

        document.getElementById('formLaporanKerusakan').addEventListener('submit', function () {
            const canvas1 = document.getElementById('signature1');
            const canvas2 = document.getElementById('signature2');
            const canvas3 = document.getElementById('signature3');

            const ttd1 = document.getElementById('ttd_dilaporkan_oleh');
            const ttd2 = document.getElementById('ttd_diketahui_oleh');
            const ttd3 = document.getElementById('ttd_diterima_oleh');

            if (!isCanvasBlank(canvas1)) {
                ttd1.value = canvas1.toDataURL();
            }

            if (!isCanvasBlank(canvas2)) {
                ttd2.value = canvas2.toDataURL();
            }

            if (!isCanvasBlank(canvas3)) {
                ttd3.value = canvas3.toDataURL();
            }
        });
    </script>
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif
</x-default-layout>
