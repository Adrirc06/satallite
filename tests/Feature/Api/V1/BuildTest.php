<?php

use App\Models\Chassis;
use App\Models\Cpu;
use App\Models\Drive;
use App\Models\Gpu;
use App\Models\Motherboard;
use App\Models\Psu;
use App\Models\Ram;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    // Setup typical components for testing
    // Sockets
    $socketAM4 = DB::table('sockets')->insertGetId(['name' => 'AM4']);
    $socketIntegrated = DB::table('sockets')->insertGetId(['name' => 'Intel Integrated']);

    // PSU Types, Form Factors, Ram Types, Drive Types, Chassis Types
    $psuType = DB::table('psu_types')->insertGetId(['name' => 'ATX']);
    $formFactor = DB::table('form_factors')->insertGetId(['name' => 'ATX']);
    $ramType = DB::table('ram_types')->insertGetId(['name' => 'DDR4']);
    $driveType = DB::table('drive_types')->insertGetId(['name' => 'NVMe']);
    $chassisType = DB::table('chassis_types')->insertGetId(['name' => 'ATX Mid Tower']);

    // Standard Motherboard
    $this->standardMobo = Motherboard::create([
        'name' => 'Basic AM4',
        'price' => 100,
        'max_memory' => 64,
        'socket_id' => $socketAM4,
        'form_factor_id' => $formFactor,
        'ram_type_id' => $ramType,
    ]);

    // Integrated Motherboard
    $this->integratedMobo = Motherboard::create([
        'name' => 'Integrated Board',
        'price' => 80,
        'max_memory' => 16,
        'socket_id' => $socketIntegrated,
        'form_factor_id' => $formFactor,
        'ram_type_id' => $ramType,
    ]);

    // CPUs
    $this->standardCpu = Cpu::create([
        'name' => 'Ryzen 5',
        'price' => 200,
        'cores' => 6,
        'tdp' => 65,
        'frequency' => 3.6,
        'max_frequency' => 4.2,
        'socket_id' => $socketAM4,
        'igpu_id' => null,
    ]);

    $igpu = DB::table('igpus')->insertGetId(['name' => 'Radeon Graphics']);
    $this->igpuCpu = Cpu::create([
        'name' => 'Ryzen 5G',
        'price' => 220,
        'cores' => 6,
        'tdp' => 65,
        'frequency' => 3.6,
        'max_frequency' => 4.2,
        'socket_id' => $socketAM4,
        'igpu_id' => $igpu,
    ]);

    // GPUs
    $this->gpu = Gpu::create([
        'name' => 'RTX 3060',
        'price' => 300,
        'tdp' => 170,
        'vram' => 12,
    ]);

    // Other components
    $this->ram = Ram::create(['name' => '16GB DDR4', 'price' => 60, 'cas_latency' => 16, 'size' => 16, 'modules' => 2, 'speed' => 3200, 'ram_type_id' => $ramType]);
    $this->psuGood = Psu::create(['name' => '600W PSU', 'price' => 70, 'power' => 600, 'psu_type_id' => $psuType]);
    $this->psuBad = Psu::create(['name' => '200W PSU', 'price' => 20, 'power' => 200, 'psu_type_id' => $psuType]);
    $this->drive = Drive::create(['name' => '1TB NVMe', 'price' => 80, 'size' => 1000, 'drive_type_id' => $driveType]);
    $this->chassis = Chassis::create(['name' => 'NZXT H510', 'price' => 80, 'chassis_type_id' => $chassisType]);
});

test('it blocks unauthenticated users', function () {
    $this->postJson('/api/v1/builds', [])
        ->assertStatus(401);
});

test('it requires basic fields to test validation', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/v1/builds', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'motherboard_id', 'ram_id', 'psu_id', 'drive_id', 'chassis_id']);
});

test('it requires cpu if motherboard has no integrated cpu', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/v1/builds', [
            'name' => 'Test Build',
            'motherboard_id' => $this->standardMobo->id,
            'ram_id' => $this->ram->id,
            'psu_id' => $this->psuGood->id,
            'drive_id' => $this->drive->id,
            'chassis_id' => $this->chassis->id,
            'gpu_id' => $this->gpu->id, // Provide a GPU just to test CPU error
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['cpu_id']);
});

test('it allows missing cpu if motherboard has integrated cpu', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/v1/builds', [
            'name' => 'Integrated Build',
            'motherboard_id' => $this->integratedMobo->id, // Has 'integrated' socket
            'ram_id' => $this->ram->id,
            'psu_id' => $this->psuGood->id,
            'drive_id' => $this->drive->id,
            'chassis_id' => $this->chassis->id,
            'gpu_id' => $this->gpu->id,
        ])
        ->assertSuccessful();
});

test('it requires gpu if cpu has no integrated graphics', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/v1/builds', [
            'name' => 'Test Build',
            'motherboard_id' => $this->standardMobo->id,
            'cpu_id' => $this->standardCpu->id, // This CPU has no IGPU
            'ram_id' => $this->ram->id,
            'psu_id' => $this->psuGood->id,
            'drive_id' => $this->drive->id,
            'chassis_id' => $this->chassis->id,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['gpu_id']);
});

test('it allows missing gpu if cpu has integrated graphics', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/v1/builds', [
            'name' => 'Test Build',
            'motherboard_id' => $this->standardMobo->id,
            'cpu_id' => $this->igpuCpu->id, // This CPU has IGPU
            'ram_id' => $this->ram->id,
            'psu_id' => $this->psuGood->id,
            'drive_id' => $this->drive->id,
            'chassis_id' => $this->chassis->id,
        ])
        ->assertSuccessful();
});

test('it prevents saving if TDP exceeds PSU power', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/v1/builds', [
            'name' => 'Overpowered Build',
            'motherboard_id' => $this->standardMobo->id,
            'cpu_id' => $this->standardCpu->id, // 65W
            'gpu_id' => $this->gpu->id, // 170W
            'ram_id' => $this->ram->id,
            'psu_id' => $this->psuBad->id, // 200W (65+170 = 235W > 200W)
            'drive_id' => $this->drive->id,
            'chassis_id' => $this->chassis->id,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['psu_id']);
});

test('it creates the build successfully when everything is valid', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/v1/builds', [
            'name' => 'Perfect Build',
            'is_public' => true,
            'motherboard_id' => $this->standardMobo->id,
            'cpu_id' => $this->standardCpu->id,
            'gpu_id' => $this->gpu->id,
            'ram_id' => $this->ram->id,
            'psu_id' => $this->psuGood->id,
            'drive_id' => $this->drive->id,
            'chassis_id' => $this->chassis->id,
        ])
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Perfect Build')
        ->assertJsonPath('data.components.cpu.id', $this->standardCpu->id);

    $this->assertDatabaseHas('builds', [
        'name' => 'Perfect Build',
        'cpu_id' => $this->standardCpu->id,
        'user_id' => $user->id,
    ]);
});
