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

test('it can upload a profile image and stores it locally', function () {
    $user = User::factory()->create();
    $image = \Illuminate\Http\UploadedFile::fake()->create('avatar.jpg', 1024, 'image/jpeg');

    $response = $this->actingAs($user)->post('/profile/image-temp', [
        'image' => $image,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(); // From redirect()->back()

    // Assert DB was updated
    $user->refresh();
    expect($user->profile_url)->toStartWith('/delete/test_avatar_');

    // Clean up
    $fileName = basename($user->profile_url);
    if (file_exists(public_path('delete/'.$fileName))) {
        unlink(public_path('delete/'.$fileName));
    }
});

test('it validates the maximum size of the profile image (exceeds 2MB)', function () {
    $user = User::factory()->create();
    $image = \Illuminate\Http\UploadedFile::fake()->create('massive_avatar.jpg', 3000, 'image/jpeg'); // 3MB

    $response = $this->actingAs($user)->post('/profile/image-temp', [
        'image' => $image,
    ]);

    $response->assertSessionHasErrors('image');
});

test('it validates the mime type of the profile image', function () {
    $user = User::factory()->create();
    $file = \Illuminate\Http\UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

    $response = $this->actingAs($user)->post('/profile/image-temp', [
        'image' => $file,
    ]);

    $response->assertSessionHasErrors('image');
});
