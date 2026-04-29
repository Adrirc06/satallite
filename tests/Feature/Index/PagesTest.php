<?php

use App\Models\Build;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the home page successfully', function () {
    $this->get('/')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Index/Home')
        );
});

it('renders the pc builder page successfully', function () {
    $this->get('/builder')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Index/Builder')
        );
});

it('redirects guests to login when accessing profile', function () {
    $this->get('/profile')
        ->assertRedirect('/login');
});

it('renders the profile page for authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/profile')
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Index/Profile')
        );
});

it('renders the public profile page for any user', function () {
    $user = User::factory()->create();

    $this->get("/user/{$user->id}")
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Screens/PublicProfile')
            ->where('profileUser.id', $user->id)
            ->where('profileUser.name', $user->name)
        );
});

it('returns 404 for a non-existent public profile', function () {
    $this->get('/user/99999')
        ->assertNotFound();
});

it('redirects authenticated user to /profile when visiting their own public profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get("/user/{$user->id}")
        ->assertRedirect('/profile');
});

it('renders the build page and exposes user info', function () {
    $socketId = DB::table('sockets')->insertGetId(['name' => 'AM4']);
    $psuTypeId = DB::table('psu_types')->insertGetId(['name' => 'ATX']);
    $formFactorId = DB::table('form_factors')->insertGetId(['name' => 'ATX']);
    $ramTypeId = DB::table('ram_types')->insertGetId(['name' => 'DDR4']);
    $driveTypeId = DB::table('drive_types')->insertGetId(['name' => 'NVMe']);
    $chassisTypeId = DB::table('chassis_types')->insertGetId(['name' => 'ATX Mid Tower']);

    $owner = User::factory()->create();

    $build = Build::create([
        'name' => 'Mi Build Pública',
        'is_public' => true,
        'user_id' => $owner->id,
        'motherboard_id' => DB::table('motherboards')->insertGetId(['name' => 'Mobo', 'price' => 100, 'max_memory' => 32, 'socket_id' => $socketId, 'form_factor_id' => $formFactorId, 'ram_type_id' => $ramTypeId]),
        'ram_id' => DB::table('rams')->insertGetId(['name' => 'RAM', 'price' => 50, 'cas_latency' => 16, 'size' => 16, 'modules' => 2, 'speed' => 3200, 'ram_type_id' => $ramTypeId]),
        'psu_id' => DB::table('psus')->insertGetId(['name' => 'PSU', 'price' => 60, 'power' => 600, 'psu_type_id' => $psuTypeId]),
        'drive_id' => DB::table('drives')->insertGetId(['name' => 'Drive', 'price' => 70, 'size' => 1000, 'drive_type_id' => $driveTypeId]),
        'chassis_id' => DB::table('chassis')->insertGetId(['name' => 'Caja', 'price' => 80, 'chassis_type_id' => $chassisTypeId]),
    ]);

    $this->get("/build/{$build->id}")
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Index/Build')
            ->where('build.name', 'Mi Build Pública')
            ->where('build.user.id', $owner->id)
            ->where('build.user.name', $owner->name)
        );
});

it('blocks guests from viewing a private build', function () {
    $socketId = DB::table('sockets')->insertGetId(['name' => 'AM4']);
    $psuTypeId = DB::table('psu_types')->insertGetId(['name' => 'ATX']);
    $formFactorId = DB::table('form_factors')->insertGetId(['name' => 'ATX']);
    $ramTypeId = DB::table('ram_types')->insertGetId(['name' => 'DDR4']);
    $driveTypeId = DB::table('drive_types')->insertGetId(['name' => 'NVMe']);
    $chassisTypeId = DB::table('chassis_types')->insertGetId(['name' => 'ATX Mid Tower']);

    $owner = User::factory()->create();

    $build = Build::create([
        'name' => 'Build Privada',
        'is_public' => false,
        'user_id' => $owner->id,
        'motherboard_id' => DB::table('motherboards')->insertGetId(['name' => 'Mobo', 'price' => 100, 'max_memory' => 32, 'socket_id' => $socketId, 'form_factor_id' => $formFactorId, 'ram_type_id' => $ramTypeId]),
        'ram_id' => DB::table('rams')->insertGetId(['name' => 'RAM', 'price' => 50, 'cas_latency' => 16, 'size' => 16, 'modules' => 2, 'speed' => 3200, 'ram_type_id' => $ramTypeId]),
        'psu_id' => DB::table('psus')->insertGetId(['name' => 'PSU', 'price' => 60, 'power' => 600, 'psu_type_id' => $psuTypeId]),
        'drive_id' => DB::table('drives')->insertGetId(['name' => 'Drive', 'price' => 70, 'size' => 1000, 'drive_type_id' => $driveTypeId]),
        'chassis_id' => DB::table('chassis')->insertGetId(['name' => 'Caja', 'price' => 80, 'chassis_type_id' => $chassisTypeId]),
    ]);

    $this->get("/build/{$build->id}")
        ->assertForbidden();
});
