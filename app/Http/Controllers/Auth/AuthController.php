<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
	public function register(Request $request)
	{
		try
		{
			$user = User::create([
				'email' => $request->get('email'),
			    'password' => bcrypt($request->get('password'))
			]);
		}
		catch(Exception $e)
		{
			return response()->json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);
		}

		$token = JWTAuth::fromUser($user);

		return response()->json(compact('token'));
	}

	public function login(Request $request)
	{
		$credentials = $request->only('email', 'password');

		try
		{
			if(!$token = JWTAuth::attempt($credentials))
			{
				return response()->json(false, HttpResponse::HTTP_UNAUTHORIZED);
			}
		}
		catch(JWTException $e)
		{
			return response()->json(['error' => 'could_not_create_token'], 500);
		}

		return response()->json(compact('token'));
	}
}
