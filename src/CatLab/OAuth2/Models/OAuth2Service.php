<?php

namespace CatLab\OAuth2\Models;

use Neuron\Config;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\RefreshToken;
use OAuth2\Server;
use OAuth2\Storage\Pdo;

class OAuth2Service {

	public static function getInstance ()
	{
		static $in;
		if (!isset ($in))
		{
			$in = new self ();
		}
		return $in;
	}

	private function __construct ()
	{
		$dsn = 'mysql:dbname='.Config::get ('database.mysql.database').';host='.Config::get ('database.mysql.host');
		$username = Config::get ('database.mysql.username');
		$password = Config::get ('database.mysql.password');

		$pdoconfig = array
		(
			'client_table' => 'oauth2_clients',
			'access_token_table' => 'oauth2_access_tokens',
			'refresh_token_table' => 'oauth2_refresh_tokens',
			'code_table' => 'oauth2_authorization_codes',
			'user_table' => 'oauth2_users',
			'jwt_table'  => 'oauth2_jwt',
			'scope_table'  => 'oauth2_scopes',
			'public_key_table'  => 'oauth2_public_keys',
		);

		$storage = new Pdo (array('dsn' => $dsn, 'username' => $username, 'password' => $password), $pdoconfig);
		//$storage = DB::connection()->getPdo();

		$this->server = new Server($storage, array (
			'allow_implicit' => true,
			'access_lifetime' => 60 * 60 * 24 * 365 * 2
		));

		$this->server->addGrantType (new AuthorizationCode($storage));

		$this->server->addGrantType (new RefreshToken ($storage, array (
			'always_issue_new_refresh_token' => true,
			'refresh_token_lifetime' => 60 * 60 * 24 * 31 * 2
		)));
	}

	/**
	 * @return Server
	 */
	public function getServer ()
	{
		return $this->server;
	}

	public function translateRequest (\Neuron\Net\Request $request)
	{
		$headers = array ();
		foreach ($request->getHeaders () as $k => $v)
		{
			$headers[strtoupper ($k)] = $v;
		}

		return new \OAuth2\Request (
			$request->getParameters (),
			$request->getPost (),
			array (),
			$request->getCookies (),
			array (),
			array (),
			null,
			$headers
		);
	}
}