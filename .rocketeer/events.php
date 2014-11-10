<?php
use Rocketeer\Facades\Rocketeer;

Rocketeer::before( 'deploy', array(
    'composer self-update',
    'composer install',
    'npm install --cache',
    'gulp build --production'
));
