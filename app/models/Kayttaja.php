<?php

class Kayttaja extends BaseModel {

    public $id, $nimi, $salasana, $kirjoitukset, $kirjoituksia, $kommentteja,
            $luetutKirjoitukset, $ryhma_id, $ryhma;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_nimi', 'validoi_salasana');
    }
    
    public function validoi_nimi() {
        return parent::validate_string('Nimi', $this->nimi, 5, 20, true, true);
    }
    public function validoi_salasana() {
        $method='validate_string';
        return $this->{$method}('Salasana', $this->salasana, 6, 30, true, false);
    }

    public static function haeKaikki() {
        // Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja '
                . 'ORDER BY nimi');
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
                'kommentteja' => sizeof(Kommentti::haeKayttajalla($row['id'])),
                'ryhma_id' => $row['ryhma_id'],
                'ryhma' => Ryhma::hae($row['ryhma_id'])
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
                'nimi' => $row['nimi'],
                'ryhma_id' => $row['ryhma_id']
//                'ryhma' => Ryhma::hae($row['ryhma_id'])
//                'kirjoitukset' => Kirjoitus::findByUser($row['id'])
            ));
//            $kayttaja->kirjoituksia = sizeof($kayttaja->kirjoitukset);
            return $kayttaja;
        }
        return null;
    }
    
    public static function haeRyhmalla($id) {
        // Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja '
                . 'WHERE ryhma_id = :id '
                . 'ORDER BY nimi');
        // Suoritetaan kysely
        $query->execute(array('id' => $id));
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
                'kommentteja' => sizeof(Kommentti::haeKayttajalla($row['id'])),
                'ryhma_id' => $row['ryhma_id']
            ));
            $kayttaja->kirjoituksia = sizeof($kayttaja->kirjoitukset);
            $kayttajat[] = $kayttaja;
        }

        return $kayttajat;
    }

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (nimi, salasana, ryhma_id) '
                . 'VALUES (:nimi, :salasana, :ryhma_id) RETURNING id');
        $query->execute(array('nimi' => $this->nimi, 'salasana' => $this->salasana, 'ryhma_id' => 2));
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
    
    public function paivitaSalasana() {
        $query = DB::connection()->prepare('UPDATE Kayttaja SET salasana=:salasana '
                . 'WHERE id=:id');
        $query->execute(array('salasana' => $this->salasana,
            'id' =>  $this->id));
    }
    public function paivitaRyhma() {
        
        $query = DB::connection()->prepare('UPDATE Kayttaja SET ryhma_id=:ryhma_id '
                . 'WHERE id=:id');
        $query->execute(array('ryhma_id' => $this->ryhma_id,
            'id' =>  $this->id));
    }
    
    public function poista() {
        $kirjoitukset = Kirjoitus::haeKayttajalla($this->id);
        foreach ($kirjoitukset as $kirjoitus) {
            $kirjoitus->poista();
        } 
        $kommentit = Kommentti::haeKayttajalla($this->id);
        foreach ($kommentit as $kommentti) {
            $kommentti->poista();
        }
        $query = DB::connection()->prepare("DELETE FROM Kayttaja WHERE id=:id");
        $query->execute(array('id' =>  $this->id)); 
    }
}
