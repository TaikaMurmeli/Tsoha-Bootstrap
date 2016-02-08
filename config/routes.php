<?php

$routes->get('/', function() {
    HelloWorldController::index();
});
    
$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/topic/1', function() {
    HelloWorldController::topic();
});

$routes->get('/topics', function() {
    HelloWorldController::topics();
});

$routes->post('/kayttaja', function() {
    KayttajaController::store();
});

$routes->get('/kayttaja/uusi', function() {
    KayttajaController::create();
});

$routes->get('/kayttaja/:id', function($id) {
    KayttajaController::nayta($id);
});

$routes->get('/kayttajat', function() {
    KayttajaController::lista();
});


$routes->get('/article', function() {
    HelloWorldController::article();
});

$routes->get('/group/1', function() {
    HelloWorldController::group();
});

$routes->get('/groups', function() {
    HelloWorldController::groups();
});


$routes->get('/login', function() {
    HelloWorldController::login();
});
