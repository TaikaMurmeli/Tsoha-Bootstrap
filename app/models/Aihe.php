<?php

class Aihe {

    public $id, $nimi, $kuvaus;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        // Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM Aihe');
        // Suoritetaan kysely
        $query->execute();
        // Haetaan kyselyn tuottamat rivit
        $rows = $query->fetchAll();
        $aiheet = array();

        // Käydään kyselyn tuottamat rivit läpi
        foreach ($rows as $row) {
            // Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
            $aiheet[] = new Aihe(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'kuvaus' => $row['kuvaus']
            ));
        }

        return $aiheet;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Aihe WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $aihe = new Aihe(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'kuvaus' => $row['kuvaus']
            ));

            return $aihe;
        }

        return null;
    }
}
