<?php

class Kayttaja extends BaseModel {

    public $id, $nimi, $salasana, $kirjoitukset, $kirjoituksia, $kommentteja,
            $luetutKirjoitukset;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_nimi', 'validate_salasana');
    }
    
    public function validate_nimi() {
        return parent::validate_string('Nimi', $this->nimi, 5, 20, true);
    }
    public function validate_salasana() {
        $method='validate_string';
        return $this->{$method}('Salasana', $this->salasana, 6, 30, true);
    }

    public static function haeKaikki() {
        // Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja');
        // Suoritetaan kysely
        $query->execute();
        // Haetaan kyselyn tuottamat rivit
        $rows = $query->fetchAll();
        $kayttajat = array();

        // Käydään kyselyn tuottamat rivit läpi
        foreach ($rows as $row) {
            // Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
            $kayttaja = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'kirjoitukset' => Kirjoitus::haeKayttajalla($row['id']),
                'kommentteja' => Kommentti::haeKayttajalla($row['id'])
            ));
            $kayttaja->kirjoituksia = sizeof($kayttaja->kirjoitukset);
            $kayttajat[] = $kayttaja;
        }

        return $kayttajat;
    }

    public static function hae($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $kayttaja = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi']
//                'kirjoitukset' => Kirjoitus::findByUser($row['id'])
            ));
//            $kayttaja->kirjoituksia = sizeof($kayttaja->kirjoitukset);
            return $kayttaja;
        }
        return null;
    }

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (nimi, salasana) VALUES (:nimi, :salasana) RETURNING id');
        $query->execute(array('nimi' => $this->nimi, 'salasana' => $this->salasana));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }

    public function autentikoi($nimi, $salasana) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE nimi = :nimi AND salasana = :salasana LIMIT 1');
        $query->execute(array('nimi' => $nimi, 'salasana' => $salasana));
        $row = $query->fetch();
        if ($row) {
            return $row['id'];
        } else {
            return null;
        }
    }
    
    public function paivita() {
        $query = DB::connection()->prepare('UPDATE Kayttaja SET salasana=:salasana'
                . 'WHERE id=:id');
        $query->execute(array('salasana' => $this->salasana,
            'id' =>  $this->id));
    }
    
    public function poista() {
        $kirjoitukset = Kirjoitus::haeKayttajalla($this->id);
        foreach ($kirjoitukset as $kirjoitus) {
            $kirjoitus->poista();
        } 
        $query = DB::connection()->prepare("DELETE FROM Kayttaja WHERE id=:id");
        $query->execute(array('id' =>  $this->id)); 
    }
}
