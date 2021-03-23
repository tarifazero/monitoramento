<div>
    <canvas
        x-data="{ chart: null}"
        x-init="() => {
            this.chart = new Chart($el, {
                type: 'line',
                data: {
                    labels: JSON.parse('{{ json_encode($labels) }}'),
                    datasets: [{
                        data: JSON.parse('{{ json_encode($values) }}'),
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
                                fontFamily: 'DM Sans',
                                fontStyle: 'bold',
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
                                fontFamily: 'DM Sans',
                                fontStyle: 'bold',
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
                },
            });
        }"
        height="500"
    ></canvas>
</div>
