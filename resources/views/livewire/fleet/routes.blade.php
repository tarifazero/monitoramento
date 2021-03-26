<div>
    <div class="bg-beige-light p-6 rounded-2xl">
        <h2 class="text-sm sm:text-base md:text-lg text-center font-dm font-bold">
            Saiba quantos ônibus estão circulando por linha tem tempo real:
        </h2>
    </div>

    <div class="relative max-w-screen-lg mx-auto mt-12">

        <div class="lg:absolute top-0 right-0 lg:w-1/2 lg:mt-10">
            <div class="bg-yellow-dark p-6 lg:w-2/3 border border-black rounded-xl">

                <div class="relative">
                    <input
                        wire:model.debounce.500ms="search"
                        type="search"
                        placeholder="Procure uma linha"
                        class="font-bold uppercase placeholder-black bg-yellow-light py-2 px-6 w-full rounded-2xl focus:outline-none focus:ring focus:ring-pink"
                    >
                    @svg('search', 'absolute top-1/2 right-0 w-5 h-auto rounded-md mr-2 transform -translate-y-1/2')
                </div>

                <div class="relative">
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

                    <div
                        wire:loading.flex
                        class="absolute inset-0 items-center justify-center bg-yellow-dark"
                    >
                        @svg('wheel', 'w-6 h-auto animate-spin')
                    </div>

                </div>

            </div>
        </div>

        @svg('ponto', 'hidden lg:block w-full h-auto')

    </div>

</div>
