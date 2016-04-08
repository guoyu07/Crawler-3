<?php

/**
 * @file
 * Example TestSuite with tests for admin users.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Crawler\Container;
use Crawler\Environment;
use Crawler\Test\Test;

// You can add multiple environments to a test-suite.
$environments = [];
$environments[] = new Environment('drupal7', __DIR__ . '/environments/drupal7_local.yml');

// For every environment a container is created that runs test-sequences.
// Each test-sequence runs in a new session.
foreach ($environments as $env) {
  $container = new Container($env, "/tmp/crawler-log.csv", 'Developer Tests ' . $env->label());

  /**
   * Without Garbage.
   **/

  // Flush caches.
  $test_filename = __DIR__ . '/tests/developer/developer_cache_clear_all.yml';
  $test = new Test($container);
  $test->createTestActionSequenceFromYML($test_filename);
  $container->testSuite()->addTest($test);

  // Run Cron.
  $test_filename = __DIR__ . '/tests/developer/developer_run_cron.yml';
  $test = new Test($container);
  $test->createTestActionSequenceFromYML($test_filename);
  $container->testSuite()->addTest($test);

  // Create and Delete View.
  $test_filename = __DIR__ . '/tests/developer/developer_views_create_and_delete.yml';
  $test = new Test($container);
  $test->createTestActionSequenceFromYML($test_filename);
  $container->testSuite()->addTest($test);

  /**
   * With Garbage (cannot remove content)
   */

  // Create Node/Page.
  $test_filename = __DIR__ . '/tests/developer/developer_page_create.yml';
  $test = new Test($container);
  $test->createTestActionSequenceFromYML($test_filename);
  $container->testSuite()->addTest($test);


  // Execute the tests.
  $container->testSuite()->doExecute();
}