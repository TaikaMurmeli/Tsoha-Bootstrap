<?php

class Aihe extends BaseModel{

    public $id, $nimi, $kuvaus, $kirjoitukset, $kirjoituksia;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validoi_nimi', 'validoi_kuvaus');
    }

    public static function haeKaikki() {
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
                'kuvaus' => $row['kuvaus'],
                'kirjoituksia' => sizeof(Kirjoitus::haeAiheella($row['id'])),
                'kirjoitukset' => Kirjoitus::haeAiheella($row['id'])
            ));
        }

        return $aiheet;
    }

    public static function hae($id) {
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

    public function tallenna() {
        $query = DB::connection()->prepare('INSERT INTO Aihe (nimi, kuvaus) VALUES (:nimi, :kuvaus) RETURNING id');
        $query->execute(array('nimi' => $this->nimi, 'kuvaus' => $this->kuvaus));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }
    
     public function paivita() {
        $query = DB::connection()->prepare('UPDATE Aihe SET kuvaus=:kuvaus'
                . ' WHERE id=:id');
        $query->execute(array('kuvaus' => $this->kuvaus,
            'id' =>  $this->id));
    }
    
    public function poista() {
        
        $kirjoitukset = Kirjoitus::haeAiheella($this->id);
        foreach ($kirjoitukset as $kirjoitus) {
            $kirjoitus->poista();
        } 
        $query = DB::connection()->prepare("DELETE FROM Aihe WHERE id=:id");
        $query->execute(array('id' =>  $this->id)); 
    }
    
    public function validoi_nimi() {
        return parent::validate_string('nimi', $this->nimi, 3, 30, true, true);
    }
    public function validoi_kuvaus() {
        $method='validate_string';
        return $this->{$method}('kuvaus', $this->kuvaus, 5, 200, true, false);
    }

}
