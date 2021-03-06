<?php

/**
 * @file
 * contains: Crawler\TestSuite
 */

namespace Crawler;

use Crawler\TestSequence\TestSequenceInterface;

/**
 * Class TestSuite.
 *
 * @package Crawler
 */
class TestSuite {
  protected $container;
  protected $tests = [];
  protected $groups = ['Anonymous', 'Developer', 'Member'];

  /**
   * TestSuite constructor.
   *
   * @param Container $container
   *    The injected container.
   * @param array $groups
   *    An array with test-groups.
   */
  public function __construct(Container $container, $groups = []) {
    $this->container = $container;
//    if (!empty($groups)) {
//      $this->groups = $groups;
//    }
//    $this->tests = $this->autoloader($this->groups);

  }

  public function addTest(TestSequenceInterface $test) {
    $this->tests[] = $test;
  }

  /**
   * Executes all tests.
   */
  public function doExecute() {
    print 'TestSequence suite started' . PHP_EOL . PHP_EOL . PHP_EOL;
    $stopwatch = $this->container->stopwatch()->start('test_suite');

    print 'Crawler\TestSuite doExecute() called' . PHP_EOL;
    foreach ($this->tests as $test) {
      $test->doExecute();
    }

    $event = $stopwatch->stop('test_suite');
    print PHP_EOL . 'Full test suite Duration: ' . $event->getDuration() . PHP_EOL;
  }
}
