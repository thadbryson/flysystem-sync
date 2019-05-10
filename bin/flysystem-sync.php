<?php

declare(strict_types = 1);

/**
 * Flysystem Sync CLI
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Console\Application;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use TCB\Flysystem\Commands;

$app = new Application(new Container, new Dispatcher, 'v1.0');
$app->setName('Flysystem Sync CLI');

$app->add(new Commands\Project\Init);
$app->run();
