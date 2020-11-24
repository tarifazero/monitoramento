<div>
    @foreach ($this->trips->groupBy('direction_id') as $direction)
        <table class="mx-auto mt-4">

            <thead>
                <tr>

                    <th class="text-center px-4 py-1">
                        ID da viagem
                    </th>

                    <th class="text-center px-4 py-1">
                        Chegada prevista
                    </th>

                    <th class="text-center px-4 py-1">
                        Chegada efetiva
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
                            {{ $trip->getArrivalStopTime()->arrival_time }}
                        </td>

                        <td class="text-center px-4 py-1">
                            {{ $this->getClosestArrival($trip) }}
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    @endforeach
</div>
