<?php

class Kirjoitus extends BaseModel{

    public $id, $aihe_id, $nimi, $sisalto, $julkaistu, $julkaisija;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        // Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM Kirjoitus');
        // Suoritetaan kysely
        $query->execute();
        // Haetaan kyselyn tuottamat rivit
        $rows = $query->fetchAll();
        $kirjoitukset = array();

        // Käydään kyselyn tuottamat rivit läpi
        foreach ($rows as $row) {
            // Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
            $kirjoitukset[] = new Kirjoitus(array(
                'id' => $row['id'],
                'aihe_id' => $row['aihe_id'],
                'nimi' => $row['nimi'],
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => $row['julkaisija']
            ));
        }

        return $kirjoitukset;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kirjoitus WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $kirjoitus = new Kirjoitus(array(
                'id' => $row['id'],
                'aihe_id' => $row['aihe_id'],
                'nimi' => $row['nimi'],
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => $row['julkaisija']
            ));

            return $kirjoitus;
        }

        return null;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Kirjoitus (nimi, sisalto, julkaistu, julkaisija) VALUES (:nimi, :sisalto, :julkaistu, :julkaisija) RETURNING id');
        $query->execute(array('nimi' => $this->nimi, 'sisalto' => $this->sisalto, 'julkaistu' => $this->julkaistu, 'julkaisija' => $this->julkaisija));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }


}
