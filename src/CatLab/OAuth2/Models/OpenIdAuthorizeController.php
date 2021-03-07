<?php

namespace CatLab\OAuth2\Models;

/**
 * Class OpenIdAuthorizeController
 * @package CatLab\OAuth2\Models
 */
class OpenIdAuthorizeController extends \OAuth2\OpenID\Controller\AuthorizeController
{
    /**
     * @param \OAuth2\RequestInterface $request
     * @param \OAuth2\ResponseInterface $response
     * @param mixed $user_id
     * @return array|mixed
     */
    protected function buildAuthorizeParameters($request, $response, $user_id)
    {
        $params = parent::buildAuthorizeParameters($request, $response, $user_id);
        return TrackingParameterInjector::addTrackingParameters($params, $request, $response, $user_id);
    }
}
