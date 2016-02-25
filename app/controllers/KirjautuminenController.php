<?php

class KirjautuminenController extends BaseController {

    public static function nayta() {
        View::make('login.html');
    }

    public static function etusivu() {
//        for ($x = 2; $x <= 6; $x++) {
//            KirjoitusController::poistaKommentti($x);
//        } 
//        self::logout();
        self::check_logged_in();
        $kirjoitukset = Kirjoitus::haeKymmenenViimeisinta();
        $kayttaja = self::get_user_logged_in();
        $kayttaja->kirjoitukset = Kirjoitus::haeKayttajalla($kayttaja->id);
        $kayttaja->luetutKirjoitukset = 
                KirjoituksenLukenutKayttaja::haeLuetutKayttajalla($kayttaja->id);
        View::make('index.html', array('kirjoitukset' => $kirjoitukset
            , 'kayttaja' => $kayttaja));
    }

    public static function kirjauduSisaan() {
        $params = $_POST;

        $userId = Kayttaja::autentikoi($params['nimi'], $params['salasana']);

        if (!$userId) {
            View::make('login.html', array('error' => 'Väärä käyttäjätunnus'
                . ' tai salasana!', 'nimi' => $params['nimi']));
        } else {
            $kayttaja = Kayttaja::hae($userId);
            $_SESSION['user'] = $kayttaja->id;

            Redirect::to('/', array('message' => 'Tervetuloa foorumille ' . $kayttaja->nimi . '!'));
        }
    }

    public static function kirjauduUlos() {
        self::check_logged_in();
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
    }

}
