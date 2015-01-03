<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 25/12/14
 * Time: 15:55
 */

namespace CatLab\OAuth2;

use CatLab\OAuth2\Mappers\AccessTokenMapper;
use CatLab\OAuth2\Mappers\ApplicationMapper;

class MapperFactory {

	public static function getInstance ()
	{
		static $in;
		if (!isset ($in))
		{
			$in = new self ();
		}
		return $in;
	}

	private $mapped = array ();

	public function setMapper ($key, $mapper)
	{
		$this->mapped[$key] = $mapper;
	}

	public function getMapper ($key, $default)
	{
		if (isset ($this->mapped[$key]))
		{
			return $this->mapped[$key];
		}
		else
		{
			$this->mapped[$key] = new $default ();
		}
		return $this->mapped[$key];
	}

	/**
	 * @return AccessTokenMapper
	 */
	public static function getAccessTokenMapper ()
	{
		return self::getInstance ()->getMapper ('accesstoken', '\CatLab\OAuth2\Mappers\AccessTokenMapper');
	}

	/**
	 * @return ApplicationMapper
	 */
	public static function getApplicationMapper ()
	{
		return self::getInstance ()->getMapper ('application', '\CatLab\OAuth2\Mappers\ApplicationMapper');
	}
}