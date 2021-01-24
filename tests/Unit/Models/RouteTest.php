<?php

namespace Tests\Unit\Models;

use App\Models\GtfsFetch;
use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function to_flat_tree_returns_flat_tree()
    {
        $parent = Route::factory()->create();

        $children = Route::factory()
            ->count(2)
            ->create(['parent_id' => $parent->id]);

        $tree = $parent->toFlatTree();

        $idArray = [$parent->id, $children[0]->id, $children[1]->id];

        $this->assertEquals($idArray, $tree->pluck('id')->toArray());
    }

    /** @test */
    function has_real_time_id_works()
    {
        $route = Route::factory()
            ->create(['real_time_id' => null]);

        $this->assertFalse($route->hasRealTimeId());

        $route = Route::factory()
            ->create(['real_time_id' => 123]);

        $this->assertTrue($route->hasRealTimeId());
    }

    /** @test */
    function has_real_time_id_checks_entire_tree()
    {
        $parent = Route::factory()
            ->create(['real_time_id' => null]);

        $children = Route::factory()
            ->state(new Sequence(
                [
                    'parent_id' => $parent->id,
                    'real_time_id' => null,
                ],
                [
                    'parent_id' => $parent->id,
                    'real_time_id' => 123,
                ]
            ))
            ->count(2)
            ->create();

        $this->assertTrue($parent->hasRealTimeId());
    }
}
