<?php
namespace CogCrate\Test;

class DatabaseTestCase extends ApplicationTestCase
{
	public function setUp()
	{
		parent::setUp();
		$this->app["db"]->beginTransaction();
	}

	public function tearDown()
	{
		$this->app["db"]->rollback();
		parent::tearDown();
	}
}