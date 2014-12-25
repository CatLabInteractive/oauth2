<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 25/12/14
 * Time: 15:55
 */

namespace CatLab\OAuth2\Mappers;

use Neuron\DB\Query;

class AccessTokenMapper {

	/**
	 * @param $token
	 * @return null|int
	 */
	public function getUserIdFromAccessToken ($token)
	{
		$data = Query::select (
			'oauth2_access_tokens',
			array (
				'user_id'
			),
			array (
				'access_token' => $token
			)
		)->execute ();

		if ($data)
		{
			return intval ($data[0]['user_id']);
		}
		return null;
	}

}