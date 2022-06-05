<?php

// require_once __DIR__ . '/vendor/autoload.php';

// $database = require __DIR__ . '/Config/settings.php';

// use Lucaslz\CheckDatabaseConnection\DatabaseConnection;

// $databaseConnection = new DatabaseConnection($database['databases']);

// var_dump($databaseConnection->getConnection());

$output = shell_exec('./check_docker.sh');
echo $output;