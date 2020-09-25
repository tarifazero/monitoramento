<div>
    @if ($route)
        <h2>
            Dados para a rota: {{ $route->short_name }} - {{ $route->long_name }}
        </h2>

        <p>
            Veículos rodando: {{ $this->vehicleCount }}
        </p>
    @endif

</div>
