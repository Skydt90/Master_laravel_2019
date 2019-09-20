<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUser;
use Illuminate\Http\Request;
use App\Image;
use App\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(User::class, 'user');
    }
    

    public function index()
    {
        
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

    
    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

   
    public function update(UpdateUser $request, User $user)
    {
        if ($request->hasFile('avatar')) {

            $path = $request->file('avatar')->store('avatars');
            
            if ($user->image) {

                $user->image->path = $path;
                $user->image->save();
                
            } else {

                $user->image()->save(
                    Image::make(['path' => $path])
                );
            }
        }

        $user->locale = $request->get('locale');
        $user->save();

        return redirect()->back()->withStatus('Profile Picture updated!');
    }

    
    public function destroy(User $user)
    {
        //
    }
}
