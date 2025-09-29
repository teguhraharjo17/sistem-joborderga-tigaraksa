<x-default-layout>
    @section('title', 'Detail Laporan Kerusakan')

    <div class="container mt-5">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-dark text-white d-flex align-items-center">
                <i class="bi bi-file-text me-2 fs-4"></i>
                <h5 class="mb-0 fw-bold">Detail Laporan Kerusakan</h5>
            </div>

            <div class="card-body p-4">
                <!-- Informasi Laporan -->
                <div class="mb-4">
                    <h6 class="fw-bold">Informasi Laporan</h6>
                    <ul class="list-group">
                        <li class="list-group-item"><b>Jenis Barang:</b> {{ $laporan->jenis_barang }}</li>
                        <li class="list-group-item"><b>Nama:</b> {{ $laporan->nama }}</li>
                        <li class="list-group-item"><b>Dept/Divisi:</b> {{ $laporan->dept_divisi }}</li>
                        <li class="list-group-item"><b>Lokasi:</b> {{ $laporan->lokasi }}</li>
                        <li class="list-group-item"><b>Keterangan:</b> {{ $laporan->keterangan }}</li>
                        <li class="list-group-item"><b>Uraian Masalah:</b> {{ $laporan->uraian_masalah }}</li>
                        <li class="list-group-item">
                            <b>Status:</b>
                            <span class="badge 
                                @if($laporan->status_laporan == 'menunggu_diketahui') bg-warning 
                                @elseif($laporan->status_laporan == 'menunggu_diterima') bg-info 
                                @elseif($laporan->status_laporan == 'selesai') bg-success 
                                @else bg-secondary @endif">
                                {{ ucfirst(str_replace('_',' ', $laporan->status_laporan)) }}
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Bagian Tanda Tangan -->
                <div class="row text-center mb-4 g-3">
                    <!-- Dilaporkan Oleh -->
                    <div class="col-md-4 col-12">
                        <h6 class="fw-bold">Dilaporkan Oleh</h6>
                        @if($laporan->nama_dilaporkan_oleh && $laporan->ttd_dilaporkan_oleh)
                            <img src="{{ $laporan->ttd_dilaporkan_oleh }}" class="w-100 border rounded-3 bg-light" style="height:150px;object-fit:contain;">
                            <p class="mt-2">{{ $laporan->nama_dilaporkan_oleh }}</p>
                        @elseif(auth()->user()->id == $laporan->user_id && $laporan->status_laporan == 'draft')
                            <form action="{{ route('laporan.ttd', [$laporan->id, 'role' => 'dilaporkan']) }}" method="POST">
                                @csrf
                                <canvas id="signature1" class="border w-100 rounded-3 bg-light" height="150"></canvas>
                                <input type="hidden" name="ttd" id="signature1_input">
                                <input type="text" name="nama" class="form-control mt-2 shadow-sm" placeholder="Nama" required>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2 w-100" onclick="clearSignature('signature1')">Hapus Tanda Tangan</button>
                                <button type="submit" class="btn btn-sm btn-success mt-2 w-100" onclick="saveSignature('signature1','signature1_input')">Simpan</button>
                            </form>
                        @else
                            <p class="text-muted">Menunggu...</p>
                        @endif
                    </div>

                    <!-- Diketahui Oleh -->
                    <div class="col-md-4 col-12">
                        <h6 class="fw-bold">Diketahui Oleh</h6>
                        @if($laporan->nama_diketahui_oleh && $laporan->ttd_diketahui_oleh)
                            <img src="{{ $laporan->ttd_diketahui_oleh }}" class="w-100 border rounded-3 bg-light" style="height:150px;object-fit:contain;">
                            <p class="mt-2">{{ $laporan->nama_diketahui_oleh }}</p>
                        @elseif(auth()->user()->role == 'Supervisor' && $laporan->status_laporan == 'menunggu_diketahui')
                            <form action="{{ route('laporan.ttd', [$laporan->id, 'role' => 'diketahui']) }}" method="POST">
                                @csrf
                                <canvas id="signature2" class="border w-100 rounded-3 bg-light" height="150"></canvas>
                                <input type="hidden" name="ttd" id="signature2_input">
                                <input type="text" name="nama" class="form-control mt-2 shadow-sm" placeholder="Nama" required>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2 w-100" onclick="clearSignature('signature2')">Hapus Tanda Tangan</button>
                                <button type="submit" class="btn btn-sm btn-success mt-2 w-100" onclick="saveSignature('signature2','signature2_input')">Simpan</button>
                            </form>
                        @else
                            <p class="text-muted">Menunggu...</p>
                        @endif
                    </div>

                    <!-- Diterima Oleh -->
                    <div class="col-md-4 col-12">
                        <h6 class="fw-bold">Diterima Oleh</h6>
                        @if($laporan->nama_diterima_oleh && $laporan->ttd_diterima_oleh)
                            <img src="{{ $laporan->ttd_diterima_oleh }}" class="w-100 border rounded-3 bg-light" style="height:150px;object-fit:contain;">
                            <p class="mt-2">{{ $laporan->nama_diterima_oleh }}</p>
                        @elseif(auth()->user()->role == 'GA' && $laporan->status_laporan == 'menunggu_diterima')
                            <form action="{{ route('laporan.ttd', [$laporan->id, 'role' => 'diterima']) }}" method="POST">
                                @csrf
                                <canvas id="signature3" class="border w-100 rounded-3 bg-light" height="150"></canvas>
                                <input type="hidden" name="ttd" id="signature3_input">
                                <input type="text" name="nama" class="form-control mt-2 shadow-sm" placeholder="Nama" required>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2 w-100" onclick="clearSignature('signature3')">Hapus Tanda Tangan</button>
                                <button type="submit" class="btn btn-sm btn-success mt-2 w-100" onclick="saveSignature('signature3','signature3_input')">Simpan</button>
                            </form>
                        @else
                            <p class="text-muted">Menunggu...</p>
                        @endif
                    </div>
                </div>

                <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>

    <script>
        function initSignaturePad(canvasId) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            const ctx = canvas.getContext("2d");
            let drawing = false;

            function getPos(e) {
                const rect = canvas.getBoundingClientRect();
                return {
                    x: (e.touches ? e.touches[0].clientX : e.clientX) - rect.left,
                    y: (e.touches ? e.touches[0].clientY : e.clientY) - rect.top
                };
            }

            canvas.addEventListener("mousedown", e => {
                drawing = true;
                const pos = getPos(e);
                ctx.beginPath(); ctx.moveTo(pos.x, pos.y);
            });
            canvas.addEventListener("mouseup", () => { drawing = false; ctx.beginPath(); });
            canvas.addEventListener("mousemove", e => {
                if (!drawing) return;
                const pos = getPos(e);
                ctx.lineWidth = 2; ctx.lineCap = "round"; ctx.strokeStyle = "#000";
                ctx.lineTo(pos.x, pos.y); ctx.stroke(); ctx.beginPath(); ctx.moveTo(pos.x, pos.y);
            });

            // mobile
            canvas.addEventListener("touchstart", e => {
                e.preventDefault(); drawing = true;
                const pos = getPos(e); ctx.beginPath(); ctx.moveTo(pos.x, pos.y);
            });
            canvas.addEventListener("touchend", () => { drawing = false; ctx.beginPath(); });
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

        function saveSignature(canvasId, inputId) {
            const canvas = document.getElementById(canvasId);
            const input = document.getElementById(inputId);
            if (canvas && input) input.value = canvas.toDataURL();
        }

        document.addEventListener("DOMContentLoaded", () => {
            initSignaturePad("signature1");
            initSignaturePad("signature2");
            initSignaturePad("signature3");
        });
    </script>
</x-default-layout>
