<div class="mt-12">

    <h2 class="text-center text-2xl font-bold leading-tight">
        @if ($this->route)
            {{ $this->route->short_name }} - {{ $this->route->long_name }}
        @else
            Dados globais
        @endif
    </h2>

    <div wire:poll.60s class="mt-6">

        <livewire:real-time.data.forecast-trips
            :start-time="$this->localizedStartTime"
            :end-time="$this->localizedEndTime"
            :route="$this->route"
        />

        <livewire:real-time.data.active-vehicles
            :start-time="$this->localizedStartTime"
            :end-time="$this->localizedEndTime"
            :route="$this->route"
        />

    </div>

</div>
