<?php
function class_htmlGoogleCharts($str_title, $str_type, $str_3d, $arr_xaxis, $arr_yaxis){
    $accordion_id = generateUID();

    // Preparar datos
    $data = [];
    $data[] = array_merge([$arr_xaxis['label']], $arr_yaxis['labels']);
    $lenX = count($arr_xaxis['data']);

    foreach ($arr_yaxis['data'] as $idx => $serie) {
        if (count($serie) !== $lenX) {
            $arr_yaxis['data'][$idx] = array_pad($serie, $lenX, 0);
        }
    }

    foreach ($arr_xaxis['data'] as $i => $xVal) {
        $row = [$xVal];
        foreach ($arr_yaxis['data'] as $serie) {
            $value = isset($serie[$i]) ? $serie[$i] : 0;
            $row[] = is_numeric($value) ? floatval($value) : 0;
        }
        $data[] = $row;
    }

    $dataInvertida = [];
    $firstYLabel = $arr_yaxis['labels'][0] ?? '';
    $dataInvertida[] = array_merge([$firstYLabel], $arr_xaxis['data']);

    foreach ($arr_yaxis['data'] as $idx => $serie) {
        $row = [$arr_yaxis['labels'][$idx] ?? 'Serie ' . ($idx + 1)];
        foreach ($arr_xaxis['data'] as $i => $xVal) {
            $value = isset($serie[$i]) ? $serie[$i] : 0;
            $row[] = is_numeric($value) ? floatval($value) : 0;
        }
        $dataInvertida[] = $row;
    }

    $jsonData = json_encode($data, JSON_NUMERIC_CHECK);
    $jsonDataInvertida = json_encode($dataInvertida, JSON_NUMERIC_CHECK);

    $types = [
        'line' => '',
        'bar' => '',
        'column' => '',
        'pie' => '',
        'pie3d' => '',
        'donut' => '',
        'area' => '',
        'scatter' => '',
        'combo' => '',
        'bubble' => '',
        'gauge' => '',
        'histogram' => '',
        'stepped' => '',
    ];

    if (isset($types[$str_type])) {
        $types[$str_type] = 'selected';
    }

    $is3D = ($str_type === 'pie3d') ? 'true' : (($str_type === 'pie') ? ($str_3d ? 'true' : 'false') : 'false');

    $html = <<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        let currentType_{$accordion_id} = "$str_type";
        let invertXY_{$accordion_id} = false;

        google.charts.load('current', {packages:['corechart', 'sankey', 'gauge']});
        google.charts.setOnLoadCallback(drawChart_{$accordion_id});

        function drawChart_{$accordion_id}() {
            const rawData = invertXY_{$accordion_id} ? $jsonDataInvertida : $jsonData;
            try {
                const data = google.visualization.arrayToDataTable(rawData);
                const chartDiv = document.getElementById('chart_div_{$accordion_id}');

                const options = {
                    title: "$str_title",
                    colors: ['#0d6efd', '#20c997', '#ffc107', '#dc3545', '#6f42c1'],
                    backgroundColor: { fill: 'transparent' },
                    titleTextStyle: { color: '#ffffff' },
                    legend: { position: 'bottom', textStyle: { color: '#ffffff' } },
                    hAxis: { 
                    gridlines: { color: '#2F2F2F', count: 5 },       // Líneas principales (más visibles)
                    minorGridlines: { color: '#2F2F2F', count: 3 },  // Líneas menores (más tenues)
                    textStyle: { color: '#ffffff' }, 
                    titleTextStyle: { color: '#ffffff' } 
                },
                vAxis: { 
                    gridlines: { color: '#2F2F2F', count: 5 },
                    minorGridlines: { color: '#2F2F2F', count: 3 },
                    textStyle: { color: '#ffffff' }, 
                    titleTextStyle: { color: '#ffffff' } 
                },

                is3D: (currentType_{$accordion_id} === 'pie3d') ? true : false,
                pieHole: (currentType_{$accordion_id} === 'donut' ? 0.4 : 0),
                animation: {duration: 1000, easing: 'inAndOut', startup: true},
                chartArea: {
                    left: 50,
                    top: 50,
                    right: 20,
                    bottom: 80,
                    width: '90%',
                    height: '70%'
                }
            };



            let chart;
            switch(currentType_{$accordion_id}) {
            case 'line': chart = new google.visualization.LineChart(chartDiv); break;
            case 'bar': chart = new google.visualization.BarChart(chartDiv); break;
            case 'column': chart = new google.visualization.ColumnChart(chartDiv); break;
            case 'pie':
            case 'pie3d':
            case 'donut':
                chart = new google.visualization.PieChart(chartDiv); break;
            case 'area': chart = new google.visualization.AreaChart(chartDiv); break;
            case 'scatter': chart = new google.visualization.ScatterChart(chartDiv); break;
            case 'combo': chart = new google.visualization.ComboChart(chartDiv); break;
            case 'bubble': chart = new google.visualization.BubbleChart(chartDiv); break;
            case 'gauge': chart = new google.visualization.Gauge(chartDiv); break;
            case 'histogram': chart = new google.visualization.Histogram(chartDiv); break;
            case 'stepped': chart = new google.visualization.SteppedAreaChart(chartDiv); break;
            default: console.error("Tipo no soportado:", currentType_{$accordion_id}); return;
            }
            chart.draw(data, options);
        } catch(e) {
            console.error("Error al crear el gráfico:", e);
        }
    }

    function changeChartType_{$accordion_id}(select) {
        const chartDiv = document.getElementById('chart_div_{$accordion_id}');
        chartDiv.style.transition = "opacity 0.3s ease";
        chartDiv.style.opacity = 0;
        setTimeout(() => {
            currentType_{$accordion_id} = select.value;
            drawChart_{$accordion_id}();
            chartDiv.style.opacity = 1;
        }, 300);
    }

    function toggleInvertXY_{$accordion_id}(checkbox) {
        invertXY_{$accordion_id} = checkbox.checked;
        drawChart_{$accordion_id}();
    }

    window.addEventListener('resize', drawChart_{$accordion_id});
</script>

<div class="col-md-6 mb-2">
    <label for="chartType_{$accordion_id}" class="form-label text-white">Tipo de gráfico</label>
    <select id="chartType_{$accordion_id}" class="form-select bg-dark text-white border-secondary" onchange="changeChartType_{$accordion_id}(this)">
        <option value="line" {$types['line']}>Línea</option>
        <option value="bar" {$types['bar']}>Barra</option>
        <option value="column" {$types['column']}>Columnas</option>
        <option value="pie" {$types['pie']}>Pastel</option>
        <option value="pie3d" {$types['pie3d']}>Pastel 3D</option>
        <option value="donut" {$types['donut']}>Donut</option>
        <option value="area" {$types['area']}>Área</option>
        <option value="scatter" {$types['scatter']}>Dispersión</option>
        <option value="combo" {$types['combo']}>Combinado</option>
        <option value="bubble" {$types['bubble']}>Burbuja</option>
        <option value="gauge" {$types['gauge']}>Gauge</option>
        <option value="histogram" {$types['histogram']}>Histograma</option>
        <option value="stepped" {$types['stepped']}>Área escalonada</option>
    </select>
</div>

<div class="col-md-6 form-check form-switch mb-2">
    <input class="form-check-input" type="checkbox" id="invertXY_{$accordion_id}" onchange="toggleInvertXY_{$accordion_id}(this)">
    <label class="form-check-label text-white" for="invertXY_{$accordion_id}">Invertir datos X ↔ Y</label>
</div>

<style>
    .responsive-chart {
        width: 100%;
        aspect-ratio: 16 / 9;
        min-height: 300px;
    }
</style>

<div id="chart_div_{$accordion_id}" class="responsive-chart"></div>

HTML;

return $html;
}
?>
