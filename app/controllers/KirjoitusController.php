<?php

class KirjoitusController extends BaseController {

    public static function listaa() {
        self::check_logged_in();
        $kirjoitukset = Kirjoitus::all();
        View::make('kirjoitus/lista.html', array('kirjoitukset' => $kirjoitukset));
    }

    public static function nayta($id) {
        self::check_logged_in();
        $kirjoitus = Kirjoitus::find($id);
        $kommentit = Kommentti::findByArticle($id);
        $kirjoitus->kommentteja = sizeof($kommentit);
        View::make('kirjoitus/nayta.html', array('kirjoitus' => $kirjoitus,
            'kommentit' => $kommentit));
    }

    public static function luo($aihe_id) {
        self::check_logged_in();
        $user = self::get_user_logged_in();
        $aihe = Aihe::find($aihe_id);
        View::make('kirjoitus/uusi.html', 
                array('aihe' => $aihe, 'julkaisija' => $user));
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
            Redirect::to('/kirjoitus/' . $kirjoitus->id, 
                    array('message' => 'Uusi kirjoitus on luotu!'));
        } else {
            
            View::make('kirjoitus/uusi/' . $aihe->id . '.html', 
                    array('errors' => $errors));
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
            View::make('kirjoitus/muokkaa.html', array('errors' => 
                $errors, 'attributes' => $attributes));
        } else {

            $kirjoitus->update();

            Redirect::to('/kirjoitus/' . $kirjoitus->id, array('message' => 
                'Kirjoitusta on muokattu onnistuneesti!'));
        }
    }

    public static function poista($id) {
        $kirjoitus = new Kirjoitus(array('id' => $id));
        $kirjoitus->delete();
        Redirect::to('/', array('message' => 
            'Kirjoitus on poistettu onnistuneesti!'));
    }
    
    public static function poistaKommentti($id) {
        $kommentti = Kommentti::find($id);
        $kirjoitus_id = $kommentti->julkaisija->id;
        $kommentti->delete();
        Redirect::to('/kirjoitus/' . $kirjoitus_id, array('message' => 
            'Kommentti on poistettu onnistuneesti!'));     
    }
    
    public static function lisaaKommentti() {
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
            $kommentti->save();
            Redirect::to('/kirjoitus/' . $kirjoitus_id, array('message' => 
                'Uusi kommentti on lisätty kirjoitukseen!'));
        } else {
            Redirect::to('/kirjoitus/' . $kirjoitus_id, 
                    array('errors' => $errors));
        }
    }

}
