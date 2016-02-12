<?php

class Kommentti extends BaseModel{

    public $id, $kirjoitus, $sisalto, $julkaistu, $julkaisija;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_teksti');
    }

    public static function findByArticle($kirjoitus_id) {
        // Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM Kommentti '
                . 'WHERE kirjoitus_id = :kirjoitus_id');
        // Suoritetaan kysely
        $query->execute(array('kirjoitus_id' => $kirjoitus_id));
        // Haetaan kyselyn tuottamat rivit
        $rows = $query->fetchAll();
        $kommentit = array();

        // Käydään kyselyn tuottamat rivit läpi
        foreach ($rows as $row) {
            // Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
            $kommentit[] = new Kommentti(array(
                'id' => $row['id'],
                'kirjoitus' => Kirjoitus::find($row['kirjoitus_id']),
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => Kayttaja::find($row['julkaisija'])
            ));
        }

        return $kommentit;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kommentti '
                . 'WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $kommentti = new Kommentti(array(
                'id' => $row['id'],
                'kirjoitus' => Kirjoitus::find($row['kirjoitus_id']),
                'sisalto' => $row['sisalto'],
                'julkaistu' => $row['julkaistu'],
                'julkaisija' => Kayttaja::find($row['julkaisija'])
            ));
            return $kommentti;
        }
        return null;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Kommentti (kirjoitus_id,'
                . ' sisalto, julkaistu, julkaisija) '
                . 'VALUES (:kirjoitus_id, :sisalto, :julkaistu, :julkaisija)'
                . ' RETURNING id');
        $query->execute(array('kirjoitus_id'=> $this->kirjoitus,
            'sisalto' => $this->sisalto, 'julkaistu' => $this->julkaistu,
            'julkaisija' => $this->julkaisija));
        $row = $query->fetch();
//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }
    
    public function delete() {
        $query = DB::connection()->prepare("DELETE FROM Kommentti WHERE id=:id");
        $query->execute(array('id' =>  $this->id)); 
    } 
    
    public function validate_teksti() {
        $method='validate_string';
        return $this->{$method}('Teksti', $this->sisalto, 5, 4000, true);
    }
}     