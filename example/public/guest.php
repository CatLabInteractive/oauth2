<?php

$app = include ('../bootstrap/start.php');

var_dump (\CatLab\OAuth2\Models\OAuth2Service::getInstance()->getGuestAccessToken());