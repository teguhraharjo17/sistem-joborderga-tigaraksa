<div class="card card-flush h-md-100">
    <div class="card-header pt-7">
        <h3 class="card-title fw-bold text-dark fs-3">Top 5 Lokasi Paling Sering Rusak</h3>
    </div>
    <div class="card-body" style="height: 400px;">
        <div class="table-responsive">
            <table class="table table-row-dashed align-middle gs-0 gy-3">
                <thead>
                    <tr class="fw-semibold text-muted text-uppercase fs-7">
                        <th style="width: 10px;">#</th>
                        <th>Lokasi</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody id="lokasi-table-body">
                    @forelse ($topLokasi as $index => $item)
                        <tr>
                            <td class="fw-bold text-dark">{{ $index + 1 }}</td>
                            <td class="fw-semibold text-gray-800">{{ $item->lokasi }}</td>
                            <td class="text-end fw-bold text-dark">{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                Tidak ada data kerusakan untuk ditampilkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
