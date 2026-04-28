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
});

test('it stores a rating without crashing and returns the saved payload', function () {
    $user = User::factory()->create();
    $build = Build::create([
        'name' => 'Test Build',
        'is_public' => true,
        'user_id' => $user->id,
        'motherboard_id' => $this->motherboardId,
        'ram_id' => $this->ramId,
        'psu_id' => $this->psuId,
        'drive_id' => $this->driveId,
        'chassis_id' => $this->chassisId,
    ]);

    $response = $this->actingAs($user)->postJson("/api/v1/builds/{$build->id}/ratings", [
        'rating' => 80,
    ]);

    $response->assertSuccessful()
        ->assertJsonPath('data.rating', 80)
        ->assertJsonPath('data.build_id', $build->id)
        ->assertJsonStructure([
            'data' => [
                'id',
                'rating',
                'user' => ['id'],
                'build_id',
                'created_at',
            ],
        ]);
});
