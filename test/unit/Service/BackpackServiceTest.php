<?php
namespace CogCrate\Test\Unit\Service;

use CogCrate\Test\DatabaseTestCase;

class BackpackServiceTest extends DatabaseTestCase
{
	public function testGetBackpack() : void
	{
		$service = $this->app["service.backpack"];
		$backpack1 = $service->createBackpack();
		$backpack2 = $service->createBackpack();
		$this->assertEquals(
			$service->getBackpack($backpack1["id"]),
			$backpack1
		);
		$this->assertEquals(
			$service->getBackpack($backpack2["id"]),
			$backpack2
		);
		$this->assertNotEquals($backpack1, $backpack2);
		$this->assertFalse(empty($backpack1));
	}

	public function testGetContents()
	{
		$service = $this->app["service.backpack"];
		$backpack = $service->createBackpack();
		$item = $this->app["db"]->fetchAssoc(
			"SELECT id, name, weight FROM backpackitem LIMIT 1"
		);
		$this->assertTrue(empty($service->getContents($backpack["id"])));
		$service->addItem($item["id"], $backpack["id"]);
		$this->assertFalse(empty($service->getContents($backpack["id"])));
	}

	public function testAddItem()
	{
		$service = $this->app["service.backpack"];
		$backpack = $service->createBackpack();
		$item = $this->app["db"]->fetchAssoc(
			"SELECT id, name, weight FROM backpackitem WHERE weight = 1 LIMIT 1"
		);
		$add1 = $service->addItem($item["id"], $backpack["id"]);
		$add2 = $service->addItem($item["id"], $backpack["id"]);
		$add3 = $service->addItem($item["id"], $backpack["id"]);
		$add4 = $service->addItem($item["id"], $backpack["id"]);
		$add5 = $service->addItem($item["id"], $backpack["id"]);
		$add6 = $service->addItem($item["id"], $backpack["id"]);
		$this->assertTrue($add1 && $add2 && $add3 && $add4 && $add5);
		$this->assertFalse($add6);
		$this->assertEquals(count($service->getContents($backpack["id"])), 5);
	}

	public function testRemoveItem()
	{
		$service = $this->app["service.backpack"];
		$backpack = $service->createBackpack();
		$item = $this->app["db"]->fetchAssoc(
			"SELECT id, name, weight FROM backpackitem WHERE weight = 1 LIMIT 1"
		);
		$service->addItem($item["id"], $backpack["id"]);
		$service->addItem($item["id"], $backpack["id"]);
		$this->assertEquals(count($service->getContents($backpack["id"])), 2);
		$del1 = $service->removeItem($item["id"], $backpack["id"]);
		$this->assertEquals(count($service->getContents($backpack["id"])), 1);
		$del2 = $service->removeItem($item["id"], $backpack["id"]);
		$this->assertEquals(count($service->getContents($backpack["id"])), 0);
		$del3 = $service->removeItem($item["id"], $backpack["id"]);
		$this->assertTrue($del1 && $del2);
		$this->assertFalse($del3);
	}
}