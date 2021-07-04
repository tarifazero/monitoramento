<x-app-layout>

    <section class="bg-pink">
        <div class="flex flex-wrap lg:flex-nowrap items-center justify-between text-center text-yellow font-helsinki uppercase py-6 container">

            <div class="w-full lg:w-1/5">
                @svg('logo', 'max-w-xs w-full h-auto mx-auto')
                <h1 class="sr-only">Cadê meu busão?</h1>
            </div>

            <p class="lg:text-2xl w-full lg:w-3/5 mt-4 lg:mt-0">
                Monitoramento da oferta de ônibus no transporte coletivo de Belo Horizonte
            </p>

        </div>
    </section>

    <section class="py-12 container">

        <ul class="flex flex-wrap md:flex-nowrap space-y-8 md:space-y-0 md:space-x-8">
            <li class="w-full md:w-1/2 h-full">
                <a
                    href="#"
                    class="relative block text-xl text-center text-pink font-work font-bold uppercase h-full group"
                >

                    <span class="block absolute inset-0 bg-pink rounded-2xl mt-2 ml-2 -mb-2 -mr-2 opacity-0 group-hover:opacity-100"></span>

                    <span class="block relative bg-beige-light group-hover:bg-beige py-2 px-6 h-full border border-pink rounded-2xl">
                        Cumprimento de viagens
                    </span>

                </a>
            </li>

            <li class="w-full md:w-1/2 h-full">
                <a
                    href="{{ route('fleet') }}"
                    class="relative block text-xl text-center text-pink font-work font-bold uppercase h-full group"
                >

                    <span class="block absolute inset-0 bg-pink rounded-2xl mt-2 ml-2 -mb-2 -mr-2 opacity-0 group-hover:opacity-100"></span>

                    <span class="block relative bg-beige-light group-hover:bg-beige py-2 px-6 h-full border border-pink rounded-2xl">
                        Frota de ônibus
                    </span>

                </a>
            </li>
        </ul>

        <div class="text-center font-dm font-bold bg-beige-light p-4 lg:p-6 rounded-2xl mt-12">
            <p>
                Inserir aqui um texto sobre a ferramenta e a metodologia
            </p>
        </div>

    </section>

</x-app-layout>
