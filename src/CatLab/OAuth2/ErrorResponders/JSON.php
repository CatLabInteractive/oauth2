<?php
namespace CatLab\OAuth2\ErrorResponders;

use Neuron\Net\Response;

class JSON
	extends Responder {

	public function getError ($message)
	{
		return Response::json (array ('error' => array ('message' => $message)))->setStatus (401);
	}

}