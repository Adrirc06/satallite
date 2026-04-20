<?php

use App\Models\User;

test('it can update the user name', function () {
    $user = User::factory()->create([
        'name' => 'testuser',
    ]);

    $response = $this->actingAs($user)->patch('/profile', [
        'name' => 'new-username',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'new-username',
    ]);
});

test('it validates the maximum length of the username', function () {
    $user = User::factory()->create([
        'name' => 'testuser',
    ]);

    $response = $this->actingAs($user)->patch('/profile', [
        'name' => str_repeat('a', 21),
    ]);

    $response->assertSessionHasErrors('name');
});

test('it validates the allowed characters in the username', function () {
    $user = User::factory()->create([
        'name' => 'testuser',
    ]);

    $response = $this->actingAs($user)->patch('/profile', [
        'name' => 'user name!',
    ]);

    $response->assertSessionHasErrors('name');
});

test('it validates that the username is not the same as the current one', function () {
    $user = User::factory()->create([
        'name' => 'testuser',
    ]);

    $response = $this->actingAs($user)->patch('/profile', [
        'name' => 'testuser',
    ]);

    $response->assertSessionHasErrors('name');
});

test('it validates that the username is unique', function () {
    User::factory()->create(['name' => 'existinguser']);
    $user = User::factory()->create(['name' => 'testuser']);

    $response = $this->actingAs($user)->patch('/profile', [
        'name' => 'existinguser',
    ]);

    $response->assertSessionHasErrors('name');
});
test('it can delete the account', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->delete('/profile');

    $response->assertRedirect('/');
    $this->assertGuest();

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});
