<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UserAvatarController extends Controller
{

    /**
     * Display the form for edit the user avatar
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('user-avatar.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {

        $cropDetail = $request->post('cropDetail');

        $uploadImage = $request->post('uploadImage');

        if (!$cropDetail && !$uploadImage) {
            abort(500);
        }

        $path = 'avatars/' . Str::random(40) . '.png';

        if (!Storage::exists('public/avatars')) {
            Storage::makeDirectory('public/avatars');
        }

        Image::make($uploadImage)
            ->crop(
                (int)$cropDetail['width'],
                (int)$cropDetail['height'],
                (int)$cropDetail['x'],
                (int)$cropDetail['y']
            )->resize(100, 100)
            ->save(storage_path('app/public/') . $path);

        $request->user()->profile()->update([
            'avatar_url' => $path,
        ]);
    }
}
