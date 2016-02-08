<?php

class Ryhma {
    //put your code here
    public $id, $nimi, $kuvaus;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
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
            $ryhmat[] = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'kuvaus' => $row['kuvaus']
            ));
        }

        return $ryhmat;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Ryhma WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $ryhma = new Ryhma(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'kuvaus' => $row['kuvaus']
            ));

            return $ryhma;
        }

        return null;
    }
}
