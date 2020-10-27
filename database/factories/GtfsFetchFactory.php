<?php

namespace Database\Factories;

use App\Models\GtfsFetch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GtfsFetchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GtfsFetch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'path' => Str::random(40),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (GtfsFetch $gtfs) {
            Storage::disk(GtfsFetch::STORAGE_DISK)
                ->put($gtfs->path . '.zip', file_get_contents(base_path('tests/resources/gtfsfiles.zip')));
        });
    }
}
