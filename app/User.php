<?php
/**
 * protected $fillable = ['name', 'email', 'username', 'password'];, 'username' is added 
 * 
 */
namespace App;

use App\Mail\NewUserWelcomeMail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // needed when booting up the model - when there is no data at the database
    protected static function boot()
    {
        parent::boot();

        // start this syntax to display a newly registered user
        static::created(function ($user){
            $user->profile()->create([
                'title' => $user->username,
            ]);

            Mail::to($user->email)->send(new NewUserWelcomeMail());
            
        });
    }
    
    // return the user's post by using hasMany() through Post Model
    // meaning this speific user has many post
    // then arranged the data by DESC
    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }

    // make a relationship with Profile Controller
    public function following()
    {
        return $this->belongsToMany(Profile::class);
    }

    // return a data to user() @ Profile.php class
    // this function had a relation to Profile.php - user() function
    // HasOne means this is mother relation
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
