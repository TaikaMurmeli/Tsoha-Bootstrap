<?php

class LoginController extends BaseController{

    public static function show() {
        View::make('suunnitelmat/login.html');
    }
    
    public static function index() {
        $kirjoitukset = Kirjoitus::all();
        $user = self::get_user_logged_in();
        View::make('suunnitelmat/index.html', array('kirjoitukset' => $kirjoitukset
                , 'kirjautunut_kayttaja' => $user->nimi));
    }

    public static function login() {
        $params = $_POST;

        $userId = Kayttaja::authenticate($params['nimi'], $params['salasana']);

        if (!$userId) {
            View::make('suunnitelmat/login.html', array('error' => 'Väärä käyttäjätunnus'
                . ' tai salasana!', 'nimi' => $params['nimi']));
        } else {
            $user = Kayttaja::find($userId);
            $_SESSION['user'] = $user->id;

            Redirect::to('/', array('message' => 'Tervetuloa foorumille ' . $user->nimi     . '!'));
        }
    }

}
