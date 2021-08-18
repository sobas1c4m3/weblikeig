<?php
/**
 * custom controller for follow button at dir js.components FollowButton.vue
 * 
 * 
 */
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class FollowsController extends Controller
{
    // 
    public function __construct(User $user)
    {
        $this->middleware('auth');
    }

    // toggle() is a laravel method
    public function store(User $user)
    {
        return auth()->user()->following()->toggle($user->profile);
    }
}
