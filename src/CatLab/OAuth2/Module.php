<?php

namespace CatLab\OAuth2;

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

		// Add locales
		Text::getInstance ()->addPath ('catlab.oauth2', __DIR__ . '/locales/');
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