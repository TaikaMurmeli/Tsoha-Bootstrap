<?php

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function errors() {
        // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors = array();

        foreach ($this->validators as $validator) {
            $validator_errors=$this->$validator();
            $errors = array_merge($errors, $validator_errors);
        }

        return $errors;
    }

    public function validate_string($kentta, $string, $minLength, $maxLength, 
            $eiNull, $eiErikoismerkkeja) {
        $errors = array();
        if ($eiNull = true) {
            if ($string == '' || $string == null) {
                $errors[] = "Kenttä \"{$kentta}\" ei saa olla tyhjä!";
            }
        }
        if (strlen($string) < $minLength) {
            $errors[] = "Kentän \"{$kentta}\" pituuden tulee olla vähintään {$minLength} merkkiä!";
        }

        if (strlen($string) > $maxLength) {
            $errors[] = "Kentän \"{$kentta}\" pituuden tulee olla enintään {$maxLength} merkkiä!";
        }
//        if($eiErikoismerkkeja) {
//            if(!ctype_alnum($string)) {
//                $errors[] = "Kentässä \"{$kentta}\" ei saa olla erikoismerkkejä!";
//            }
//        }

        return $errors;
    }

}
