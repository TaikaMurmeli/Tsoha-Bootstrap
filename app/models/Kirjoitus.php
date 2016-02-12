<?php

class Kirjoitus extends BaseModel {

    public $id, $aihe, $nimi, $sisalto, $julkaistu, $julkaisija;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_nimi', 'validate_sisalto');
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Kirjoitus');
        $query->execute();
        $rows = $query->fetchAll();
        $kirjoitukset = array();

        foreach ($rows as $row) {
            $kirjoitukset[] = new Kirjoitus(array(
                'id' => $row['id'],
                'aihe' => Aihe::find($row['aihe_id']),
                'nimi' => $row['nimi'],
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => Kayttaja::find($row['julkaisija'])
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
                'aihe' => Aihe::find($row['aihe_id']),
                'nimi' => $row['nimi'],
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => Kayttaja::find($row['julkaisija'])
            ));
            return $kirjoitus;
        }
        return null;
    }

    
    public function validate_nimi() {
        return parent::validate_string('Otsikko', $this->nimi, 3, 50, true);
    }
    public function validate_sisalto() {
        $method='validate_string';
        return $this->{$method}('Sisältö', $this->sisalto, 5, 4000, true);
    }
    
    public function save() {
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
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE Kirjoitus SET nimi=:nimi,'
                . ' sisalto=:sisalto WHERE id=:id');
        $query->execute(array('nimi' => $this->nimi, 'sisalto' => $this->sisalto,
            'id' =>  $this->id));
    }
    
    public function delete() {
        $query = DB::connection()->prepare("DELETE FROM Kirjoitus WHERE id=:id");
        $query->execute(array('id' =>  $this->id)); 
    } 
}
