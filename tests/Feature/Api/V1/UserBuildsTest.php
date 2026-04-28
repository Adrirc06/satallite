<?php

use App\Models\Build;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $socketId = DB::table('sockets')->insertGetId(['name' => 'AM4']);
    $psuTypeId = DB::table('psu_types')->insertGetId(['name' => 'ATX']);
    $formFactorId = DB::table('form_factors')->insertGetId(['name' => 'ATX']);
    $ramTypeId = DB::table('ram_types')->insertGetId(['name' => 'DDR4']);
    $driveTypeId = DB::table('drive_types')->insertGetId(['name' => 'NVMe']);
    $chassisTypeId = DB::table('chassis_types')->insertGetId(['name' => 'ATX Mid Tower']);

    $this->motherboardId = DB::table('motherboards')->insertGetId([
        'name' => 'Basic AM4',
        'price' => 100,
        'max_memory' => 64,
        'socket_id' => $socketId,
        'form_factor_id' => $formFactorId,
        'ram_type_id' => $ramTypeId,
    ]);

    $this->ramId = DB::table('rams')->insertGetId([
        'name' => '16GB DDR4',
        'price' => 60,
        'cas_latency' => 16,
        'size' => 16,
        'modules' => 2,
        'speed' => 3200,
        'ram_type_id' => $ramTypeId,
    ]);

    $this->psuId = DB::table('psus')->insertGetId([
        'name' => '600W PSU',
        'price' => 70,
        'power' => 600,
        'psu_type_id' => $psuTypeId,
    ]);

    $this->driveId = DB::table('drives')->insertGetId([
        'name' => '1TB NVMe',
        'price' => 80,
        'size' => 1000,
        'drive_type_id' => $driveTypeId,
    ]);

    $this->chassisId = DB::table('chassis')->insertGetId([
        'name' => 'NZXT H510',
        'price' => 80,
        'chassis_type_id' => $chassisTypeId,
    ]);

    $this->user = User::factory()->create();
});

/**
 * @param  array<string, mixed>  $overrides
 */
function createBuild(array $overrides = []): Build
{
    return Build::create(array_merge([
        'name' => fake()->word(),
        'is_public' => true,
        'user_id' => test()->user->id,
        'motherboard_id' => test()->motherboardId,
        'ram_id' => test()->ramId,
        'psu_id' => test()->psuId,
        'drive_id' => test()->driveId,
        'chassis_id' => test()->chassisId,
    ], $overrides));
}

test('it returns builds for a user with correct structure', function () {
    createBuild(['name' => 'My Build']);

    $this->getJson("/api/v1/users/{$this->user->id}/builds")
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'My Build')
        ->assertJsonStructure([
            'data' => [['id', 'name', 'is_public', 'components', 'ratings_avg_rating', 'ratings_count']],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);
});

test('it paginates builds by per_page parameter', function () {
    foreach (range(1, 8) as $i) {
        createBuild(['name' => "Build {$i}"]);
    }

    $response = $this->getJson("/api/v1/users/{$this->user->id}/builds?per_page=6");

    $response->assertSuccessful()
        ->assertJsonCount(6, 'data')
        ->assertJsonPath('meta.per_page', 6)
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.last_page', 2)
        ->assertJsonPath('meta.total', 8);
});

test('it returns second page of builds', function () {
    foreach (range(1, 8) as $i) {
        createBuild(['name' => "Build {$i}"]);
    }

    $response = $this->getJson("/api/v1/users/{$this->user->id}/builds?per_page=6&page=2");

    $response->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.current_page', 2);
});

test('it caps per_page at 50', function () {
    $this->getJson("/api/v1/users/{$this->user->id}/builds?per_page=999")
        ->assertSuccessful()
        ->assertJsonPath('meta.per_page', 50);
});

test('it returns private and public builds for a user without filter', function () {
    createBuild(['is_public' => true]);
    createBuild(['is_public' => false]);

    $this->getJson("/api/v1/users/{$this->user->id}/builds")
        ->assertSuccessful()
        ->assertJsonCount(2, 'data');
});

test('it filters to only public builds when is_public=1', function () {
    createBuild(['is_public' => true]);
    createBuild(['is_public' => true]);
    createBuild(['is_public' => false]);

    $this->getJson("/api/v1/users/{$this->user->id}/builds?is_public=1")
        ->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.is_public', true)
        ->assertJsonPath('data.1.is_public', true);
});
