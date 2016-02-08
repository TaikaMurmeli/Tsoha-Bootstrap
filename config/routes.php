<?php

$routes->get('/', function() {
    HelloWorldController::index();
});
    
$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->post('/aihe', function() {
    AiheController::tallenna();
});

$routes->get('/aihe/uusi', function() {
    AiheController::luo();
});

$routes->get('/aihe/:id', function($id) {
    AiheController::nayta($id);
});

$routes->get('/aiheet', function() {
    AiheController::listaa();
});

$routes->post('/kayttaja', function() {
    KayttajaController::tallenna();
});

$routes->get('/kayttaja/uusi', function() {
    KayttajaController::luo();
});

$routes->get('/kayttaja/:id', function($id) {
    KayttajaController::nayta($id);
});

$routes->get('/kayttajat', function() {
    KayttajaController::listaa();
});



$routes->post('/kirjoitus', function() {
    KirjoitusController::tallenna();
});

$routes->get('/kirjoitus/uusi', function() {
    KirjoitusController::luo();
});

$routes->get('/kirjoitus/:id', function($id) {
    KirjoitusController::nayta($id);
});

$routes->get('/kirjoitukset', function() {
    KirjoitusController::listaa();
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
