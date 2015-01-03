<?php
namespace CatLab\OAuth2\Controllers;



use CatLab\OAuth2\MapperFactory;
use Neuron\Core\Template;
use Neuron\Net\Response;
use Neuron\URLBuilder;

class RegisterController
	extends Base {

	public function register ()
	{
		// Must be logged in
		if (! ($user = $this->request->getUser ()))
		{
			//echo '<p>' . ('This page is only available for registered users.') . '</p>';
			$login = URLBuilder::getURL ('account/login', array (
				'return' => $this->module->getURL ('register', $_GET)
			));

			return Response::redirect ($login);
		}

		if ($this->request->isPost ())
		{
			$template = new Template ('CatLab/OAuth2/registerdone.phpt');

			$clientid = uniqid ('oauth2', true);
			$password = md5 (uniqid ('secret'));

			$redirect_url = $this->request->input ('redirecturi');

			$layout = $this->request->input ('layout');

			MapperFactory::getApplicationMapper ()->create ($clientid, $password, $redirect_url, $layout, $this->request->getUser ()->getId ());

			$template->set ('clientid', $clientid);
			$template->set ('clientsecret', $password);
			$template->set ('redirecturi', $redirect_url);

			return Response::template ($template);
		}

		$template = new Template ('CatLab/OAuth2/register.phpt');
		$template->set ('action', $this->module->getURL ('register'));
		return Response::template ($template);
	}
}