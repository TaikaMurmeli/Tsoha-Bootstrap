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

$routes->post('/user', function() {
    KayttajaController::store();
});

$routes->get('/user/new', function() {
    KayttajaController::create();
});

$routes->get('/user/:id', function($id) {
    KayttajaController::nayta($id);
});

$routes->get('/users', function() {
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
