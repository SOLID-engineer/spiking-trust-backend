<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $facebook_token = $request->post('facebook');
        $google_token = $request->post('google');

        $query = [];
        if (!empty($facebook_token)) $query['facebook'] = $facebook_token;
        else if (!empty($google_token)) $query['google'] = $google_token;
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->get('https://web-dev.spiking.com/oauth', ['query' => $query]);
            $data = json_decode($response->getBody()->getContents(),true);
            if (!empty($data['data']['access_token'])) {
                $response = $client->get('https://api-dev.spiking.com/v3/me', ['query' => ['access_token' => $data['data']['access_token']]]);
                $data = json_decode($response->getBody()->getContents(),true);
                $user = User::where('spiking_id', $data['Id'])->first();
                if (empty($user)) {
                    $user = new User();
                    $user->spiking_id = $data['Id'];
                    $user->first_name = $data['Firstname'];
                    $user->last_name = $data['Lastname'];
                    $user->email = $data['Email'];
                    $user->password = Hash::make('password');
                    $user->save();
                }
                return response()->json(['accessToken' => $user->createToken('accessToken')->accessToken]);
            }
        } catch (GuzzleException $e) {
            return response()->json(null, 500);
        }
        return response()->json(null, 400);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }
}
