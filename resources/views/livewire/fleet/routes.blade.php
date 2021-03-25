<div>
    <div class="bg-beige-light p-6 rounded-2xl">
        <h2 class="text-center font-dm font-bold">
            Saiba quantos ônibus estão circulando por linha tem tempo real:
        </h2>
    </div>

    <div class="relative mt-12">

        <div class="absolute top-0 right-0 w-1/2 mt-10">
            <div class="bg-yellow-dark p-6 w-2/3 border border-black rounded-xl">

                <input
                    wire:model="search"
                    type="search"
                    placeholder="Procure uma linha"
                    class="bg-yellow-light py-2 px-6 w-full rounded-2xl"
                >

                <dl class="font-bold mt-6 mx-auto">
                    @foreach ($this->routes as $route)
                        <div class="flex space-x-10">

                            <dt class="text-center w-1/2">
                                {{ $route->get('short_name') }}
                            </dt>

                            <dl class="text-center uppercase w-1/2">
                                {{ $route->get('active_vehicles') }}
                                ônibus
                            </dl>

                        </div>
                    @endforeach
                </dl>

            </div>
        </div>

        @svg('ponto', 'w-full h-auto')

    </div>

</div>
