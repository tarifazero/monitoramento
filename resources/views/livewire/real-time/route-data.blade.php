<div>
    <h2>
        @if ($route)
            Dados para a rota: {{ $route->short_name }} - {{ $route->long_name }}
        @else
            Dados globais
        @endif
    </h2>

    <div wire:poll.60s>
        <p>
            Veículos rodando: {{ $this->currentVehicleCount }}
        </p>

        <table>

            <thead>
                <tr>

                    <th>
                        Hora
                    </th>

                    <th>
                        Número de veículos
                    </th>

                </tr>
            </thead>

            <tbody>
                @foreach ($this->vehicleCountByHour as $hour => $count)
                    <tr>

                        <td>
                            {{ $hour }}
                        </td>

                        <td>
                            {{ $count }}
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
