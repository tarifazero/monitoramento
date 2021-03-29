<div>
    <div class="bg-beige-light px-2 sm:px-4 lg:px-6 py-6 rounded-2xl">

        <h2 class="text-3xl sm:text-4xl text-center font-work font-extrabold">
            {{ $this->yesterday->format('d/m/Y') }}
        </h2>

        <div class="mt-6">
            <canvas
                x-data="{ chart: null }"
                x-init="() => {
                    chart = new Chart($el, {
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
                                        fontSize: 12,
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
                            chart.config.options.scales.yAxes[0].ticks.fontSize = 20;
                            chart.config.options.scales.yAxes[0].ticks.padding = 30;
                            chart.config.options.scales.xAxes[0].ticks.fontSize = 16;
                            chart.update();
                        },
                        unmatch: () => {
                            $el.style.height = '300px';
                            chart.config.options.scales.yAxes[0].ticks.fontSize = 12;
                            chart.config.options.scales.yAxes[0].ticks.padding = 10;
                            chart.config.options.scales.xAxes[0].ticks.fontSize = 12;
                            chart.update();
                        },
                    });
                }"
                height="300"
            ></canvas>
        </div>

        <div class="hidden md:flex space-x-1 lg:space-x-3 xl:space-x-4 ml-20 px-1">
            @foreach ($this->hourlyActiveFleet->pluck('opacity') as $opacity)
                <span class="flex-1" style="opacity: {{ $opacity }}">
                    @svg('wheel', 'w-full h-auto')
                </span>
            @endforeach
        </div>

    </div>

    <div class="flex text-sm sm:text-base lg:text-xl font-dm font-bold bg-beige-light flex-grow rounded-2xl mt-12">

        <div class="relative text-center bg-yellow-dark p-4 lg:p-6 w-3/5 rounded-2xl">
            <p class="flex flex-col items-center justify-center h-full">

                <span>
                    Ontem, a média diária da frota circulante foi de
                </span>

                <span class="block text-3xl sm:text-4xl font-work font-extrabold">
                    {{ $this->averageActiveFleet }}%
                </span>

            </p>

            <div class="absolute right-0 top-1/2 bg-yellow-dark w-4 h-4 transform rotate-45 -translate-y-1/2 translate-x-1/2"></div>
        </div>

        <div class="relative text-center p-4 lg:p-6 w-2/5">
            <p class="flex flex-col items-center justify-center h-full">
                Compare este valor com as médias dos últimos dias!
            </p>
        </div>

    </div>

</div>
