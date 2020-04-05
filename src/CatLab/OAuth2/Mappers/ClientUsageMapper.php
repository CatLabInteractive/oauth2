<?php

namespace CatLab\OAuth2\Mappers;

use CatLab\OAuth2\Models\OAuth2Service;
use Neuron\DB\Query;
use Neuron\Interfaces\Models\User;

/**
 * Class ClientUsageMapper
 * @package CatLab\OAuth2\Mappers
 */
class ClientUsageMapper {

    const TABLE = 'oauth2_client_users';

    /**
     * @param User $user
     * @param $clientId
     */
    public function touch(User $user, $clientId)
    {
        $existing = Query::select(
            self::TABLE,
            [ '*' ],
            [
                'user_id' => $user->getId(),
                'client_id' => $clientId
            ]
        )->execute();

        if (count($existing) === 0) {
            Query::insert(self::TABLE, [
                'user_id' => $user->getId(),
                'client_id' => $clientId
            ])->execute();
        }
    }

    /**
     * @param User $user
     * @return array
     */
    public function getClientsFromUser(User $user)
    {
        $clientIds = Query::select(
            self::TABLE,
            [ 'client_id' ],
            [
                'user_id' => $user->getId()
            ]
        )->execute();

        $server = OAuth2Service::getInstance()->getServer();

        $out = [];
        foreach ($clientIds as $clientId) {
            $out[] = $server->getStorage('client')->getClientDetails($clientId['client_id']);
        }

        return $out;
    }

    /**
     * @param User $user
     */
    public function removeFromUser(User $user)
    {
        Query::delete(
            self::TABLE,
            [
                'user_id' => $user->getId()
            ]
        )->execute();
    }

}
