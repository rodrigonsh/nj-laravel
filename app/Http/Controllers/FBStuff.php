<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Auth as FBAuth;
use Illuminate\Support\Facades\Auth;

class FBStuff extends Controller
{
    // PARA OS NOVOS USUÁRIOS DO APP
    public function checkIn(FBAuth $auth, Request $r)
    {

        $verifiedIdToken = $auth->verifyIdToken($r['_userToken']);
        $uid = $verifiedIdToken->claims()->get('sub');
        $phoneNumber = $verifiedIdToken->claims()->get('phone_number');

        // nao sobrescrever se já estiver pré cadastrado
        $user = User::where('uid', $uid)->first();
    
        if ( $user )
        {
            Auth::login($user, true);
            // Considerar no futuro o update de dados do usuário
            return 
            [
                'token' => $user->createToken('token')->plainTextToken,
                'user' => $user
            ];
        }
        else
        {
            // retornar avisando que faltam dados
            $sad = ['missingData' => true, 'message' => 'Faltam dados para completar o cadastro'];
            if ( ! $r->get('name') ) return $sad;
            if ( ! $r->get('cpf') ) return $sad;
            if ( ! $r->get('lat') ) return $sad;
            if ( ! $r->get('lon') ) return $sad;
            if ( ! $r->get('familySize') ) return $sad;
        }
        
        
        $data = $r->all();
        $data['uid'] = $uid;
        $data['phoneNumber'] = $phoneNumber;

        if ( !isset($data['name']) )
        {
            $data['name'] = $verifiedIdToken->claims()->get('name') || 'Usuário sem nome';
        }

        if ( !isset($data['email']) )
        {
            $data['email'] = $verifiedIdToken->claims()->get('email') || $data['phoneNumber'].'@noemail.com';
        }

        $user = new User();
        $user->fill($data);
        $user->familySize = json_encode($user->familySize);
        $user->save();

        Auth::login($user, true);
        return 
        [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];

    }

    // QUANDO O CARA PASSAR PELA PORTA
    public function letMeIn(FBAuth $auth, Request $r)
    {
        $user = $r->user();
        $user->lastdoor = (int) $r->input('door');
        $user->save();
        return 'ok';
    }

    public function updateProfile(Auth $auth, Request $r)
    {

        $verifiedIdToken = $auth->verifyIdToken($r['_userToken']);
        $uid = $verifiedIdToken->claims()->get('sub');

        $acc = User::where('uid', $uid)->first();
        if ( !$acc )
        {
            abort(404, 'Usuário não encontrado');
        }

        $data = $r->all();
        
        $acc->fill($data);
        $acc->save();
        
        return $acc;
    }

    public function updatePhoto(Auth $auth, Request $r)
    {
        $verifiedIdToken = $auth->verifyIdToken($r['_userToken']);
        $uid = $verifiedIdToken->claims()->get('sub');

        $photo = $r->file('image');
        if( strpos($photo->getMimeType(), 'image/' ) != 0 )
        {
            abort(400, 'Somente imagens');
        }

        $hash = md5($uid);
        $photo->move("uploads", $hash);

        $res = $auth->updateUser($uid, ['photoURL' => env('APP_URL')."/uploads/$hash"]);
        //Log::debug("Update Photo");
        //Log::debug(env('APP_URL')."/uploads/$hash");
        //Log::debug(print_r($res, true));

    }

    public function sendFCMToken(Request $request)
    {

        Log::debug('sendFCMToken');
        Log::debug($request);

        $user = $request->user();

        // weird im getting null here
        // the bearer is correct
        // the token is correct
        // the user is null
        // the user is null
        
        if ( !$user )
        {
            abort(401, 'Usuário não autenticado');
        }

        $user->fcm_token = request()->get('token');
        $user->save();

        return ['message' => 'Token salvo'];

    }

}
