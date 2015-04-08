<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 8/04/15
 * Time: 15:26
 */

namespace CatLab\OAuth2\Models;


class Server
	extends \OAuth2\Server {


	public function getGuestAccessToken () {
		$responsetypes = $this->getDefaultResponseTypes ();
		return $responsetypes['token']->createAccessToken ('__guest__', -1, 'guest');
	}
}