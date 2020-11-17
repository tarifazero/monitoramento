<div class="mt-12">

    <h2 class="text-center text-2xl font-bold leading-tight">
        @if ($route)
            {{ $route->short_name }} - {{ $route->long_name }}
        @else
            Dados globais
        @endif
    </h2>

    <div class="mt-6">

        <livewire:real-time.data.forecast-trips
            :start-time="$this->localizedStartTime"
            :end-time="$this->localizedEndTime"
            :route="$route"
        />

        <livewire:real-time.data.active-vehicles
            :start-time="$this->localizedStartTime"
            :end-time="$this->localizedEndTime"
            :route="$route"
        />

        @if ($route)
            <livewire:real-time.data.trip-delays
                :start-time="$this->localizedStartTime"
                :end-time="$this->localizedEndTime"
                :route="$route"
            />
        @endif

    </div>

</div>
