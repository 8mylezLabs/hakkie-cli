#!/usr/bin/php

<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Commands\Docker\DockerCommand;

$app = new Application('hakkie CLI-Tools', 'WIP:v1.0.0');
$app->add(new DockerCommand());
$app->run();