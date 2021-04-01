<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends BaseController
{

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response

     * @OA\Post(
     *      path="/register",
     *      operationId="register",
     *      tags={"auth"},
     *
     *      summary="register",
     *      description="register",
     *      @OA\Parameter(
     *          name="name",
     *          in="query",
     *          description="name",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="email",
     *          required=true
     *     ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="password",
     *          required=true
     *     ),
     *      @OA\Parameter(
     *          name="c_password",
     *          in="query",
     *          description="copy password",
     *          required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     * ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *  ),
     * ),
     */

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {

            return $this->sendError('Validation Error.', $validator->errors());

        }

        $input = $request->all();

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $success['token'] = $user->createToken('MyApp')->accessToken;

        $success['name'] = $user->name;

        return $this->sendResponse($success, 'User register successfully.');

    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *      path="/login",
     *      operationId="login",
     *      tags={"auth"},
     *
     *      summary="login",
     *      description="login",
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          description="email",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          description="password",
     *          required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *         mediaType="application/json",
     *     )
     * ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     * ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *  ),
     * ),
     */

    public function login(Request $request)
    {

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            $success['token'] = $user->createToken('MyApp')->accessToken;

            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User login successfully.');

        } else {

            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);

        }

    }

}
