<?php
use Rocketeer\Facades\Rocketeer;

Rocketeer::after( 'deploy', array(
    'composer install',
    'npm install',
    'gulp build --production'
));
