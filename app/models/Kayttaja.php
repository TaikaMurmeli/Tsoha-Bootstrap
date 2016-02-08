<?php

class Kayttaja extends BaseModel {

    public $id, $nimi, $salasana;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
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
            $kayttajat[] = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'salasana' => $row['salasana']
            ));
        }

        return $kayttajat;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kayttaja WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $kayttaja = new Kayttaja(array(
                'id' => $row['id'],
                'nimi' => $row['nimi'],
                'salasana' => $row['salasana']
            ));

            return $kayttaja;
        }

        return null;
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Kayttaja (nimi, salasana) VALUES (:nimi, :salasana) RETURNING id');
        $query->execute(array('nimi' => $this->nimi, 'salasana' => $this->salasana));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }

}
