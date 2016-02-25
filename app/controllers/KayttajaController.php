<?php

class KayttajaController extends BaseController {

    public static function listaa() {
        self::check_logged_in();
        $kayttajat = Kayttaja::haeKaikki();
        View::make('kayttaja/lista.html', array('kayttajat' => $kayttajat));
    }

    public static function nayta($id) {
        self::check_logged_in();
        $kayttaja = Kayttaja::hae($id);
        $kayttaja->kirjoitukset = Kirjoitus::haeKayttajalla($kayttaja->id);
        $kayttaja->kirjoituksia = sizeof($kayttaja->kirjoitukset);
        $kayttaja->kommentteja = sizeof(Kommentti::haeKayttajalla($kayttaja->id));
        $kayttaja->luetutKirjoitukset = KirjoituksenLukenutKayttaja::haeLuetutKayttajalla($kayttaja->id);
        $kayttaja->ryhma = Ryhma::hae($kayttaja->ryhma_id);
        View::make('kayttaja/nayta.html', array('kayttaja' => $kayttaja));
    }

    public static function luo() {
        self::check_logged_in_as_admin();
        View::make('kayttaja/uusi.html');
    }

    public static function tallenna() {
        self::check_logged_in_as_admin();
        $params = $_POST;
        $kayttaja = new Kayttaja(array(
            'nimi' => $params['nimi'],
            'salasana' => $params['salasana']
        ));

        $errors = $kayttaja->errors();
        if (count($errors) == 0) {
            $kayttaja->tallenna();
            Redirect::to('/kayttaja/' . $kayttaja->id, array('message' => 'Uusi käyttäjä on luotu!'));
        } else {

            View::make('kayttaja/uusi.html', array('errors' => $errors));
        }
    }

    public static function muokkaa($id) {

        self::check_logged_in_as_admin();
        $kayttaja = Kayttaja::hae($id);
        View::make('kayttaja/muokkaa.html', array('kayttaja' => $kayttaja));
    }

    public static function paivita($id) {
        self::check_logged_in_as_admin();
        $params = $_POST;
        $kayttaja = new Kayttaja(array(
            'id' => $id,
            'nimi' => $params['nimi'],
            'salasana' => $params['salasana'],
            'ryhma_id' => intval($params['ryhma_id'])
        ));
        //Omaa ryhmää ei voi muokata, ettei admin vahingossa huononna statustaan.
        if ($id != self::get_user_logged_in()->id) {
            $kayttaja->paivitaRyhma();
        }
        else {
            Redirect::to('/kayttajat', array('message' =>
                'Et voi muuttaa omaa ryhmääsi!'));
        }
        if ($params['salasana'] != NULL) {

            $errors = $kayttaja->errors();

            if (count($errors) > 0) {
                View::make('kayttaja/muokkaa.html', array('errors' =>
                    $errors, 'kayttaja' => $kayttaja));
            } else {
                $kayttaja->paivitaSalasana();
            }
        }
        Redirect::to('/kayttaja/' . $id, array('message' =>
            'Käyttäjää on muokattu onnistuneesti!'));
    }

    public static function poista($id) {
        self::check_logged_in_as_admin();
        if ($id == self::get_user_logged_in()->id) {
            Redirect::to('/kayttajat', array('message' =>
                'Et voi poistaa itseäsi!'));
        } else {
            $kayttaja = new Kayttaja(array('id' => $id));
            $kayttaja->poista();
            Redirect::to('/kayttajat', array('message' =>
                'Käyttaja on poistettu onnistuneesti!'));
        }
    }

}
