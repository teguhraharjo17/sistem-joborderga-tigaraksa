<div class="card">
    <div class="card-header"><h3 class="card-title">Status Perbaikan</h3></div>
    <div class="card-body"><div id="chartStatusPerbaikan" style="height: 400px;"></div></div>
</div>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
    am5.ready(function () {
        let root = am5.Root.new("chartStatusPerbaikan");
        root.setThemes([am5themes_Animated.new(root)]);

        let chart = root.container.children.push(am5percent.PieChart.new(root, {
            layout: root.verticalLayout
        }));

        window.statusSeries = chart.series.push(am5percent.PieSeries.new(root, {
            valueField: "value",
            categoryField: "status"
        }));

        statusSeries.data.setAll([
            @foreach ($statusPerbaikan as $item)
                { status: "{{ $item->status_perbaikan ?? 'Tidak Diketahui' }}", value: {{ $item->total }} },
            @endforeach
        ]);

        statusSeries.appear(1000, 100);
    });
</script>
