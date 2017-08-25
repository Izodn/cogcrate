<?php
namespace CogCrate\Test;

use Silex\WebTestCase;

use Symfony\Component\Stopwatch\Stopwatch;

use CogCrate\Application;

class ApplicationTestCase extends WebTestCase
{
	const MAX_RUN_DURATION = 5000;

	protected $stopwatch;

	public function createApplication()
	{
		$app = new Application();
		$this->stopwatch = new StopWatch();
		return $app;
	}

	public function setUp()
	{
		parent::setUp();
		$this->app->boot();
		$this->stopwatch->start("test");
	}

	public function tearDown()
	{
		$ended = $this->stopwatch->stop("test");

		if ($ended->getDuration() > Self::MAX_RUN_DURATION) {
			throw new \Exception("Unit test took too long.");
		}
		parent::tearDown();
	}

	// TODO:
	// If you have enough time, look into why removal of this causes phpunit to
	// constantly warn about un-tested test cases (which shouldn't be tested).
	public function test() { $this->assertTrue(true); }
}