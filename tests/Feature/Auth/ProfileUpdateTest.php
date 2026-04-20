<?php

use App\Actions\DeleteImageFromCloudinary;
use App\Actions\UploadImageToCloudinary;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;

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
test('it can delete the account and associated cloudinary images', function () {
    $user = User::factory()->create([
        'public_profile_url' => 'profiles/abcde_profile',
    ]);

    Article::factory()->create([
        'user_id' => $user->id,
        'public_banner_url' => 'articles/abcde_banner',
    ]);

    $mockDeleter = Mockery::mock(DeleteImageFromCloudinary::class);
    $mockDeleter->shouldReceive('execute')->with('articles/abcde_banner')->once();
    $mockDeleter->shouldReceive('execute')->with('profiles/abcde_profile')->once();

    app()->instance(DeleteImageFromCloudinary::class, $mockDeleter);

    $response = $this->actingAs($user)->delete('/profile');

    $response->assertRedirect('/');
    $this->assertGuest();

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

test('it can upload a profile image and stores it in cloudinary', function () {
    $user = User::factory()->create();
    $image = UploadedFile::fake()->create('avatar.jpg', 1024, 'image/jpeg');

    $this->mock(UploadImageToCloudinary::class, function (MockInterface $mock) {
        $mock->shouldReceive('execute')
            ->once()
            ->andReturn([
                'url' => 'https://res.cloudinary.com/demo/image/upload/v12345/profiles/abcde.jpg',
                'public_id' => 'profiles/abcde',
            ]);
    });

    $response = $this->actingAs($user)->post('/profile/image', [
        'image' => $image,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(); // From redirect()->back()

    // Assert DB was updated
    $user->refresh();
    expect($user->profile_url)->toBe('https://res.cloudinary.com/demo/image/upload/v12345/profiles/abcde.jpg');
    expect($user->public_profile_url)->toBe('profiles/abcde');
});
test('it can delete the profile image and revert to default', function () {
    $user = User::factory()->create([
        'profile_url' => 'https://res.cloudinary.com/old/url.jpg',
        'public_profile_url' => 'profiles/old',
    ]);

    $this->mock(DeleteImageFromCloudinary::class, function (MockInterface $mock) {
        $mock->shouldReceive('execute')
            ->once()
            ->with('profiles/old');
    });

    $response = $this->actingAs($user)->delete('/profile/image');

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $user->refresh();
    expect($user->profile_url)->toBe('/img/default.jpg');
    expect($user->public_profile_url)->toBeNull();
});
test('it validates the maximum size of the profile image (exceeds 2MB)', function () {
    $user = User::factory()->create();
    $image = UploadedFile::fake()->create('massive_avatar.jpg', 3000, 'image/jpeg'); // 3MB

    $response = $this->actingAs($user)->post('/profile/image', [
        'image' => $image,
    ]);

    $response->assertSessionHasErrors('image');
});

test('it validates the mime type of the profile image', function () {
    $user = User::factory()->create();
    $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

    $response = $this->actingAs($user)->post('/profile/image', [
        'image' => $file,
    ]);

    $response->assertSessionHasErrors('image');
});
