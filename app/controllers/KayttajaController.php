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

        Redirect::to('/kayttaja/' . $kayttaja->id, array('message' => 'Uusi kayttaja on lisätty järjestelmään!'));
    }

}
