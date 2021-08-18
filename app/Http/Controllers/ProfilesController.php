<?php

/**
 * This is custom controller file for the user profiles
 * 
 * call the use App\User; to enable the use of User class, syntax: User::find($user);
 * 
 * Intervention\Image\Facades\Image; is a library or plugins - we can download it by using the - composer require intervention/image
 */

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    // pass a valid user
    public function index(User $user)
    {
        // if the user is authenticated ?
        // then grab the user following data otherwise false
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        
        $postCount = Cache::remember(
            'count.posts.' . $user->id, 
            now()->addseconds(30), 
            function() use ($user){
                return $user->posts->count();
        });

        // using cache to make the website faster
        $followersCount = Cache::remember(
            'count.posts.' . $user->id, 
            now()->addseconds(30), 
            function() use ($user){
                return $user->profile->followers->count();
        });

        $followingCount = Cache::remember(
            'count.posts.' . $user->id, 
            now()->addseconds(30), 
            function() use ($user){
                return $user->following->count();
        });


        // fecth the specific data
        // findOrFail - if a user is registered then the profile will load if not then 404 not found appear
        // $user = User::findOrFail($user);

        // display the data in the dir profiles with index.blade.php file
        // return view('profiles.index', [
        //    'user' => $user,
        // ]);

        // this is a cleaner way to do the code above
        // if - use App\User; is not called then we can we need to use the syntax App\User as the parameter
        return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));
    }

    // go to the edit.blade.php
    public function edit(User $user)
    {
        // authorize to edit the login user only
        $this->authorize('update', $user->profile);
        
        return view('profiles.edit', compact('user'));
        
    }

    // update or edit a profile
    public function update(User $user)
    {
        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);

        // if the request has an image then save the image
        if (request('image')) {
            $imagePath = (request('image')->store('profile', 'public'));
        
            // make the image fit
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);

            // save the image in the dir
            $image->save(); 
            
            $imageArray = ['image' => $imagePath];
        }
        
        // array_merge() is a php function that takes all number of array then combine them in a new one
        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? [],
        ));

        return redirect("/profile/{$user->id}");
        
    }
}
