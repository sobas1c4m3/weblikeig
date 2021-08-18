<?php
/**
 * this is a custom class to control the user posts @ posts table
 * 
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // tell laravel not to guard anything
    // 
    protected $guarded = [];

    // return the post belong to a specific user by using belongsTo()
    public function user()
    {
        
        return $this->belongsTo(User::class);
    }
}
