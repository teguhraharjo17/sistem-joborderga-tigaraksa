<div class="card card-flush h-md-100">
    <div class="card-header pt-7">
        <h3 class="card-title fw-bold text-dark fs-3">Tren Kerusakan per Bulan</h3>
    </div>
    <div class="card-body">
        <div id="tren-kerusakan-chart" style="height: 400px;"></div>
    </div>
</div>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
    am5.ready(function () {
        const existing = am5.registry.rootElements.find(r => r.dom.id === "tren-kerusakan-chart");
        if (existing) existing.dispose();

        let root = am5.Root.new("tren-kerusakan-chart");
        root.setThemes([am5themes_Animated.new(root)]);

        let chart = root.container.children.push(am5xy.XYChart.new(root, {
            layout: root.verticalLayout
        }));

        chart.set("cursor", am5xy.XYCursor.new(root, { behavior: "none" }));

        const bulanLabels = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
        const rawData = {
            @foreach ($trenBulanan as $item)
                "{{ \Carbon\Carbon::parse($item->bulan . '-01')->format('m') }}" : {{ $item->total }},
            @endforeach
        };
        const data = Array.from({ length: 12 }, (_, i) => {
            const bulan = (i + 1).toString().padStart(2, '0');
            return { bulan: bulanLabels[i], total: rawData[bulan] ?? 0 };
        });

        const maxY = Math.max(...data.map(d => d.total));
        const paddedMax = Math.ceil((maxY + (maxY * 0.1)) / 5) * 5;

        window.trenXAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
            categoryField: "bulan",
            renderer: am5xy.AxisRendererX.new(root, {
                minGridDistance: 30,
                startLocation: 0.1,
                endLocation: 0.9
            })
        }));

        let yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            min: 0,
            max: paddedMax,
            strictMinMax: true,
            renderer: am5xy.AxisRendererY.new(root, {})
        }));

        window.trenSeries = chart.series.push(am5xy.LineSeries.new(root, {
            name: "Jumlah",
            xAxis: trenXAxis,
            yAxis: yAxis,
            valueYField: "total",
            categoryXField: "bulan",
            stroke: am5.color(0x3b82f6),
            tension: 0.6,
            tooltip: am5.Tooltip.new(root, { labelText: "{categoryX}: {valueY.formatNumber('#')}" })
        }));

        trenSeries.strokes.template.setAll({ strokeWidth: 3 });
        trenSeries.fills.template.setAll({ visible: true, fillOpacity: 0.2, fill: am5.color(0x3b82f6) });

        // Tambahkan angka di atas titik
        trenSeries.bullets.push(function (root, series, dataItem) {
            return am5.Bullet.new(root, {
                locationY: 1,
                sprite: am5.Label.new(root, {
                    text: "{valueY}",
                    centerY: am5.p50,
                    centerX: am5.p50,
                    populateText: true,
                    fontSize: 12,
                    fill: am5.color(0x000000),
                    dy: -10
                })
            });
        });

        trenXAxis.data.setAll(data);
        trenSeries.data.setAll(data);

        chart.appear(1000);
        trenSeries.appear(1000);
    });
</script>
