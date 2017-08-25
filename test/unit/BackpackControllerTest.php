<?php
namespace CogCrate\Test\Integration;

use CogCrate\Test\DatabaseTestCase;

class BackpackControllerTest extends DatabaseTestCase
{
	public function testCreateBackpack() : void
	{
		$client = $this->createClient();
		$client->request("GET", "/backpack/new");
		$response = $client->getResponse();
		$jsonStr = $response->getContent();
		$jsonArr = json_decode($jsonStr, true);
		$this->assertFalse(empty($jsonArr["id"]));
		$this->assertFalse(empty($jsonArr["capacity"]));
	}

	public function testGetBackpack() : void
	{
		$backpackId = $this->app["service.backpack"]->createBackpack()["id"];
		$client = $this->createClient();
		$client->request("GET", "/backpack/$backpackId");
		$response = $client->getResponse();
		$jsonStr = $response->getContent();
		$jsonArr = json_decode($jsonStr, true);
		$this->assertEquals($backpackId, $jsonArr["id"]);
		$this->assertFalse(empty($jsonArr["capacity"]));
	}

	public function testGetMissingBackpack() : void
	{
		$client = $this->createClient();
		$client->request("GET", "/backpack/-1");
		$response = $client->getResponse();
		$jsonStr = $response->getContent();
		$jsonArr = json_decode($jsonStr, true);
		$this->assertTrue(empty($jsonArr));
	}

	public function testAddItem() : void
	{
		$backpackId = $this->app["service.backpack"]->createBackpack()["id"];
		$itemId = $this->app["db"]->fetchAssoc(
			"SELECT id FROM backpackitem LIMIT 1"
		)["id"];
		$client = $this->createClient();
		$client->request("PUT", "/backpack/$itemId/$backpackId");
		$response = $client->getResponse();
		$jsonStr = $response->getContent();
		$jsonArr = json_decode($jsonStr, true);
		$this->assertTrue($jsonArr["success"]);
	}
}