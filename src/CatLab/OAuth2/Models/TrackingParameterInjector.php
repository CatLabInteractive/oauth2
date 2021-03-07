<?php

namespace CatLab\OAuth2\Models;

use Neuron\Net\QueryTrackingParameters;
use OAuth2\Request;

/**
 * Class TrackingParameterInjector
 * @package CatLab\OAuth2\Models
 */
class TrackingParameterInjector
{
    /**
     * @param $parameters
     * @param Request $request
     * @param $response
     * @param $user_id
     * @return mixed
     */
    public static function addTrackingParameters($parameters, $request, $response, $user_id)
    {
        foreach (self::getTrackingParameters() as $trackingParameter) {
            if ($val = $request->query($trackingParameter)) {
                $parameters[$trackingParameter] = $val;
            }
        }

        return $parameters;
    }

    /**
     * Return the parameters that need to be passed through the flow.
     * @return string[]
     */
    protected static function getTrackingParameters()
    {
        return QueryTrackingParameters::instance()->queryParameters;
    }
}
