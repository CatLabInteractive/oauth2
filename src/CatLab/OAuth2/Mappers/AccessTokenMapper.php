<?php

namespace CatLab\OAuth2\Mappers;

use Neuron\DB\Query;
use Neuron\Interfaces\Models\User;

/**
 * Class AccessTokenMapper
 * @package CatLab\OAuth2\Mappers
 */
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

		if (count($data) > 0) {
			return intval ($data[0]['user_id']);
		}
		return null;
	}

    /**
     * @param User $user
     */
	public function deleteFromUser(User $user)
    {
        Query::delete('oauth2_access_tokens', [ 'user_id' => $user->getId() ])->execute();
    }

}
