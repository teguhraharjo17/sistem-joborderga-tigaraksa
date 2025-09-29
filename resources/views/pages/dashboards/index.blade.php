<x-default-layout>
    @section('title', 'Dashboard')

    <div class="d-flex justify-content-end mb-5 mt-5">
        <select id="filter-tahun" class="form-select form-select-sm w-auto">
            @foreach($years as $tahun)
                <option value="{{ $tahun }}" {{ $tahun == $currentYear ? 'selected' : '' }}>
                    {{ $tahun }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="row gx-5 gx-xl-10 mt-10">
        <div class="col-xxl-6 mb-5 mb-xl-10">
            @include('partials/widgets/charts/_widget-status-perbaikan')
        </div>
        <div class="col-xl-6 mb-5 mb-xl-10">
            @include('partials/widgets/charts/_widget-tren-bulanan')
        </div>
    </div>

    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-xl-6">
            @include('partials/widgets/charts/_widget-lokasi-terbanyak')
        </div>
        <div class="col-xl-6">
            @include('partials/widgets/charts/_widget-internal-eksternal')
        </div>
    </div>
    <script>
        document.getElementById('filter-tahun').addEventListener('change', function() {
            const tahun = this.value;

            fetch(`{{ route('dashboard.index') }}?year=${tahun}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {

                // 🔄 Update chart Status Perbaikan
                statusSeries.data.setAll(
                    data.statusPerbaikan.map(item => ({
                        status: item.status_perbaikan ?? "Tidak Diketahui",
                        value: item.total
                    }))
                );

                // 🔄 Update chart Tren Bulanan
                const bulanLabels = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
                const rawData = {};
                data.trenBulanan.forEach(item => {
                    const month = (new Date(item.bulan + "-01")).getMonth();
                    rawData[month] = item.total;
                });
                const finalData = bulanLabels.map((b, i) => ({
                    bulan: b,
                    total: rawData[i] ?? 0
                }));
                trenSeries.data.setAll(finalData);
                trenXAxis.data.setAll(finalData);

                // 🔄 Update table Lokasi Terbanyak
                let tbody = document.querySelector("#lokasi-table-body");
                tbody.innerHTML = "";
                if (data.topLokasi.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>`;
                } else {
                    data.topLokasi.forEach((item, idx) => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${idx+1}</td>
                                <td>${item.lokasi}</td>
                                <td class="text-end fw-bold">${item.total}</td>
                            </tr>
                        `;
                    });
                }

                // 🔄 Update chart Internal vs Eksternal
                internalSeries.data.setAll(
                    data.internalExternal.map(item => ({
                        jenis: item.internal_external ?? "Unknown",
                        value: item.total
                    }))
                );
            });
        });
    </script>
</x-default-layout>
