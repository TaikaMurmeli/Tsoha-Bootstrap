<?php
class RyhmaController extends BaseController {
    
    public static function listaa() {
        self::check_logged_in();
        $ryhmat = Ryhma::all();
        View::make('ryhma/lista.html', array('ryhmat' => $ryhmat));
    }

    public static function nayta($id) {
        self::check_logged_in();
        $ryhma = Ryhma::find($id);
        View::make('ryhma/nayta.html', array('ryhma' => $ryhma));
    }

    public static function luo() {
        self::check_logged_in();
        View::make('ryhma/uusi.html');
    }

    public static function tallenna() {
        $params = $_POST;
        $ryhma = new Ryhma(array(
            'nimi' => $params['nimi'],
            'kuvaus' => $params['kuvaus']
        ));

//        Kint::dump($params);
        $ryhma->save();

        Redirect::to('/ryhma/' . $ryhma->id, array('message' => 'Uusi ryhmä on lisätty järjestelmään!'));
    }
}
