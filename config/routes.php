<?php

$routes->get('/', function() {
    KirjautuminenController::etusivu();
});

$routes->post('/aihe', function() {
    AiheController::tallenna();
});

$routes->post('/aihe/paivita/:id', function($id) {
    AiheController::paivita($id);
});

$routes->post('/aihe/poista/:id', function($id) {
    AiheController::poista($id);
});

$routes->get('/aihe/muokkaa/:id', function($id) {
    AiheController::muokkaa($id);
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

$routes->post('/kayttaja/paivita/:id', function($id) {
    KayttajaController::paivita($id);
});

$routes->post('/kayttaja/poista/:id', function($id) {
    KayttajaController::poista($id);
});

$routes->get('/kayttaja/muokkaa/:id', function($id) {
    KayttajaController::muokkaa($id);
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

$routes->post('/kirjoitus/luettu/:id', function($id) {
    KirjoitusController::merkitseLuetuksi($id);
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

$routes->post('/kommentti', function() {
    KirjoitusController::lisaaKommentti();
});


$routes->post('/kommentti/:id', function($id) {
    KirjoitusController::poistaKommentti($id);
});

$routes->get('/login', function() {
    KirjautuminenController::nayta();
});

$routes->post('/login', function() {
    KirjautuminenController::kirjauduSisaan();
});

$routes->get('/logout', function() {
    KirjautuminenController::kirjauduUlos();
});

$routes->get('/ryhma/:id', function($id) {
    RyhmaController::nayta($id);
});

$routes->get('/ryhmat', function() {
    RyhmaController::listaa();
});
