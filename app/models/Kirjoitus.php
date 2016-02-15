<?php

class Kirjoitus extends BaseModel {

    public $id, $aihe, $nimi, $sisalto, $julkaistu, $julkaisija, $kommentteja,
            $lukeneetKayttajat;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_nimi', 'validate_sisalto');
    }

    public function validate_nimi() {
        return parent::validate_string('Otsikko', $this->nimi, 3, 50, true);
    }

    public function validate_sisalto() {
        $method = 'validate_string';
        return $this->{$method}('Sisältö', $this->sisalto, 5, 4000, true);
    }

    public static function haeKaikki() {
        $query = DB::connection()->prepare('SELECT * FROM Kirjoitus');
        $query->execute();
        $rows = $query->fetchAll();
        $kirjoitukset = array();

        foreach ($rows as $row) {
            $kirjoitukset[] = new Kirjoitus(array(
                'id' => $row['id'],
                'aihe' => Aihe::hae($row['aihe_id']),
                'nimi' => $row['nimi'],
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => Kayttaja::hae($row['julkaisija']),
                'kommentteja' => sizeof(Kommentti::haeKirjoituksella($row['id']))
            ));
        }
        return $kirjoitukset;
    }
    
    public static function haeKymmenenViimeisinta() {
        $query = DB::connection()->prepare('SELECT * FROM Kirjoitus Limit 10');
        $query->execute();
        $rows = $query->fetchAll();
        $kirjoitukset = array();

        foreach ($rows as $row) {
            $kirjoitukset[] = new Kirjoitus(array(
                'id' => $row['id'],
                'aihe' => Aihe::hae($row['aihe_id']),
                'nimi' => $row['nimi'],
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => Kayttaja::hae($row['julkaisija']),
                'kommentteja' => sizeof(Kommentti::haeKirjoituksella($row['id']))
            ));
        }
        return $kirjoitukset;
    }

    public static function haeKayttajalla($kayttaja_id) {
        $query = DB::connection()->prepare('SELECT * FROM Kirjoitus '
                . 'WHERE julkaisija = :julkaisija');
        $query->execute(array('julkaisija' => $kayttaja_id));
        $rows = $query->fetchAll();
        $kirjoitukset = array();

        foreach ($rows as $row) {
            $kirjoitukset[] = new Kirjoitus(array(
                'id' => $row['id'],
                'aihe' => Aihe::hae($row['aihe_id']),
                'nimi' => $row['nimi'],
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => Kayttaja::hae($row['julkaisija']),
                'kommentteja' => sizeof(Kommentti::haeKirjoituksella($row['id']))
            ));
        }
        return $kirjoitukset;
    }

    public static function haeAiheella($aihe_id) {
        $query = DB::connection()->prepare('SELECT * FROM Kirjoitus '
                . 'WHERE aihe_id = :aihe_id');
        $query->execute(array('aihe_id' => $aihe_id));
        $rows = $query->fetchAll();
        $kirjoitukset = array();

        foreach ($rows as $row) {
            $kirjoitukset[] = new Kirjoitus(array(
                'id' => $row['id'],
                'aihe' => Aihe::hae($row['aihe_id']),
                'nimi' => $row['nimi'],
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => Kayttaja::hae($row['julkaisija']),
                'kommentteja' => sizeof(Kommentti::haeKirjoituksella($row['id']))
            ));
        }
        return $kirjoitukset;
    }
    
   

    public static function hae($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kirjoitus '
                . 'WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $kirjoitus = new Kirjoitus(array(
                'id' => $row['id'],
                'aihe' => Aihe::hae($row['aihe_id']),
                'nimi' => $row['nimi'],
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => Kayttaja::hae($row['julkaisija'])
            ));
            return $kirjoitus;
        }
        return null;
    }

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Kirjoitus (aihe_id, nimi, sisalto, '
                . 'julkaistu, julkaisija) VALUES (:aihe_id, :nimi, :sisalto, :julkaistu,'
                . ' :julkaisija) RETURNING id');
        $query->execute(array('aihe_id' => $this->aihe, 'nimi' => $this->nimi,
            'sisalto' => $this->sisalto,
            'julkaistu' => $this->julkaistu, 'julkaisija' => $this->julkaisija));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }

    public function paivita() {
        $query = DB::connection()->prepare('UPDATE Kirjoitus SET nimi=:nimi,'
                . ' sisalto=:sisalto WHERE id=:id');
        $query->execute(array('nimi' => $this->nimi, 'sisalto' => $this->sisalto,
            'id' => $this->id));
    }

    public function poista() {
        $kommentit = Kommentti::haeKirjoituksella($this->id);
        foreach ($kommentit as $kommentti) {
            $kommentti->poista();
        }
        $query = DB::connection()->prepare("DELETE FROM Kirjoitus WHERE id=:id");
        $query->execute(array('id' => $this->id));
    }
}
