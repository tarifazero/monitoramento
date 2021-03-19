<div class="pt-12 pb-20 container">

    <h1 class="text-2xl text-white text-center font-work font-bold uppercase bg-pink py-2 rounded-2xl">
        Frota de ônibus
    </h1>

    <section class="flex flex-wrap lg:flex-nowrap space-y-6 lg:space-y-0 lg:space-x-8 mt-12">

        <div class="flex flex-col font-dm font-bold bg-pink w-full lg:w-1/2 rounded-2xl">

            <div class="relative flex flex-col items-center justify-center bg-white p-4 lg:p-6 flex-grow rounded-2xl">
                <p class="text-center">
                    Em 2008, início do atual contrato de concessão do transporte coletivo de Belo Horizonte, a frota total de ônibus era de XXXX ônibus. Em 2017, este número passou para XXXX.
                </p>

                <div class="absolute bottom-0 left-1/2 bg-white w-4 h-4 transform rotate-45 translate-y-1/2 -translate-x-1/2"></div>
            </div>

            <div class="px-4 lg:px-6 pt-3 pb-2 flex-shrink-0">
                <p class="text-center text-white">

                    <span class="block">
                        Hoje, este número está em
                    </span>

                    <span class="block text-4xl font-work font-extrabold">
                        {{ $this->activeFleet }}
                    </span>

                </p>
            </div>

        </div>

        <div class="flex flex-col font-dm font-bold bg-white w-full lg:w-1/2 rounded-2xl">

            <div class="flex bg-yellow-medium flex-grow rounded-2xl">

                <div class="relative text-center bg-yellow-dark p-4 lg:p-6 w-3/5 rounded-2xl">
                    <p class="flex flex-col items-center justify-center h-full">

                        <span>
                            Neste exato momento,
                        </span>

                        <span class="block text-4xl font-work font-extrabold">
                            {{ $this->activeVehicles }}
                        </span>

                        <span>
                            ônibus estão circulando na cidade
                        </span>

                    </p>

                    <div class="absolute right-0 top-1/2 bg-yellow-dark w-4 h-4 transform rotate-45 -translate-y-1/2 translate-x-1/2"></div>
                </div>

                <div class="relative text-center p-4 lg:p-6 w-2/5">
                    <p class="flex flex-col items-center justify-center h-full">

                        <span>
                            Isto representa
                        </span>

                        <span class="block text-4xl font-work font-extrabold">
                            {{ $this->activeVehiclesPercentage }}%
                        </span>

                        <span>
                            da frota total disponível
                        </span>

                    </p>

                    <div class="absolute left-1/2 bottom-0 bg-yellow-medium w-4 h-4 transform rotate-45 translate-y-1/2 -translate-x-1/2"></div>
                </div>

            </div>

            <div class="text-center px-4 lg:px-6 pt-3 pb-2 flex-shrink-0">
                <p>
                    Saiba quais foram os níveis de ônibus circulando ontem de hora em hora!
                </p>
            </div>

        </div>

    </section>

</div>
