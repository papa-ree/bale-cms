@props(['chartData' => [], 'chartLabel' => []])

<div x-data="{
    chartCanvas: null,
    datas: @js($chartData),
    labels: @js($chartLabel),
    chart: null,
    initChart() {
        const datasets = this.datas.map(data => ({
            label: data.label,
            data: data.data,
            lineTension: 0.4,
            fill: false,
        }));
        const chartData = {
            labels: this.labels,
            datasets: datasets,
        };
        const chartOptions = {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    boxWidth: 80,
                    fontColor: 'white',
                },
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: -1,
                    ticks: {
                        display: false,
                        stepSize: 10,
                        callback: value => value,
                    },
                },
            },
            elements: {
                point: {
                    radius: 0,
                    hitRadius: 100,
                },
            },
        };
        this.chart = new Chart(this.chartCanvas, {
            type: 'line',
            data: chartData,
            options: chartOptions,
        });
    },
}" x-init="chartCanvas = $el.querySelector('#chartCanvasElement');
initChart()">
    <canvas id="chartCanvasElement"></canvas>
</div>
