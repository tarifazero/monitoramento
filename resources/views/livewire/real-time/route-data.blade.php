<div class="mt-12">

    <h2 class="text-center text-2xl font-bold leading-tight">
        @if ($route)
            {{ $route->short_name }} - {{ $route->long_name }}
        @else
            Dados globais
        @endif
    </h2>

    <div wire:poll.60s class="mt-6">

        <p class="text-center">
            Veículos rodando: {{ $this->currentVehicleCount }}
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

                    <th class="text-center px-4 py-1">
                        Média móvel (mensal)
                    </th>

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

                            <td class="text-center px-4 py-1">
                                {{ $stats['average_vehicle_count'] }}
                            </td>
                        @else
                            <td class="text-center px-4 py-1">
                                {{ $stats['vehicle_percentage'] }}%
                            </td>

                            <td class="text-center px-4 py-1">
                                {{ $stats['average_vehicle_percentage'] }}%
                            </td>
                        @endif

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
