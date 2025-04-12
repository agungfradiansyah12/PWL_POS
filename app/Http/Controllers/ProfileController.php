<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{


    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:9048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile', $filename);

            if ($user->photo && Storage::exists('public/profile/' . $user->photo)) {
                Storage::delete('public/profile/' . $user->photo);
            }

            $user->photo = $filename;
            $user->save();
        }

        return redirect('/')->with('success', 'Foto profil berhasil diperbarui.');
    }


}
