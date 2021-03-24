<div class="flex text-xl font-dm font-bold bg-beige-light flex-grow rounded-2xl">

    <div class="relative text-center bg-yellow-dark p-4 lg:p-6 w-3/5 rounded-2xl">
        <p class="flex flex-col items-center justify-center h-full">

            <span>
                Ontem, a média diária da frota circulante foi de
            </span>

            <span class="block text-4xl font-work font-extrabold">
                {{ $this->dailyAverageActiveFleet->last()['value'] }}%
            </span>

        </p>

        <div class="absolute right-0 top-1/2 bg-yellow-dark w-4 h-4 transform rotate-45 -translate-y-1/2 translate-x-1/2"></div>
    </div>

    <div class="relative text-center p-4 lg:p-6 w-2/5">
        <p class="flex flex-col items-center justify-center h-full">
            Compare este valor com as médias dos últimos dias!
        </p>
    </div>

</div>
