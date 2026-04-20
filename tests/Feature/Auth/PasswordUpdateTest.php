<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('can change the password successfully', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $response = $this->actingAs($user)->put('/profile/password', [
        'current_password' => 'password123',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/profile');

    $this->assertTrue(Hash::check('newpassword123', $user->refresh()->password));
});

it('validates the current password when trying to change it', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $response = $this->actingAs($user)->put('/profile/password', [
        'current_password' => 'wrongpassword',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors('current_password');
});

it('validates that the new password has a minimum length', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($user)->put('/profile/password', [
        'current_password' => 'password',
        'password' => 'short',
        'password_confirmation' => 'short',
    ]);

    $response->assertSessionHasErrors('password');
});

it('validates that the new password is not the same as the current one', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $response = $this->actingAs($user)->put('/profile/password', [
        'current_password' => 'password123',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors('password');
});

it('validates the new password confirmation', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $response = $this->actingAs($user)->put('/profile/password', [
        'current_password' => 'password123',
        'password' => 'newpassword123',
        'password_confirmation' => 'differentpassword',
    ]);

    $response->assertSessionHasErrors('password');
});
