<?php

class KirjoituksenLukenutKayttaja extends BaseModel{
    public $kirjoitus_id, $kayttaja_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function haeKaikki() {
        // Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM KirjoituksenLukenutKayttaja');
        // Suoritetaan kysely
        $query->execute();
        // Haetaan kyselyn tuottamat rivit
        $rows = $query->fetchAll();
        $lukijat = array();

        // Käydään kyselyn tuottamat rivit läpi
        foreach ($rows as $row) {
            // Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
            $lukijat[] = new KirjoituksenLukenutKayttaja(array(
                'kirjoitus_id' => $row['kirjoitus_id'],
                'kayttaja_id' => $row['kayttaja_id']
            ));
        }

        return $lukijat;
    }

    public static function hae($kirjoitus_id, $kayttaja_id) {
        $query = DB::connection()->prepare('SELECT * FROM KirjoituksenLukenutKayttaja '
                . 'WHERE kirjoitus_id = :kirjoitus_id'
                . ' AND kayttaja_id = :kayttaja_id');
        $query->execute(array('kayttaja_id' => $kayttaja_id, 'kirjoitus_id' => $kirjoitus_id));
        $row = $query->fetch();

        if ($row) {
            $kayttajaryhma = new KirjoituksenLukenutKayttaja(array(
                'kayttaja_id' => $row['kayttaja_id'],
                'kirjoitus_id' => $row['kirjoitus_id']));

            return $kayttajaryhma;
        }

        return null;
    }
    
    public static function haeLukeneetKirjoituksella($kirjoitus_id) {
        $query = DB::connection()->prepare('SELECT * '
                . 'FROM Kayttaja '
                . 'INNER JOIN KirjoituksenLukenutKayttaja '
                . 'ON Kayttaja.id = KirjoituksenLukenutKayttaja.kayttaja_id '
                . 'WHERE KirjoituksenLukenutKayttaja.kirjoitus_id=:kirjoitus_id');
        $query->execute(array('kirjoitus_id' => $kirjoitus_id));
        $rows = $query->fetchAll();
        $kayttajat = array();

        foreach ($rows as $row) {
            $kayttajat[] = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi']
            ));
        }
        return $kayttajat;
    }
    public static function haeLuetutKayttajalla($kayttaja_id) {
        $query = DB::connection()->prepare('SELECT * '
                . 'FROM Kirjoitus '
                . 'INNER JOIN KirjoituksenLukenutKayttaja '
                . 'ON Kirjoitus.id = KirjoituksenLukenutKayttaja.kirjoitus_id '
                . 'WHERE KirjoituksenLukenutKayttaja.kayttaja_id=:kayttaja_id');
        $query->execute(array('kayttaja_id' => $kayttaja_id));
        $rows = $query->fetchAll();
        $kirjoitukset = array();

        foreach ($rows as $row) {
            $kirjoitukset[] = new Kirjoitus(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => Kayttaja::hae($row['julkaisija']),
                'kommentteja' => sizeof(Kommentti::haeKirjoituksella($row['id']))
            ));
        }
        return $kirjoitukset;
    }
    
    public function tallenna() {
        $query = DB::connection()->prepare(''
                . 'INSERT INTO KirjoituksenLukenutKayttaja (kirjoitus_id, kayttaja_id) '
                . 'VALUES (:kirjoitus_id, :kayttaja_id)');
        $query->execute(array('kirjoitus_id' => $this->kirjoitus_id,
            'kayttaja_id' => $this->kayttaja_id));
    }
}
