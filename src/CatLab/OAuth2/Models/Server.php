<?php

namespace CatLab\OAuth2\Models;

use OAuth2\OpenID\Controller\AuthorizeController as OpenIDAuthorizeController;

/**
 * Class Server
 * @package CatLab\OAuth2\Models
 */
class Server extends \OAuth2\Server {

    /**
     * @return mixed
     */
    public function getGuestAccessToken () {
        $responsetypes = $this->getDefaultResponseTypes ();
        return $responsetypes['token']->createAccessToken ('__guest__', -1, 'guest');
    }

    /**
     * @return AuthorizeController|OpenIDAuthorizeController
     */
    protected function createDefaultAuthorizeController()
    {
        if (!isset($this->storages['client'])) {
            throw new \LogicException("You must supply a storage object implementing OAuth2\Storage\ClientInterface to use the authorize server");
        }
        if (0 == count($this->responseTypes)) {
            $this->responseTypes = $this->getDefaultResponseTypes();
        }
        if ($this->config['use_openid_connect'] && !isset($this->responseTypes['id_token'])) {
            $this->responseTypes['id_token'] = $this->createDefaultIdTokenResponseType();
            if ($this->config['allow_implicit']) {
                $this->responseTypes['id_token token'] = $this->createDefaultIdTokenTokenResponseType();
            }
        }

        $config = array_intersect_key($this->config, array_flip(explode(' ', 'allow_implicit enforce_state require_exact_redirect_uri')));

        if ($this->config['use_openid_connect']) {
            return new OpenIDAuthorizeController($this->storages['client'], $this->responseTypes, $config, $this->getScopeUtil());
        }

        return new AuthorizeController($this->storages['client'], $this->responseTypes, $config, $this->getScopeUtil());
    }
}