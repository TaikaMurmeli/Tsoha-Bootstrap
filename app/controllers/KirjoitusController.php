<?php

class KirjoitusController extends BaseController {

    public static function listaa() {
        $kirjoitukset = Kirjoitus::all();
        View::make('kirjoitus/lista.html', array('kirjoitukset' => $kirjoitukset));
    }

    public static function nayta($id) {
        $kirjoitus = Kirjoitus::find($id);
        View::make('kirjoitus/nayta.html', array('kirjoitus' => $kirjoitus));
    }

    public static function luo($id) {
        $user = self::get_user_logged_in();
        $aihe = Aihe::find($id);
        View::make('kirjoitus/uusi.html', array('aihe' => $aihe, 'julkaisija' => $user));
    }

    public static function tallenna() {
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
            $kirjoitus->save();
            Redirect::to('/kirjoitus/' . $kirjoitus->id, array('message' => 'Uusi kirjoitus on luotu!'));
        } else {
            
            View::make('kirjoitus/uusi/' . $aihe->id . '.html', array('errors' => $errors));
        }
    }

    public static function muokkaa($id) {
        $kirjoitus = Kirjoitus::find($id);
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

        $kirjoitus = new Kirjoitus($attributes);
        $errors = $kirjoitus->errors();

        if (count($errors) > 0) {
            View::make('kirjoitus/muokkaa.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {

            $kirjoitus->update();

            Redirect::to('/kirjoitus/' . $kirjoitus->id, array('message' => 'Kirjoitusta on muokattu onnistuneesti!'));
        }
    }

    public static function poista($id) {
        $kirjoitus = new Kirjoitus(array('id' => $id));
        $kirjoitus->delete();
        Redirect::to('/kirjoitukset', array('message' => 'Kirjoitus on poistettu onnistuneesti!'));
    }

}
