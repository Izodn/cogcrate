<?php
namespace CogCrate\Test\Unit\Service;

use CogCrate\Test\DatabaseTestCase;

class BackpackItemServiceTest extends DatabaseTestCase
{
	public function testGetItem() : void
	{
		$queryItem1 = $this->app["db"]->fetchAssoc(
			"SELECT id, name, weight FROM backpackitem LIMIT 1"
		);
		$queryItem2 = $this->app["db"]->fetchAssoc(
			"SELECT id, name, weight FROM backpackitem WHERE id != ? LIMIT 1",
			[$queryItem1["id"]]
		);
		$serviceItem1 = $this->app["service.backpackitem"]
			->getItem($queryItem1["id"]);
		$serviceItem2 = $this->app["service.backpackitem"]
			->getItem($queryItem2["id"]);
		$this->assertEquals($queryItem1, $serviceItem1);
		$this->assertEquals($queryItem2, $serviceItem2);
		$this->assertNotequals($serviceItem1, $serviceItem2);
	}
}