<?php

namespace CatLab\OAuth2;

use CatLab\OAuth2\Mappers\AccessTokenMapper;
use CatLab\OAuth2\Mappers\ApplicationMapper;
use CatLab\OAuth2\Mappers\ClientUsageMapper;

/**
 * Class MapperFactory
 * @package CatLab\OAuth2
 */
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
		return self::getInstance ()->getMapper ('accesstoken', AccessTokenMapper::class);
	}

	/**
	 * @return ApplicationMapper
	 */
	public static function getApplicationMapper ()
	{
		return self::getInstance ()->getMapper ('application', ApplicationMapper::class);
	}

    /**
     * @return ClientUsageMapper
     */
	public static function getClientUsageMapper()
    {
        return self::getInstance ()->getMapper ('clientUsage', ClientUsageMapper::class);
    }
}
