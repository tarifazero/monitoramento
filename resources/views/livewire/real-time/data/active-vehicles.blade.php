<div>

    <p class="text-center">
        Veículos rodando: {{ $this->currentActiveVehicleCount }}
    </p>

    <table class="mx-auto mt-4">

        <thead>
            <tr>

                <th class="text-center px-4 py-1">
                    Hora
                </th>

                @if ($route)
                    <th class="text-center px-4 py-1">
                        Número de veículos
                    </th>

                @else
                    <th class="text-center px-4 py-1">
                        Percentual da frota
                    </th>
                @endif

            </tr>
        </thead>

        <tbody>
            @foreach ($this->statsByHour as $hour => $stats)
                <tr>

                    <td class="text-center px-4 py-1">
                        {{ $hour }}
                    </td>

                    @if ($route)
                        <td class="text-center px-4 py-1">
                            {{ $stats['vehicle_count'] }}
                        </td>
                    @else
                        <td class="text-center px-4 py-1">
                            @percentage($stats['vehicle_percentage'])
                        </td>
                    @endif

                </tr>
            @endforeach
        </tbody>

    </table>

</div>
