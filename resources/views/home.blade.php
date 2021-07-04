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

        <div class="text-center font-dm font-bold bg-beige-light p-4 space-y-6 lg:p-6 rounded-2xl mt-12">
            <p>
                Cadê Meu Busão é uma ferramenta que busca dar mais transparência aos dados relacionados à oferta do transporte coletivo por ônibus em Belo Horizonte. Todo o código é aberto, de livre uso e disponível no nosso Github. As informações consideradas são disponibilizadas pela Prefeitura de Belo Horizonte no portal de dados abertos da BHTRANS.
            </p>

            <p>
                No painel de frota de ônibus é possível acompanhar diversas informações: o total da frota ativa do município; quantos ônibus de cada linha estão circulando em tempo real; a média diária de circulação dos veículos nos últimos 90 dias; e o percentual de utilização da frota total, por faixa de hora, no último dia.
            </p>

            <p>
                As informações em tempo real são extraídas do Tempo Real Ônibus - Coordenada atualizada, que contém as localizações dos ônibus em circulação na cidade, atualizadas a cada 20 segundos. Já o total da frota ativa é calculado a partir da quantidade total de veículos identificada no mês anterior.
            </p>

            <p>
                Esperamos que a ferramenta ajude passageiras e passageiros a ficarem de olho no busão e exigirem seus direitos. Em breve mais novidades!
            </p>
        </div>

    </section>

</x-app-layout>
