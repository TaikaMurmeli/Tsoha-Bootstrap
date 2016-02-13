<?php

class KayttajaController extends BaseController {

    public static function listaa() {
        self::check_logged_in();
        $kayttajat = Kayttaja::all();
        View::make('kayttaja/lista.html', array('kayttajat' => $kayttajat));
    }

    public static function nayta($id) {
        self::check_logged_in();
        $kayttaja = Kayttaja::find($id);
        $kayttaja->kirjoitukset=Kirjoitus::findByUser($kayttaja->id);
        $kayttaja->kirjoituksia=sizeof($kayttaja->kirjoitukset);
        $kayttaja->kommentteja=sizeof(Kommentti::findByUser($kayttaja->id));
        View::make('kayttaja/nayta.html', array('kayttaja' => $kayttaja));
    }

    public static function luo() {
        View::make('kayttaja/uusi.html');
    }

    public static function tallenna() {

        $params = $_POST;
        $kayttaja = new Kayttaja(array(
            'nimi' => $params['nimi'],
            'salasana' => $params['salasana']
        ));

//        Kint::dump($params);
        $kayttaja->save();

        $errors = $kayttaja->errors();
        if (count($errors) == 0) {
            $kayttaja->save();
            Redirect::to('/kayttaja/' . $kayttaja->id, 
                    array('message' => 'Uusi kayttaja on luotu!'));
        } else {

            View::make('kayttaja/uusi.html', 
                    array('errors' => $errors));
        }
    }

    public static function muokkaa($id) {
        $kayttaja = Kayttaja::find($id);
        View::make('kayttaja/muokkaa.html', array('kayttaja' => $kayttaja));
    }

    public static function paivita($id) {
        $params = $_POST;

        $kayttaja = new Kayttaja(array(
            'id' => $id,
            'nimi' => $params['nimi'],
            'salasana' => $params['salasana']
        ));

        $errors = $kayttaja->errors();

        if (count($errors) > 0) {
            View::make('kayttaja/muokkaa.html', array('errors' =>
                $errors, 'kayttaja' => $kayttaja));
        } else {

            $kayttaja->update();

            Redirect::to('/kayttaja/' . $kayttaja->id, array('message' =>
                'Käyttäjää on muokattu onnistuneesti!'));
        }
    }

    public static function poista($id) {
        $kayttaja = new Kayttaja(array('id' => $id));
        $kayttaja->delete();
        Redirect::to('/kayttajat', array('message' =>
            'Käyttaja on poistettu onnistuneesti!'));
    }

}
