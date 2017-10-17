<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use DB, Hash, Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class AuthController extends Controller
{
    /**
    * API Register
    *
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse

    */

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ];
        $input = $request->only(
            'name',
            'email',
            'password',
            'password_confirmation'
        );
        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            $error = $validator->messages()->toJson();
            return response()->json(['success'=> false, 'error'=> $error]);
        }
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $user = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
        // $verification_code = str_random(30); //Generate verification code
        // DB::table('user_verifications')->insert(['user_id'=>$user->id,'token'=>$verification_code]);
        // $subject = "Please verify your email address.";
        // Mail::send('email.verify', ['name' => $name, 'verification_code' => $verification_code],
        //     function($mail) use ($email, $name, $subject){
        //         $mail->from(getenv('FROM_EMAIL_ADDRESS'), "From User/Company Name Goes Here");
        //         $mail->to($email, $name);
        //         $mail->subject($subject);
        //     });
        // return response()->json(['success'=> true, 'message'=> 'Thanks for signing up! Please check your email to complete your registration.']);
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['success' => false, 'error' => 'We cant find an account with this credentials.'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
        }
        // all good so return the token
        return response()->json(['success' => true, 'data'=> [ 'token' => $token ]]);
    }

    /**
    * Log out
    * Invalidate the token, so user cannot use it anymore
    * They have to relogin to get a new token
    *
    * @param Request $request
    */
   public function logout(Request $request) {
       $this->validate($request, ['token' => 'required']);
       try {
           JWTAuth::invalidate($request->input('token'));
           return response()->json(['success' => true]);
       } catch (JWTException $e) {
           // something went wrong whilst attempting to encode the token
           return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
       }
   }


   /**
    * API Recover Password
    *
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */
   public function recover(Request $request)
   {
       $user = User::where('email', $request->email)->first();
       if (!$user) {
           $error_message = "Your email address was not found.";
           return response()->json(['success' => false, 'error' => ['email'=> $error_message]], 401);
       }
       try {
           Password::sendResetLink($request->only('email'), function (Message $message) {
               $message->subject('Your Password Reset Link');
           });
       } catch (\Exception $e) {
           //Return with error
           $error_message = $e->getMessage();
           return response()->json(['success' => false, 'error' => $error_message], 401);
       }
       return response()->json([
           'success' => true, 'data'=> ['msg'=> 'A reset email has been sent! Please check your email.']
       ]);
   }


}
