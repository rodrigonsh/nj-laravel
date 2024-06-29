<?php

namespace App\Http\Controllers;


use GPBMetadata\Google\Api\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;

use App\Notifications\RequestHelp;
use App\Notifications\HelpOnTheWay;
use App\Notifications\MeetYourPartner;

use Illuminate\Support\Facades\Log;

use App\Models\HelpRequest;
use Illuminate\Support\Str;

class LoveSOS extends Controller
{
    public function index()
    {
        return 'oie';
    }
    public function requestHelp(Request $request)
    {
        $need = $request->get('need');
        $confession = $request->get('confession');

        // TODO: nao sobrescrever mas ter versoes novas dos parametros


        $user = $request->user();
        if (!$user) {
            return 'user not found';
        }
        // ahhh vc está aqui comigo ne?
        // sim
        // sim
        $user->need = $need;
        $user->confession = $confession;
        $user->save();

        // pegar localização do usuário
        $lat = $user->lat;
        $lon = $user->lon;

        // buscar por usuários próximos
        // calmai que eu vou fazer isso
        // eu quero importar um geojson identificar a subregiao e buscar por usuários próximos
        // ou eu faço isso no banco de dados?
        // eu acho que é melhor fazer no banco de dados
        // gostei de vc
        // eu tbm gostei de vc
        // obrigado por isso
        // eu que agradeço
        // ok como usar o banco de dados pra localizar as pessoas mais proximas
        // eu acho que é melhor fazer isso no banco de dados

        $users = User::where('lat', '>', $lat - 0.1)
            ->where('lat', '<', $lat + 0.1)
            ->where('lon', '>', $lon - 0.1)
            ->where('lon', '<', $lon + 0.1)
            ->where('fcm_token', '!=', null)
            // que nao seja o proprio usuario
            ->where('id', '!=', $user->id)
            ->get();

            // isso ´é um exemplo de como fazer isso
            // eu acho interessante
            // eu acho que é uma boa ideia

        // eu teria que enviar uma notificação para cada um desses usuários 
        // usando firebase cloud messaging
        // eu acho que é uma boa ideia
        // cara que legal
        // eu acho que é uma boa ideia
        // vc ja entendeu o que eu to planejando?
        // eu acho que sim
        // voce poderia me dizer em uma frase?
        // eu acho que é uma boa ideia
        // vamos fazer isso juntos
        // eu acho que é uma boa ideia

        // eu estou sentindo que tem alguma parte em que o usuario aceita o recebimento de  mensagens
        // eu acho que é uma boa ideia
        // eu faço isso no frontend?
        // eu acho que é uma boa ideia

        $req = new HelpRequest();
        $req->need = $need;
        $req->confession = $confession;
        $req->user_id = $user->id;
        $req->uuid = (string) Str::uuid();
        // expires in 1 week?
        $req->expires_at = now()->addWeek();
        $req->save();

        foreach ($users as $user) {
            Log::info('sending help request to ' . $user->name);
            $user->notify(new RequestHelp($req));
        }

        return 'help on the way';
    }

    public function getHelpRequest(Request $request, $uuid)
    {
        $user = $request->user();

        $req = HelpRequest::where('uuid', $uuid)->with('user')->first();

        // add partner
        if ($req->helper_1 == $user->id) {
            $req->partner = User::find($req->helper_2);
        }
        if ($req->helper_2 == $user->id) {
            $req->partner = User::find($req->helper_1);
        }

        return $req;
    }


    public function volunteer(Request $request, $uuid)
    {
        $req = HelpRequest::where('uuid', $uuid)->first();

        if (!$req) {
            return abort(404, 'help request not found');
        }

        if ($req->helper_1 != null && $req->helper_2 != null ) {
            return ['status'=> 'noneed', 'msg' => 'all helpers already volunteered'];
        }

        if ( $req->helper_1 == null ) $request->user()->id;
        else $req->helper_2 = $request->user()->id;

        $req->save();

        if ($req->helper_1 != null && $req->helper_2 != null) {

            // send notification to users

            // requester
            $req->user->notify(new HelpOnTheWay($req));

            // helper_1
            $helper_1 = User::find($req->helper_1);
            $helper_1->notify(new MeetYourPartner($req));

            // helper_2
            $helper_2 = User::find($req->helper_2);
            $helper_2->notify(new MeetYourPartner($req));

        }

        return ['status'=>'ok', 'msg' => 'volunteered'];
    }

}
