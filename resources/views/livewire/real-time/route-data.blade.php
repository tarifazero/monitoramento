<div>
    @if ($route)
        <h2>
            Dados para a rota: {{ $route->short_name }} - {{ $route->long_name }}
        </h2>

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
    @endif
</div>
