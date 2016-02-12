<?php
class RyhmaController extends BaseController {
    
    public static function listaa() {
        $ryhmat = Ryhma::all();
        View::make('ryhma/lista.html', array('ryhmat' => $ryhmat));
    }

    public static function nayta($id) {
        $ryhma = Ryhma::find($id);
        View::make('ryhma/nayta.html', array('ryhma' => $ryhma));
    }

    public static function luo() {
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
