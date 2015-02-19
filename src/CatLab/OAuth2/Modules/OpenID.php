<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 19/02/15
 * Time: 1:00
 */

namespace CatLab\OAuth2\Modules;


use CatLab\OAuth2\Models\OAuth2Service;
use Neuron\Router;

class OpenID
	extends Base {

	public function __construct ()
	{
		OAuth2Service::instanciate (
			array (
				'use_openid_connect' => true,
				'issuer' => 'catlab'
			)
		);

		$this->setScopes ('openid', 'email', 'profile');
	}

	/**
	 * Register the routes required for this module.
	 * @param Router $router
	 * @return void
	 */
	public function setRoutes (Router $router)
	{
		parent::setRoutes ($router);
	}
}