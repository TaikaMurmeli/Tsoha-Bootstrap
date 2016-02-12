<?php

class AiheController extends BaseController{
    public static function listaa() {
        self::check_logged_in();
        $aiheet = Aihe::all();
        View::make('aihe/lista.html', array('aiheet' => $aiheet));
    }

    public static function nayta($id) {
        self::check_logged_in();
        $aihe = Aihe::find($id);
        $kirjoitukset = Kirjoitus::findByCategory($id);
        View::make('aihe/nayta.html', array('aihe' => $aihe,
            'kirjoitukset' => $kirjoitukset));
    }

    public static function luo() {
        self::check_logged_in();
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
    
    public static function muokkaa($id) {
        $aihe = Aihe::find($id);
        View::make('aihe/muokkaa.html', array('attributes' => $aihe));
    }
    
    public static function paivita($id) {
        $params = $_POST;

        $attributes = array(
        'id' => $id,
        'nimi' => $params['nimi'],
        'kuvaus' => $params['kuvaus']
        );

        $aihe = new Aihe($attributes);
        $errors = $aihe->errors();

        if (count($errors) > 0) {
            View::make('aihe/muokkaa.html', array('errors' => 
                $errors, 'attributes' => $attributes));
        } else {

            $aihe->update();

            Redirect::to('/aihe/' . $aihe->id, array('message' => 
                'Aiheen kuvausta on muokattu onnistuneesti!'));
        }
    }

    public static function poista($id) {
        $aihe = new Aihe(array('id' => $id));
        $aihe->delete();
        Redirect::to('/aiheet', array('message' => 
            'Aihe on poistettu onnistuneesti!'));
    }
    
    
}
