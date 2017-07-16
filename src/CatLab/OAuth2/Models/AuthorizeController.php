<?php

namespace CatLab\OAuth2\Models;

/**
 * Class AuthorizeController
 * @package CatLab\OAuth2\Models
 */
class AuthorizeController extends \OAuth2\Controller\AuthorizeController
{
    /**
     * Internal method for validating redirect URI supplied
     *
     * @param string $inputUri The submitted URI to be validated
     * @param string $registeredUriString The allowed URI(s) to validate against.  Can be a space-delimited string of URIs to
     *                                    allow for multiple URIs
     *
     * @see http://tools.ietf.org/html/rfc6749#section-3.1.2
     * @return bool
     */
    protected function validateRedirectUri($inputUri, $registeredUriString)
    {
        if (!$inputUri || !$registeredUriString) {
            return false; // if either one is missing, assume INVALID
        }

        $registered_uris = preg_split('/\s+/', $registeredUriString);
        foreach ($registered_uris as $registered_uri) {

            // turn into regex
            $pattern = preg_quote($registered_uri,'/');
            $pattern = str_replace('\*', '[a-zA-Z0-9]*', $pattern);

            $check = preg_match('/^' . $pattern . '$/i' , $inputUri);

            if ($check) {
                return true;
            }

            /*
            if ($this->config['require_exact_redirect_uri']) {
                // the input uri is validated against the registered uri using exact match
                if (strcmp($inputUri, $registered_uri) === 0) {
                    return true;
                }
            } else {
                // the input uri is validated against the registered uri using case-insensitive match of the initial string
                // i.e. additional query parameters may be applied
                if (strcasecmp(substr($inputUri, 0, strlen($registered_uri)), $registered_uri) === 0) {
                    return true;
                }
            }
            */
        }

        return false;
    }
}