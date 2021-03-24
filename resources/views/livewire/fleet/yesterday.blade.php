<div class="bg-beige-light p-6 rounded-2xl">
    <h2 class="text-4xl text-center font-work font-extrabold">
        {{ $this->yesterday->format('d/m/Y') }}
    </h2>

    <div class="mt-6">
        <canvas
            x-data="{ chart: null}"
            x-init="() => {
                this.chart = new Chart($el, {
                    type: 'line',
                    data: {
                        labels: JSON.parse('{{ json_encode($this->hourlyActiveFleet->pluck('label')) }}'),
                        datasets: [{
                            data: JSON.parse('{{ json_encode($this->hourlyActiveFleet->pluck('value')) }}'),
                            borderColor: '#FFCA44',
                            borderWidth: 6,
                            backgroundColor: 'rgba(255, 235, 107, 0.5)',
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                gridLines: {
                                    display:false,
                                },
                                ticks: {
                                    fontStyle: 700,
                                    fontColor: '#000000',
                                    fontSize: 16,
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
            }"
            height="500"
        ></canvas>
    </div>
</div>
