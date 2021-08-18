<?php
/**
 * This is custom model for specific user
 * 
 * 
 * 
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];

    // display the default profile image for the new user
    public function profileImage()
    {
        $defaultImgPath = ($this->image) ? $this->image : 'profile/evZqUmPVZBUek5BULZHS5b53PnsRL8onZLQs1gOe.png';
        return '/storage/' . $defaultImgPath;
    }

    // mother relation to child @ User.php following()
    // meaning a user have can have many followers
    public function followers()
    {
        return $this->belongsToMany(User::class);
    }

    // fetch a specific user profile from User.php class with profile() functon
    // this function had a relation to user.php - profile() function
    // belongsTo means this is child relation
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
