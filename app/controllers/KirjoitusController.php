<?php

class KirjoitusController extends BaseController {

    public static function listaa() {
        self::check_logged_in();
        $kirjoitukset = Kirjoitus::haeKaikki();
        View::make('kirjoitus/lista.html', array('kirjoitukset' => $kirjoitukset));
    }

    public static function nayta($kirjoitus_id) {
        $kayttaja = self::get_user_logged_in();
        self::check_logged_in();
        $kirjoitus = Kirjoitus::hae($kirjoitus_id);
        $kommentit = Kommentti::haeKirjoituksella($kirjoitus_id);
        $kirjoitus->kommentteja = sizeof($kommentit);
        $kirjoitus->lukeneetKayttajat = KirjoituksenLukenutKayttaja::haeLukeneetKirjoituksella($kirjoitus_id);
        View::make('kirjoitus/nayta.html', array('kirjoitus' => $kirjoitus,
            'kommentit' => $kommentit, 'kirjautunut_kayttaja' => $kayttaja));
    }

    public static function luo($aihe_id) {
        self::check_logged_in();
        self::check_detention();
        $user = self::get_user_logged_in();
        $aihe = Aihe::hae($aihe_id);
        View::make('kirjoitus/uusi.html', array('aihe' => $aihe, 'julkaisija' => $user));
    }

    public static function tallenna() {
        self::check_logged_in();
        self::check_detention();
        $params = $_POST;
        $aihe_id = intval($params['aihe_id']);
        $kirjoitus = new Kirjoitus(array(
            'aihe' => $aihe_id,
            'nimi' => $params['nimi'],
            'sisalto' => $params['sisalto'],
            'julkaisija' => self::get_user_logged_in()->id,
            'julkaistu' => date('d-m-Y H:i:s', time())
        ));
        $errors = $kirjoitus->errors();
        if (count($errors) == 0) {
            $kirjoitus->tallenna();
            Redirect::to('/kirjoitus/' . $kirjoitus->id, array('message' => 'Uusi kirjoitus on luotu!'));
        } else {

            View::make('kirjoitus/uusi/' . $aihe->id . '.html', array('errors' => $errors));
        }
    }

    public static function muokkaa($id) {
        self::check_logged_in();
        $kirjoitus = Kirjoitus::hae($id);
        self::check_self($kirjoitus->julkaisija->id);
        View::make('kirjoitus/muokkaa.html', array('attributes' => $kirjoitus));
    }

    public static function paivita($id) {
        $params = $_POST;

        $attributes = array(
            'id' => $id,
            'nimi' => $params['nimi'],
            'sisalto' => $params['sisalto'],
            'julkaistu' => date('d-m-Y H:i:s', time())
        );
        $kirjoitus = Kirjoitus::hae($id);
        self::check_self($kirjoitus->julkaisija->id);
        $kirjoitus = new Kirjoitus($attributes);

        $errors = $kirjoitus->errors();

        if (count($errors) > 0) {
            View::make('kirjoitus/muokkaa.html', array('errors' =>
                $errors, 'attributes' => $attributes));
        } else {

            $kirjoitus->paivita();

            Redirect::to('/kirjoitus/' . $kirjoitus->id, array('message' =>
                'Kirjoitusta on muokattu onnistuneesti!'));
        }
    }

    public static function poista($id) {
        $kirjoitus = Kirjoitus::hae($id);
        self::check_self($kirjoitus->julkaisija->id);
        $kirjoitus->poista();
        Redirect::to('/', array('message' =>
            'Kirjoitus on poistettu onnistuneesti!'));
    }

    public static function poistaKommentti($id) {
        $kommentti = Kommentti::hae($id);
        self::check_self($kirjoitus->julkaisija->id);
        $kirjoitus_id = $kommentti->kirjoitus->id;
        $kommentti->poista();
        Redirect::to('/kirjoitus/' . $kirjoitus_id, array('message' =>
            'Kommentti on poistettu onnistuneesti!'));
    }

    public static function lisaaKommentti() {
        self::check_logged_in();
        self::check_detention();
        $params = $_POST;
        $kirjoitus_id = intval($params['kirjoitus_id']);
        $kommentti = new Kommentti(array(
            'kirjoitus' => $kirjoitus_id,
            'sisalto' => $params['sisalto'],
            'julkaisija' => self::get_user_logged_in()->id,
            'julkaistu' => date('d-m-Y H:i:s', time())
        ));
        $errors = $kommentti->errors();
        if (count($errors) == 0) {
            $kommentti->tallenna();
            Redirect::to('/kirjoitus/' . $kirjoitus_id, array('message' =>
                'Uusi kommentti on lisätty kirjoitukseen!'));
        } else {
            Redirect::to('/kirjoitus/' . $kirjoitus_id, array('errors' => $errors));
        }
    }

    public static function merkitseLuetuksi($kirjoitus_id) {
        
        $lukija = new KirjoituksenLukenutKayttaja(array(
            'kirjoitus_id' => $kirjoitus_id,
            'kayttaja_id' => self::get_user_logged_in()->id
        ));
        $lukija->tallenna();
        Redirect::to('/kirjoitus/' . $kirjoitus_id, array('message' =>
            'Kirjoitus merkattu luetuksi!'));
    }

}
