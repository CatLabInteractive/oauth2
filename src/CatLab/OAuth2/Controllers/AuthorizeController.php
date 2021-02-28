<?php

namespace CatLab\OAuth2\Controllers;

use CatLab\OAuth2\Models\OAuth2Service;
use Neuron\Application;
use Neuron\Core\Template;
use Neuron\DB\Query;
use Neuron\Interfaces\Models\User;
use Neuron\URLBuilder;
use OAuth2\Request;
use OAuth2\Response;
use Neuron\Net\QueryTrackingParameters;

class AuthorizeController extends Base
{
    /**
     * @param null $parameter
     * @return \Neuron\Net\Response|void
     * @throws \Neuron\Exceptions\DataNotSet
     */
    public function authorize($parameter = null)
    {
        // Check for reset
        if (
            $parameter == 'reset' ||
            $this->request->input('reset')
        ) {
            $this->request->getSession()->set('catlab-user-id', null);
            $this->request->clearUser();
            unset ($_GET['reset']);

            //return \Neuron\Net\Response::redirect(URLBuilder::getURL('oauth2/authorize', $_GET));
        }

        $display = 'mobile';

        $server = OAuth2Service::getInstance()->getServer();
        $request = OAuth2Service::getInstance()->translateRequest($this->request);

        $response = new Response();

        // Check for cancel parameter
        if ($this->request->input('cancel')) {
            $server->handleAuthorizeRequest($request, $response, false, null);
            $response->send();
            return;
        }

        // validate the authorize request
        if (!$server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }

        $clientid = $server->getAuthorizeController()->getClientId();
        $clientdata = $server->getStorage('client')->getClientDetails($clientid);

        // Check if we should log the user out (after a revoke)
        $this->checkForLogout($server);

        $layout = $clientdata['login_layout'];
        $skipAuthorization = $clientdata['skip_authorization'];

        if ($layout) {
            $display = $layout;
        }

        if (!($user = $this->request->getUser())) {
            //echo '<p>' . ('This page is only available for registered users.') . '</p>';

            $login = $this->getLoginUrl();

            // Store some details.
            $session = Application::getInstance()
                ->getRouter()
                ->getRequest()
                ->getSession();

            $session->set('oauth2_client_id', $clientid);
            if (isset($clientdata['product_id'])) {
                $session->set('product_id', $clientdata['product_id']);
            }

            return \Neuron\Net\Response::redirect($login);
        }

        $user_id = $user->getId();

        if (!$skipAuthorization) {
            $fields = array();

            $fields['client_id'] = $clientid;
            $fields['u_id'] = $user_id;

            // Check in the database if already approved
            $data = Query::select('oauth2_app_authorizations', array('*'), $fields)->execute();

            if (count($data) > 0) {
                $skipAuthorization = true;
            }
        }

        // Should we skip authorization?
        if ($skipAuthorization) {
            $response = $server->handleAuthorizeRequest($request, $response, true, $user_id);
            $this->storeAccessTokenInSession($request, $response, $user, $clientid);

            $response->send();
            //return \Neuron\FrontController::getInstance()->getResponse ();
            return;
        }

        // display an authorization form
        if (empty($_POST)) {
            return $this->showAuthorizationDialog($clientdata);
        }

        // print the authorization code if the user has authorized your client
        $is_authorized = ($_POST['authorized'] === 'yes');

        if ($is_authorized) {
            $response = $server->handleAuthorizeRequest($request, $response, true, $user_id);
            $this->storeAccessTokenInSession($request, $response, $user, $clientid);

            // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
            //$code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
            //exit("SUCCESS! Authorization Code: $code");

            // Also store this in our database
            $fields = array();

            $fields['client_id'] = $clientid;
            $fields['u_id'] = $user_id;
            $fields['authorization_date'] = array(time(), Query::PARAM_DATE);

            // Destroy the session
            //Session::getInstance ()->destroy ();

            Query::replace('oauth2_app_authorizations', $fields)->execute();

        }
        $response->send();

        return;
    }

    /**
     * Get the redirect url for the user to login
     * @return string
     */
    protected function getLoginUrl()
    {
        $returnQueryParameters = [];
        $loginQueryParameters = [];

        foreach ($_GET as $k => $v) {
            if (in_array($k, QueryTrackingParameters::instance()->queryParameters)) {
                $loginQueryParameters[$k] = $v;
            } else {
                $returnQueryParameters[$k] = $v;
            }
        }

        $returnUrl = URLBuilder::getURL('oauth2/authorize', $returnQueryParameters);

        return URLBuilder::getURL(
            'account/login',
            array_merge(
                $loginQueryParameters,
                array(
                    'return' => $returnUrl,
                    'cancel' => $returnUrl . '&cancel=1'
                )
            )
        );
    }

    /**
     * @param \OAuth2\RequestInterface $request
     * @param \OAuth2\ResponseInterface $response
     * @param User $user
     * @param $clientid
     */
    private function storeAccessTokenInSession(
        \OAuth2\RequestInterface $request,
        \OAuth2\ResponseInterface $response,
        User $user,
        $clientid
    ) {
        $location = $response->getHttpHeader('Location');
        $parsed = parse_url($location);

        if (isset ($parsed['fragment']))
            $fragment = $parsed['fragment'];
        else {
            $fragment = $parsed['query'];
        }

        parse_str($fragment, $attributes);
        if (isset ($attributes['access_token'])) {
            //$_SESSION['oauth2_access_token'] = $attributes['access_token'];
            Application::getInstance()
                ->getRouter()
                ->getRequest()
                ->getSession()
                ->set('oauth2_access_token', $attributes['access_token']);

            // Also notify the module.
            $this->module->onAuthorize($user, $request, $response, $attributes['access_token'], $clientid);
        } elseif (isset ($attributes['code'])) {
            // Also notify the module.
            $this->module->onAuthorize($user, $request, $response, $attributes['code'], $clientid);
        }
    }

    public function token()
    {
        $server = OAuth2Service::getInstance()->getServer();
        //$request = OAuth2Service::getInstance ()->translateRequest ($this->request);
        $request = Request::createFromGlobals();


        $response = new Response ();
        $server->handleTokenRequest($request, $response);
        $response->send();
    }

    private function checkForLogout(\OAuth2\Server $server)
    {

    }

    private function showAuthorizationDialog($clientdata)
    {
        $template = new Template ('CatLab/OAuth2/authorize.phpt');
        $template->set('clientdata', $clientdata);
        $template->set('action', URLBuilder::getURL('oauth2/authorize', $_GET));

        return \Neuron\Net\Response::template($template);
    }

}
