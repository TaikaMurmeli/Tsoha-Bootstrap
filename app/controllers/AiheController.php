<?php

class AiheController extends BaseController{
    public static function listaa() {
        $aiheet = Aihe::all();
        View::make('aihe/lista.html', array('aiheet' => $aiheet));
    }

    public static function nayta($id) {
        $aihe = Aihe::find($id);
        View::make('aihe/nayta.html', array('aihe' => $aihe));
    }

    public static function luo() {
        View::make('aihe/uusi.html');
    }

    public static function tallenna() {
        $params = $_POST;
        $aihe = new Aihe(array(
            'nimi' => $params['nimi'],
            'kuvaus' => $params['kuvaus']
        ));

//        Kint::dump($params);
        $aihe->save();

        Redirect::to('/aihe/' . $aihe->id, array('message' => 'Uusi aihe on lisätty järjestelmään!'));
    }
}
