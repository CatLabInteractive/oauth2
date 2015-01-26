<?php
namespace CatLab\OAuth2\ErrorResponders;

use Neuron\Net\Response;

abstract class Responder {

	public function getError ($message)
	{
		return Response::error ($message, 401);
	}

}