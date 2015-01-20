<?php

namespace CatLab\OAuth2;

use CatLab\OAuth2\Models\OAuth2Service;
use Neuron\Exceptions\InvalidParameter;
use Neuron\Net\Request;

class Verifier
{
	private static function getInstance ()
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

	}

	/** @var int */
	private $userid;
	private $token;

	public static function isValid (Request $request)
	{
		$in = self::getInstance ();

		$oauth2request = OAuth2Service::getInstance ()->translateRequest ($request);
		$valid = OAuth2Service::getInstance ()->getServer ()->verifyResourceRequest ($oauth2request);

		if ($valid)
		{
			$data = OAuth2Service::getInstance ()->getServer ()->getAccessTokenData ($oauth2request);

			$in->token = $data['access_token'];
			$in->userid = $data['user_id'];
		}

		return $valid;
	}

	public static function getUserId ()
	{
		if (!isset (self::getInstance ()->userid))
			throw new InvalidParameter ("isValid was not called, cannot get user id.");

		return self::getInstance ()->userid;
	}

	public static function getAccessToken ()
	{
		if (!isset (self::getInstance ()->token))
			throw new InvalidParameter ("isValid was not called, cannot get access token.");

		return self::getInstance ()->token;
	}

}