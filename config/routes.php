<?php

$routes->get('/', function() {
    LoginController::index();
});
    
$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->post('/aihe', function() {
    AiheController::tallenna();
});

$routes->get('/aihe/uusi/', function() {
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

$routes->post('/kirjoitus/paivita/:id', function($id) {
    KirjoitusController::paivita($id);
});

$routes->post('/kirjoitus/poista/:id', function($id) {
    KirjoitusController::poista($id);
});

$routes->get('/kirjoitus/uusi/:id', function($id) {
    KirjoitusController::luo($id);
});

$routes->get('/kirjoitus/:id', function($id) {
    KirjoitusController::nayta($id);
});

$routes->get('/kirjoitukset', function() {
    KirjoitusController::listaa();
});


$routes->get('/kirjoitus/muokkaa/:id', function($id) {
    KirjoitusController::muokkaa($id);
});


$routes->get('/login', function() {
    LoginController::show();
});

$routes->post('/login', function() {
    LoginController::login();
});

$routes->post('/ryhma', function() {
    RyhmaController::tallenna();
});

$routes->get('/ryhma/uusi', function() {
    RyhmaController::luo();
});

$routes->get('/ryhma/:id', function($id) {
    RyhmaController::nayta($id);
});

$routes->get('/ryhmat', function() {
    RyhmaController::listaa();
});
