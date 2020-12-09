<div>
    @foreach ($this->trips as $direction)
        <table class="mx-auto mt-4">

            <thead>
                <tr>

                    <th class="text-center px-4 py-1">
                        ID viagem
                    </th>
                    <th class="text-center px-4 py-1">
                        ID do serviço
                    </th>

                    <th class="text-center px-4 py-1">
                        Saída prevista
                    </th>

                    <th class="text-center px-4 py-1">
                        Chegada prevista
                    </th>

                    <th class="text-center px-4 py-1">
                        Chegada efetiva
                    </th>

                    <th class="text-center px-4 py-1">
                        ID Ponto final
                    </th>

                </tr>
            </thead>

            <tbody>
                @foreach ($direction as $trip)
                    <tr>
                        <td class="text-center px-4 py-1">
                            {{ $trip->id }}
                        </td>

                        <td class="text-center px-4 py-1">
                            {{ $trip->service_id }}
                        </td>

                        <td class="text-center px-4 py-1">
                            {{ $trip->getDepartureStopTime()->departure_time }}
                        </td>

                        <td class="text-center px-4 py-1">
                            {{ $trip->getArrivalStopTime()->arrival_time }}
                        </td>

                        <td class="text-center px-4 py-1">
                            @php
                                $closestArrival = $this->getClosestArrival($trip);
                            @endphp

                            @if ($closestArrival)
                                {{ $closestArrival->timestamp->setTimezone(config('app.local_timezone'))->format('H:i:s') }}
                                -
                                {{ $closestArrival->vehicle_id }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="text-center px-4 py-1">
                            {{ $trip->getArrivalStopTime()->stop_id }}
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    @endforeach
</div>
