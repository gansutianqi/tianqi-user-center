<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
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
    public function edit(Request $request)
    {
        $this->authorize('editAvatar', $request->user());

        return view('user-avatar.edit');
    }

    /**
     * Update user avatar
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $this->authorize('editAvatar', $request->user());

        $this->validate($request, [
            'cropDetail' => 'required',
            'uploadImage' => 'required',
        ]);

        $cropDetail = $request->post('cropDetail');

        $uploadImage = $request->post('uploadImage');

        if (!Storage::exists('public/avatars')) {
            Storage::makeDirectory('public/avatars');
        }

        $path = 'avatars/' . Str::random(40) . '.png';

        $this->cropAndResize($uploadImage, $cropDetail, $path);

        if ($request->user()->profile->avatar_url) {
            $this->delete($request->user());
        }

        $request->user()->profile()->update([
            'avatar_url' => $path,
        ]);

        session()->flash('status', '成功修改头像！');

        return response()->json([
            'message' => 'updated success!',
        ]);
    }

    /**
     * Delete user avatar file in the disk
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        $path = $user->profile->avatar_url;
        return Storage::disk('public')->delete($path);
    }

    /**
     * Crop and resize upload image with base64 encode format
     * and then save to the path directory
     * @param  string<base64> $uploadImage
     * @param array $cropDetail
     * @param string $path
     */
    private function cropAndResize($uploadImage, $cropDetail, $path)
    {
        Image::make($uploadImage)
            ->crop(
                (int)$cropDetail['width'],
                (int)$cropDetail['height'],
                (int)$cropDetail['x'],
                (int)$cropDetail['y']
            )->resize(100, 100)
            ->save(storage_path('app/public/') . $path);
    }

}
