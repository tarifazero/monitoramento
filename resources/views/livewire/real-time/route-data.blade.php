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

                    <th class="text-center px-4 py-1">
                        Número de veículos
                    </th>

                </tr>
            </thead>

            <tbody>
                @foreach ($this->vehicleCountByHour as $hour => $count)
                    <tr>

                        <td class="text-center px-4 py-1">
                            {{ $hour }}
                        </td>

                        <td class="text-center px-4 py-1">
                            {{ $count }}
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
