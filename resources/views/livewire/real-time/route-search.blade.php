<div>
    <input wire:model.debounce.300ms="search" type="search" placeholder="Procure sua linha">

    <div wire:loading>
        Carregando...
    </div>

    @if ($this->routes)
        <ul>
            @foreach ($this->routes as $route)
                <li>
                    <a href="#" wire:click.prevent="selectRoute({{ $route->id }})">
                        {{ $route->short_name }} - {{ $route->long_name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @elseif ($search)
        <p>
            Nenhuma linha encontrada
        </p>
    @else
        <p>
            Digite um termo de busca
        </p>
    @endif
</div>
