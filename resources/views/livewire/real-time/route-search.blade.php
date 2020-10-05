<div class="relative">

    <input
        wire:model.debounce.300ms="search"
        type="search"
        placeholder="Procure sua linha"
        class="text-lg bg-gray-100 border border-gray-400 rounded pl-4 pr-12 py-2 w-full"
    >

    <button
        type="submit"
        class="absolute top-0 right-0 flex items-center mr-4 w-8 h-full"
        wire:loading.remove
    >
        <x-css-search class="w-full h-auto" />
    </button>

    <div
        wire:loading
        class="absolute top-0 right-0 flex items-center mr-4 w-8 h-full"
    >
        <x-css-spinner class="w-full h-auto animate-spin" />
    </div>

    @if ($search)
        <ul class="absolute top-full left-0 bg-white border-l border-r border-b border-gray-300 rounded-b w-full shadow-md">
            @if ($this->routes)
                @foreach ($this->routes as $route)
                    <li>
                        <a
                            href="{{ route('routes.show', ['routeShortName' => $route->short_name]) }}"
                            class="block hover:bg-gray-100 p-4"
                        >
                            {{ $route->short_name }} - {{ $route->long_name }}
                        </a>
                    </li>
                @endforeach
            @else
                <li>
                    Nenhuma linha encontrada
                </li>
            @endif
        </ul>
    @endif

</div>
