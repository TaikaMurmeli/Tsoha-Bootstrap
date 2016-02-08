<?php

class Kommentti {

    public $id, $kirjoitus_id, $content, $published, $publisher;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        // Alustetaan kysely tietokantayhteydellämme
        $query = DB::connection()->prepare('SELECT * FROM Kommentti');
        // Suoritetaan kysely
        $query->execute();
        // Haetaan kyselyn tuottamat rivit
        $rows = $query->fetchAll();
        $kommentit = array();

        // Käydään kyselyn tuottamat rivit läpi
        foreach ($rows as $row) {
            // Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
            $kommentit[] = new Kommentti(array(
                'id' => $row['id'],
                'kirjotus_id' => $row['kirjoitus_id'],
                'content' => $row['content'],
                'published' => $row['published'],
                'publisher' => $row['publisher']
            ));
        }

        return $kommentit;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Kommentti WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $kommentti = new Kommentti(array(
                'id' => $row['id'],
                'kirjoitus_id' => $row['kirjoitus_id'],
                'content' => $row['content'],
                'published' => $row['published'],
                'publisher' => $row['publisher']
            ));

            return $kommentti;
        }

        return null;
    }
}     