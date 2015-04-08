<?php

namespace CatLab\OAuth2\Modules;

use CatLab\OAuth2\Models\OAuth2Service;

class OAuth2
	extends Base
{
	/**
	 * Register the routes required for this module.
	 * @param \Neuron\Router $router
	 * @return void
	 */
	public function setRoutes (\Neuron\Router $router)
	{
		parent::setRoutes ($router);

	}

	/**
	 * Returns an access token for a guest user (id = -1)
	 * UserMapper should return a Guest model in case id -1 is requested.
	 */
	public function getGuestAccessToken () {

		$service = OAuth2Service::getInstance ();
		return $service->getGuestAccessToken ();

	}
}