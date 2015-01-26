<?php

namespace CatLab\OAuth2;

use CatLab\OAuth2\Models\OAuth2Service;
use Neuron\Application;
use Neuron\Core\Template;
use Neuron\Tools\Text;
use Neuron\URLBuilder;

class Module
	implements \Neuron\Interfaces\Module
{

	/** @var string $routepath */
	private $routepath;

	/**
	 * Set template paths, config vars, etc
	 * @param string $routepath The prefix that should be added to all route paths.
	 * @return void
	 */
	public function initialize ($routepath)
	{
		// Set path
		$this->routepath = $routepath;

		// Add templates
		Template::addPath (__DIR__ . '/templates/', 'CatLab/OAuth2/');

		Application::getInstance ()->on ('dispatch:before', array ($this, 'setRequestUser'));

		// Add locales
		Text::getInstance ()->addPath ('catlab.oauth2', __DIR__ . '/locales/');
	}

	public function setRequestUser (\Neuron\Net\Request $request)
	{
		$request->addUserCallback ('oauth2', function (\Neuron\Net\Request $request) {

			if (Verifier::isValid ($request)) {
				$userid = Verifier::getUserId ();

				$user = \Neuron\MapperFactory::getUserMapper ()->getFromId ($userid);
				if ($user)
					return $user;
			}

			return null;
		});
	}

	/**
	 * Set the scopes
	 * @param $defaultScope
	 * @param array $supportedScopes
	 */
	public function setScopes ($defaultScope, array $supportedScopes)
	{
		// configure your available scopes

		$memory = new \OAuth2\Storage\Memory (array(
			'default_scope' => $defaultScope,
			'supported_scopes' => $supportedScopes
		));
		$scopeUtil = new \OAuth2\Scope($memory);

		$server = OAuth2Service::getInstance ()->getServer ();
		$server->setScopeUtil($scopeUtil);
	}

	/**
	 * Register the routes required for this module.
	 * @param \Neuron\Router $router
	 * @return void
	 */
	public function setRoutes (\Neuron\Router $router)
	{
		$router->match ('GET|POST', $this->routepath . '/authorize', '\CatLab\OAuth2\Controllers\AuthorizeController@authorize');
		$router->match ('GET|POST', $this->routepath . '/register', '\CatLab\OAuth2\Controllers\RegisterController@register');

		// Add filter
		$router->addFilter ('oauth2', array ($this, 'routerVerifier'));
	}

	public function routerVerifier (\Neuron\Models\Router\Filter $filter)
	{
		if (Verifier::isValid ($filter->getRequest ())) {
			return true;
		}

		return $this->setAccessHeaders (\Neuron\Net\Response::error ('Provided oauth2 signature is invalid', 400));
	}

	private function setAccessHeaders (\Neuron\Net\Response $response)
	{
		$response->setHeader ('Access-Control-Allow-Origin', '*');
		$response->setHeader ('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');
		$response->setHeader ('Access-Control-Allow-Headers', 'origin, x-requested-with, content-type, access_token, authorization');

		return $response;
	}

	/**
	 * @return string
	 */
	public function getRoutePath ()
	{
		return $this->routepath;
	}

	/**
	 * @param $subpath
	 * @param array $params
	 * @return string
	 */
	public function getURL ($subpath, $params = array ())
	{
		return URLBuilder::getURL (URLBuilder::partify ($this->routepath) . $subpath, $params);
	}
}