<?php

namespace App\Http\Controllers;

use App\Actions\DeleteImageFromCloudinary;
use App\Actions\UploadImageToCloudinary;
use App\Http\Requests\UpdateProfileImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:20',
                'regex:/^[a-zA-Z0-9_-]+$/',
                Rule::unique('users')->ignore($request->user()->id),
                function ($attribute, $value, $fail) use ($request) {
                    if ($value === $request->user()->name) {
                        $fail('El nombre de usuario no puede ser el mismo que tu nombre actual.');
                    }
                },
            ],
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.max' => 'El nombre de usuario no puede exceder los 20 caracteres.',
            'name.regex' => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.',
            'name.unique' => 'Ese nombre de usuario ya está en uso.',
        ]);

        $request->user()->update([
            'name' => $validated['name'],
        ]);

        return redirect('/profile');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ], [
            'current_password.current_password' => 'La contraseña actual incorrecta.',
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las nuevas contraseñas no coinciden.',
            'password.different' => 'La nueva contraseña no puede ser igual a tu contraseña actual.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/profile');
    }

    public function destroy(Request $request, DeleteImageFromCloudinary $deleter)
    {
        $user = $request->user();

        $user->articles()->whereNotNull('public_banner_url')->pluck('public_banner_url')->each(function ($publicId) use ($deleter) {
            try {
                $deleter->execute($publicId);
            } catch (\Exception $e) {
                // Ignore failure               
            }
        });

        if ($user->public_profile_url) {
            try {
                $deleter->execute($user->public_profile_url);
            } catch (\Exception $e) {
                // Ignore failure
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function updateImage(UpdateProfileImageRequest $request, UploadImageToCloudinary $uploader, DeleteImageFromCloudinary $deleter)
    {
        $user = $request->user();

        $uploadInfo = $uploader->execute($request->file('image'), 'profiles');

        if ($user->public_profile_url) {
            $deleter->execute($user->public_profile_url);
        }

        $user->update([
            'profile_url' => $uploadInfo['url'],
            'public_profile_url' => $uploadInfo['public_id'],
        ]);

        return redirect()->back()->with('success', 'Imagen subida correctamente.');
    }

    public function destroyImage(Request $request, DeleteImageFromCloudinary $deleter)
    {
        $user = $request->user();

        if ($user->public_profile_url) {
            $deleter->execute($user->public_profile_url);
        }

        $user->update([
            'profile_url' => '/img/default.jpg',
            'public_profile_url' => null,
        ]);

        return redirect()->back()->with('success', 'Imagen eliminada correctamente.');
    }
}
