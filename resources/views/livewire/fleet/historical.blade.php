<div class="bg-beige-light px-2 sm:px-4 lg:px-6 py-6 rounded-2xl">

    <div class="flex justify-end">
        <div class="flex bg-beige border border-pink rounded-2xl">

            <button
                wire:click="setDaysLimit(90)"
                type="button"
                class="text-xs sm:text-base font-work font-bold uppercase px-4 sm:px-6 lg:px-8 px-8 py-3 rounded-2xl @if ($daysLimit === 90) text-white bg-pink @endif"
            >
                Últimos 90 dias
            </button>

            <button
                wire:click="setDaysLimit(30)"
                type="button"
                class="text-xs sm:text-base font-work font-bold uppercase px-4 sm:px-6 lg:px-8 py-3 rounded-2xl @if ($daysLimit === 30) text-white bg-pink @endif"
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
                                    fontSize: 12,
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
                                    fontSize: 12,
                                    padding: 10,
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

                enquire.register('screen and (min-width: 728px)', {
                    match: () => {
                        $el.style.height = '500px';
                        this.chart.config.options.scales.yAxes[0].ticks.fontSize = 20;
                        this.chart.config.options.scales.yAxes[0].ticks.padding = 30;
                        this.chart.config.options.scales.xAxes[0].ticks.fontSize = 16;
                        this.chart.update();
                    },
                    unmatch: () => {
                        $el.style.height = '300px';
                        this.chart.config.options.scales.yAxes[0].ticks.fontSize = 12;
                        this.chart.config.options.scales.yAxes[0].ticks.padding = 10;
                        this.chart.config.options.scales.xAxes[0].ticks.fontSize = 12;
                        this.chart.update();
                    },
                });

                $wire.on('chartUpdated', (labels, values) => {
                    chart.config.data.labels = labels;
                    chart.config.data.datasets[0].data = values;
                    chart.update();
                });
            }"
            wire:ignore
            height="300"
        ></canvas>
    </div>
</div>
