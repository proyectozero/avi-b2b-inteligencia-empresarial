<?php
function class_htmlChartJs($str_title, $str_type, $str_3d, $arr_xaxis, $arr_yaxis){
    $chart_id = generateUID();

    // Preparar datasets
    $labels = json_encode($arr_xaxis['data']);
    $datasets = [];
    $colors = ['#0d6efd', '#20c997', '#ffc107', '#dc3545', '#6f42c1'];

    foreach ($arr_yaxis['data'] as $i => $serie) {
        $datasets[] = [
            "label" => $arr_yaxis['labels'][$i] ?? "Serie " . ($i + 1),
            "data" => array_map('floatval', $serie),
            "borderColor" => $colors[$i % count($colors)],
            "backgroundColor" => $colors[$i % count($colors)],
            "fill" => ($str_type === 'area'),
        ];
    }

    $datasets_json = json_encode($datasets, JSON_NUMERIC_CHECK);

    // Preparar "selected" para los options
    $selected_line = ($str_type === 'line') ? 'selected' : '';
    $selected_bar = ($str_type === 'bar') ? 'selected' : '';
    $selected_pie = ($str_type === 'pie') ? 'selected' : '';
    $selected_doughnut = ($str_type === 'donut') ? 'selected' : '';
    $selected_radar = ($str_type === 'radar') ? 'selected' : '';
    $selected_polar = ($str_type === 'polarArea') ? 'selected' : '';

    $html = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="col-md-6 mb-2">
        <label for="chartType_$chart_id" class="form-label text-white">Tipo de gráfico</label>
        <select id="chartType_$chart_id" class="form-select bg-transparent text-white border-secondary" onchange="updateChartType_$chart_id(this.value)">
            <option value="line" $selected_line>Línea</option>
            <option value="bar" $selected_bar>Barra</option>
            <option value="pie" $selected_pie>Pastel</option>
            <option value="doughnut" $selected_doughnut>Donut</option>
            <option value="radar" $selected_radar>Radar</option>
            <option value="polarArea" $selected_polar>Área Polar</option>
        </select>
    </div>

    <div class="col-md-6 form-check form-switch mb-2">
        <input class="form-check-input" type="checkbox" id="invertXY_$chart_id" onchange="toggleInvertXY_$chart_id(this)">
        <label class="form-check-label text-white" for="invertXY_$chart_id">Invertir datos X ↔ Y</label>
    </div>

    <div class="responsive-chart bg-transparent rounded p-3">
        <canvas id="chart_$chart_id" style="width:100%; aspect-ratio:16/9;"></canvas>
    </div>

    <script>
        let originalLabels_$chart_id = $labels;
        let originalDatasets_$chart_id = JSON.parse('$datasets_json');

        let invertXY_$chart_id = false;
        let chartInstance_$chart_id;

        function renderChart_$chart_id(type) {
            const ctx = document.getElementById('chart_$chart_id').getContext('2d');
            if (chartInstance_$chart_id) chartInstance_$chart_id.destroy();

            let labels = invertXY_$chart_id ? originalDatasets_$chart_id.map(ds => ds.label) : originalLabels_$chart_id;
            let datasets = invertXY_$chart_id
            ? originalLabels_$chart_id.map((label, idx) => {
                return {
                    label: label,
                    data: originalDatasets_$chart_id.map(ds => ds.data[idx]),
                    borderColor: dsColor_$chart_id(idx),
                    backgroundColor: dsColor_$chart_id(idx),
                    fill: (type === 'area')
                };
            })
            : originalDatasets_$chart_id;

            chartInstance_$chart_id = new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    backgroundColor: 'transparent',
                    plugins: {
                        legend: {
                            labels: { color: '#ffffff' }
                        },
                        title: {
                            display: true,
                            text: "$str_title",
                            color: '#ffffff'
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#ffffff' },
                            grid: { color: '#4F4F4F' }
                        },
                        y: {
                            ticks: { color: '#ffffff' },
                            grid: { color: '#4F4F4F' }
                        }
                    }
                }

            });
        }

        function updateChartType_$chart_id(type) {
            renderChart_$chart_id(type);
        }

        function toggleInvertXY_$chart_id(cb) {
            invertXY_$chart_id = cb.checked;
            renderChart_$chart_id(document.getElementById('chartType_$chart_id').value);
        }

        function dsColor_$chart_id(index) {
            const colors = ['#0d6efd', '#20c997', '#ffc107', '#dc3545', '#6f42c1'];
            return colors[index % colors.length];
        }

        renderChart_$chart_id("$str_type");
    </script>

    <style>
    .responsive-chart {
        width: 100%;
        aspect-ratio: 16 / 9;
        min-height: 300px;
    }
    canvas {
        background-color: transparent;
        border-radius: 0.5rem;
    }
    .bg-transparent{
       background-color: transparent; 
    }
    </style>
    HTML;

    return $html;
}
?>
