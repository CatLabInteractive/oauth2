<?php
namespace CatLab\OAuth2\Controllers;

use CatLab\OAuth2\Models\OAuth2Service;
use Neuron\Core\Template;
use Neuron\DB\Query;
use Neuron\URLBuilder;
use OAuth2\Response;

class AuthorizeController
	extends Base {

	public function authorize ()
	{
		$server = OAuth2Service::getInstance ()->getServer ();
		$request = OAuth2Service::getInstance ()->translateRequest ($this->request);

		$display = 'mobile';

		$response = new Response();

		// validate the authorize request
		if (!$server->validateAuthorizeRequest($request, $response))
		{
			$response->send();
			die;
		}

		$clientid = $server->getAuthorizeController ()->getClientId ();
		$clientdata = $server->getStorage('client')->getClientDetails ($clientid);

		// Check if we should log the user out (after a revoke)
		$this->checkForLogout ($server);

		$layout = $clientdata['login_layout'];
		$skipAuthorization = $clientdata['skip_authorization'];

		if ($layout)
		{
			$display = $layout;
		}

		if (! ($user = $this->request->getUser ()))
		{
			//echo '<p>' . ('This page is only available for registered users.') . '</p>';
			$login = URLBuilder::getURL ('accounts/login', array ('return' => URLBuilder::getURL ('oauth2/authorize/next', $_GET)));

			return \Neuron\Net\Response::redirect ($login);
		}

		$user_id = $user->getId ();

		if (!$skipAuthorization)
		{
			$fields = array ();

			$fields['client_id'] = $clientid;
			$fields['u_id'] = $user_id;

			// Check in the database if already approved
			$data = Query::select ('oauth2_app_authorizations', array ('*'), $fields)->execute ();

			if (count ($data) > 0)
			{
				$skipAuthorization = true;
			}
		}

		// Should we skip authorization?
		if ($skipAuthorization)
		{
			$response = $server->handleAuthorizeRequest($request, $response, true, $user_id);
			$this->storeAccessTokenInSession ($response);

			$response->send ();
			return \Neuron\FrontController::getInstance()->getResponse ();
		}

		// display an authorization form
		if (empty($_POST))
		{
			return $this->showAuthorizationDialog ($clientdata);
		}

		// print the authorization code if the user has authorized your client
		$is_authorized = ($_POST['authorized'] === 'yes');

		$response = $server->handleAuthorizeRequest($request, $response, $is_authorized, $user_id);
		if ($is_authorized)
		{
			$response = $server->handleAuthorizeRequest($request, $response, true, $user_id);
			$this->storeAccessTokenInSession ($response);

			// this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
			//$code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
			//exit("SUCCESS! Authorization Code: $code");

			// Also store this in our database
			$fields = array ();

			$fields['client_id'] = $clientid;
			$fields['u_id'] = $user_id;
			$fields['authorization_date'] = array (time (), Query::PARAM_DATE);

			// Destroy the session
			//Session::getInstance ()->destroy ();

			Query::replace ('oauth2_app_authorizations', $fields)->execute ();

		}
		$response->send();

		return;
	}

	private function checkForLogout (\OAuth2\Server $server)
	{

	}

	private function showAuthorizationDialog ($clientdata)
	{
		$template = new Template ();
		$template->set ('clientdata', $clientdata);
		$template->set ('action', URLBuilder::getURL ('CatLab/OAuth2/authorize', $_GET));

		return \Neuron\Net\Response::template ($template);
	}

}