<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function update(UpdateAvatarRequest $request)
    {
        // return response()->redirectTo(route('profile.edit'));
        // return back()->with('message', 'Avatar is Changed');
        // $request->validate();
        // dd($request->input('_token'));
        // $path = $request->file('avatar')->store("avatars", "public");
        $path = Storage::disk("public")->put('avatars',$request->file('avatar'));
        $oldAvatar = $request->user()->avatar;
        if ($oldAvatar) {
            Storage::disk('public')->delete($oldAvatar);
        }
        auth()->user()->update(['avatar' => $path]);
        return redirect(route('profile.edit'))->with('message', 'Avatar is updated.');
    }
}
