<?php

// Initialize router
$router = new \Neuron\Router ();

// Accounts module
$oauth2module = new \CatLab\OAuth2\Module ();

// Make the module available on /account
$router->module ('/oauth2', $oauth2module);

// Catch the default route
$router->get ('/', function () {
	return \Neuron\Net\Response::template ('home.phpt');
});

return $router;