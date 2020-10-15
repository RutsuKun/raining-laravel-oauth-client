<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Redirect the user to the RainingDreams authentication page.
     *
     * @return \Illuminate\Http\Response
     */

    public function redirectToProvider()
    {
        return Socialite::driver('rainingdreams')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */

    public function handleProviderCallback()
    {
        $getInfo = Socialite::driver('rainingdreams')->user();
        if($getInfo->user){
          $user = $this->createUser($getInfo->user, 'rainingdreams');
          auth()->login($user);
          return redirect()->route('home');
        }
    }

    function createUser($getInfo,$provider){
print_r($getInfo);

      $user = User::where('provider_id', $getInfo['id'])->first();
      if (!$user) {
       $user = User::create([
         'username'     => $getInfo['username'],
         'email'    => $getInfo['email'],
         'picture' => $getInfo['picture'],
         'provider' => $provider,
         'provider_id' => $getInfo['id']
       ]);
     }
     return $user;

   }

   public function logout(){
     Auth::logout();
     return redirect()->route('home');
   }
}
