<div class="bg-beige-light p-6 rounded-2xl">

    <div class="flex justify-end">
        <div class="flex bg-beige border border-pink rounded-2xl">

            <button
                wire:click="setDaysLimit(90)"
                type="button"
                class="font-work font-bold uppercase px-8 py-3 rounded-2xl @if ($daysLimit === 90) text-white bg-pink @endif"
            >
                Últimos 90 dias
            </button>

            <button
                wire:click="setDaysLimit(30)"
                type="button"
                class="font-work font-bold uppercase px-8 py-3 rounded-2xl @if ($daysLimit === 30) text-white bg-pink @endif"
            >
                Últimos 30 dias
            </button>

        </div>
    </div>

    <div class="mt-6">
        <canvas
            x-data="{
                chart: null,
                labels: {{ json_encode($this->labels) }},
                data: {{ json_encode($this->values) }},
            }"
            x-init="() => {
                chart = new Chart($el, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            borderColor: '#E6007D',
                            borderWidth: 6,
                            backgroundColor: 'transparent',
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    color: '#E6007D',
                                    zeroLineColor: '#E6007D',
                                    drawBorder: false,
                                },
                                ticks: {
                                    fontStyle: 700,
                                    fontColor: '#000000',
                                    fontSize: 16,
                                    autoSkip: true,
                                    maxTicksLimit: 6,
                                },
                            }],
                            yAxes: [{
                                gridLines: {
                                    color: '#E6007D',
                                    zeroLineColor: '#E6007D',
                                    drawBorder: false,
                                },
                                ticks: {
                                    beginAtZero: true,
                                    max: 100,
                                    stepSize: 25,
                                    fontStyle: 700,
                                    fontColor: '#000000',
                                    fontSize: 20,
                                    padding: 30,
                                    callback: (tick) => tick + '%',
                                },
                            }],
                        },
                        legend: {
                            display: false,
                        },
                        tooltips: {
                            backgroundColor: '#EC008C',
                            titleFontSize: 20,
                            titleFontFamily: 'Work Sans',
                            titleFontStyle: 700,
                            titleAlign: 'center',
                            titleMarginBottom: 10,
                            bodyFontSize: 28,
                            bodyFontFamily: 'Work Sans',
                            bodyFontStyle: 700,
                            bodyAlign: 'center',
                            xPadding: 24,
                            yPadding: 16,
                            displayColors: false,
                            callbacks: {
                                title: (title) => title[0].label.toUpperCase(),
                                label: (label) => label.yLabel + '%',
                            },
                        },
                    },
                });

                $wire.on('chartUpdated', (labels, values) => {
                    chart.config.data.labels = labels;
                    chart.config.data.datasets[0].data = values;
                    chart.update();
                });
            }"
            wire:ignore
            height="500"
        ></canvas>
    </div>
</div>
