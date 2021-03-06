<?php

/**
 * @file
 * Example TestSuite with tests for anonymous users.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Crawler\Container;
use Crawler\Environment;
use Crawler\Logger\CsvLogger;
use Crawler\Logger\ScreenLogger;
use Crawler\TestSequence\TestSequence;

// You can add multiple environments to a test-suite.
$environments = [];
$environments[] = new Environment('drupal8', __DIR__ . '/environments/drupal8_local.yml');

// For every environment a container is created that runs test-sequences.
// Each test-sequence runs in a new session.
foreach ($environments as $env) {
  $container = new Container($env, 'Anonymous Tests');

  // Add loggers.
  $csvLogger = new CsvLogger('/tmp/crawler-log.csv');
  $container->getDispatcher()->addListener('sequence.log', [
    $csvLogger,
    'log',
  ]);
  $screenLogger = new ScreenLogger();
  $container->getDispatcher()->addListener('sequence.log', [
    $screenLogger,
    'log',
  ]);


  // Visit frontpage.
  $test_filename = __DIR__ . '/tests/anonymous/anonymous_visit_frontpage.yml';
  $test = new TestSequence($container);
  $test->createTestActionSequenceFromYML($test_filename);
  $container->testSuite()->addTest($test);


  // Execute the tests.
  $container->testSuite()->doExecute();
}
