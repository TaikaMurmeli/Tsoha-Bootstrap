<?php

require 'app/models/Kayttaja.php';

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        View::make('suunnitelmat/index.html');
    }

    public static function sandbox() {
        // Testaa koodiasi täällä
//        View::make('helloworld.html');

        $doom = new Kirjoitus(array(
            'nimi' => 'da',
            'sisalto' => 'eil',
        ));
        $errors = $doom->errors();

        Kint::dump($errors);
    }

    public static function login() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/login.html');
    }

    public static function user() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/user.html');
    }

    public static function users() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/users.html');
    }

    public static function article() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/article.html');
    }

    public static function topic() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/topic.html');
    }

    public static function topics() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/topics.html');
    }

    public static function group() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/group.html');
    }

    public static function groups() {
        // Testaa koodiasi täällä
        View::make('suunnitelmat/groups.html');
    }

}
