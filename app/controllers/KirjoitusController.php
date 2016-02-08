<?php
class KirjoitusController extends BaseController{
     public static function listaa() {
        $kirjoitukset = Kirjoitus::all();
        View::make('kirjoitus/lista.html', array('kirjoitukset' => $kirjoitukset));
    }

    public static function nayta($id) {
        $kirjoitus = Kirjoitus::find($id);
        View::make('kirjoitus/nayta.html', array('kirjoitus' => $kirjoitus));
    }

    public static function luo() {
        View::make('kirjoitus/uusi.html');
    }

    public static function tallenna() {
        $params = $_POST;
        $kirjoitus = new Kirjoitus(array(
            'nimi' => $params['nimi'],
            'sisalto' => $params['sisalto'],
            'julkaistu' => date('d-m-Y H:i:s', time())
        ));

//        Kint::dump($params);
        $kirjoitus->save();

        Redirect::to('/kirjoitus/' . $kirjoitus->id, array('message' => 'Uusi kirjoitus on luotu!'));
    }
}
