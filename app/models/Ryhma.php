<?php

class Ryhma extends BaseModel {

    //put your code here
    public $id, $nimi, $kuvaus, $kayttajat, $kayttajia;

    public function __construct($attributes) {
        parent::__construct($attributes);
//        $this->validators = array('validate_nimi', 'validate_kuvaus');
    }

//    public function validate_nimi() {
//        return parent::validate_string('nimi', $this->nimi, 3, 30, true, true);
//    }
//
//    public function validate_kuvaus() {
//        $method = 'validate_string';
//        return $this->{$method}('kuvaus', $this->kuvaus, 5, 200, true, true);
//    }

    public static function haeKaikki() {
        // Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM Ryhma');
        // Suoritetaan kysely
        $query->execute();
        // Haetaan kyselyn tuottamat rivit
        $rows = $query->fetchAll();
        $ryhmat = array();

        // Käydään kyselyn tuottamat rivit läpi
        foreach ($rows as $row) {
            // Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
            $ryhmat[] = new Ryhma(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'kuvaus' => $row['kuvaus'],
                'kayttajia' => sizeof(Kayttaja::haeRyhmalla($row['id']))
            ));
        }

        return $ryhmat;
    }

    public static function hae($id) {
        $query = DB::connection()->prepare('SELECT * FROM Ryhma WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $ryhma = new Ryhma(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'kuvaus' => $row['kuvaus'],
                'kayttajat' => Kayttaja::haeRyhmalla($row['id'])
            ));

            return $ryhma;
        }

        return null;
    }

//    public function tallenna() {
//        $query = DB::connection()->prepare('INSERT INTO Ryhma (nimi, kuvaus) VALUES (:nimi, :kuvaus) RETURNING id');
//        $query->execute(array('nimi' => $this->nimi, 'kuvaus' => $this->kuvaus));
//        $row = $query->fetch();
//        $this->id = $row['id'];
//    }

}
