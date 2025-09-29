<div class="card">
    <div class="card-header"><h3 class="card-title">Internal vs Eksternal</h3></div>
    <div class="card-body"><div id="chartInternalEksternal" style="height: 400px;"></div></div>
</div>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
    am5.ready(function () {
        let root = am5.Root.new("chartInternalEksternal");
        root.setThemes([am5themes_Animated.new(root)]);

        let chart = root.container.children.push(am5percent.PieChart.new(root, {
            layout: root.verticalLayout
        }));

        window.internalSeries = chart.series.push(am5percent.PieSeries.new(root, {
            valueField: "value",
            categoryField: "jenis"
        }));

        internalSeries.data.setAll([
            @foreach ($internalExternal as $item)
                { jenis: "{{ ucfirst($item->internal_external) ?? 'Unknown' }}", value: {{ $item->total }} },
            @endforeach
        ]);

        internalSeries.appear(1000, 100);
    });
</script>
