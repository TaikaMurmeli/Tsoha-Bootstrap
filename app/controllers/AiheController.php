<?php

class AiheController extends BaseController{
    public static function lista() {
        $aiheet = Aihe::all();
        View::make('aihe/lista.html', array('aiheet' => $aiheet));
    }

    public static function nayta($id) {
        $aihe = Kayttaja::find($id);
        View::make('aihe/nayta.html', array('aihe' => $aihe));
    }

    public static function create() {
        View::make('aihe/uusi.html');
    }

    public static function store() {
        $params = $_POST;
        $aihe = new Kayttaja(array(
            'nimi' => $params['nimi'],
            'salasana' => $params['salasana']
        ));

//        Kint::dump($params);
        $aihe->save();

        Redirect::to('/aihe/' . $aihe->id, array('message' => 'Uusi aihe on lisätty järjestelmään!'));
    }
}
