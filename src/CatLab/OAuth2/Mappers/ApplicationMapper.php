<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 25/12/14
 * Time: 15:55
 */

namespace CatLab\OAuth2\Mappers;

use Neuron\DB\Query;

class ApplicationMapper {

	public function create ($clientid, $password, $redirect_url, $layout, $userid)
	{
		$data = array (
			'client_id' => $clientid,
			'client_secret' => $password,
			'redirect_uri' => $redirect_url,
			'login_layout' => $layout
		);

		Query::insert ('oauth2_clients', $data)->execute ();
	}

}