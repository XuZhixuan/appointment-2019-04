<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizationsController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $code = $request->post('code');

        $miniProgram = \EasyWeChat::miniProgram();
        $data = $miniProgram->auth->session($code);

        if (isset($data['errCode'])) {
            return $this->response->errorUnauthorized('Code 错误');
        }

        $attributes = [
            'weapp_openid' => $data['openid'],
            'weapp_session_key' => $data['session_key'],
        ];

        if (!$user = User::where('weapp_openid', $data['openid'])->first()) {
            $user = User::create($attributes);
        }

        $user->update($attributes);

        $token = Auth::guard('api')->fromUser($user);

        return $this->responseWithToken($token)->setStatusCode(201);
    }

    /**
     * @return mixed
     */
    public function update()
    {
        $token = Auth::guard('api')->refresh();
        return $this->responseWithToken($token)->setStatusCode(201);
    }

    /**
     * @param $token
     * @return mixed
     */
    protected function responseWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => Auth::guard('api')->factory()->getTTL() * 60,
        ]);
    }
}
