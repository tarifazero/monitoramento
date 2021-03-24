<div class="bg-beige-light p-6 rounded-2xl">
    <h2 class="text-4xl text-center font-work font-extrabold">
        {{ $this->yesterday->format('d/m/Y') }}
    </h2>

    <div class="mt-6">
        <x-chart
            :labels="$this->hourlyActiveFleet->pluck('label')"
            :values="$this->hourlyActiveFleet->pluck('value')"
        />
    </div>
</div>
