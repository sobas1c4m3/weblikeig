<?php
/**
 * this is a custom class to get the data from Post.php class
 * Intervention\Image\Facades\Image; is a library or plugins - we can download it by using the - composer require intervention/image
 */
namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    // this will initialize first to avoid unauthorized access to a webpage
    // by using the laravel middleware() we can asure that only a authenticated user can only access a webpage
    public function __construct()
    {
        $this->middleware('auth');
    }

     // redirect the suth user to his post page
     // get all the users that you follow
     public function index()
     {
         // get the authenticated user then grab all the users that you follow
         // pluck means get all user id that you needed
         $users = auth()->user()->following()->pluck('profiles.user_id');

         // get the users where in the user_id then sort it with the latest then get everything
         //$posts = Post::whereIn('user_id', $users)->latest()->get();

         // use pagination
         $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);
        
         // send the view in dir posts index.blade.php
         return view('posts.index', compact('posts'));
     }

    // get or output the data
    public function create()
    {
        // display the data in the dir ppost with create.blade.php file
        return view('posts.create');
    }

    // create and store the data in the database
    // validate the data for error
    public function store()
    {
        $data = request()->validate([
            'caption' => 'required',
            'image' => ['required', 'image'],
        ]);

        /** 
         * //display the dir where the image will be saved
         * dd(request('image')->store('uploads', 'public'));
         * */ 
        $imagePath = (request('image')->store('uploads', 'public'));
        
        // make the image fit
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);

        // save the image in the dir
        $image->save();
        /**
         * // grab the authenticated user
         * auth()->user()->posts()->create($data);
         * */
        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath,
        ]);

        /**
         * display in array of the submitted data
         * dd(request()->all());
         *  */ 

         return redirect('/profile/' . auth()->user()->id);
    }

    // display the details of a post
    public function show(\App\Post $post)
    {
        /**
         * return view('posts.show', [
         *  'post' => $post,
         *   ]);
         * the alternative is below
         */
            
         // display the show.blade in dir post folder
            return view('posts.show', compact('post'));
    }
}
