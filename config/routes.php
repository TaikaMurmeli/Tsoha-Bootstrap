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

   $routes->get('/user/1', function() {
    HelloWorldController::user();
  });
  
  
   $routes->get('/users', function() {
    HelloWorldController::users();
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