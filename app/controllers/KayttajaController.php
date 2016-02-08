<?php

class KayttajaController extends BaseController {

    public static function lista() {
        $kayttajat = Kayttaja::all();
        View::make('kayttaja/lista.html', array('kayttajat' => $kayttajat));
    }

    public static function nayta($id) {
        $kayttaja = Kayttaja::find($id);
        View::make('kayttaja/nayta.html', array('kayttaja' => $kayttaja));
    }

    public static function create() {
        View::make('kayttaja/uusi.html');
    }

    public static function store() {
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
