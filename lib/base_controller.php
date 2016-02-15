<?php

class BaseController {

    public static function get_user_logged_in() {
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user'];
            $user = Kayttaja::hae($user_id);
            return $user;
        }
        return null;
    }

    public static function check_logged_in() {
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään!'));
        }
    }

    public static function check_logged_in_as_admin() {
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään!'));
        } else {
            $user = self::get_user_logged_in();
            if ($user->ryhma_id > 1) {
                Redirect::to('/', array('message' => 'Tarvitset ylläpitäjän oikeudet!'));
            }
        }
    }

    public static function check_self($user_id) {

        $user = self::get_user_logged_in();
        if ($user_id != $user->id && $user->ryhma_id > 1) {
            Redirect::to('/', array('message' => 'Et voi poistaa toisten kirjoituksia!'));
        }
    }
    
    public static function check_detention() {

        $user = self::get_user_logged_in();
        if ($user->ryhma_id > 2) {
            Redirect::to('/', array('message' => 'Olet arestissa, ota yhteyttä ylläpitäjään!'));
        }
    }

}
