<?php
namespace CogCrate\Test\Integration;

use CogCrate\Test\DatabaseTestCase;

class ItemControllerTest extends DatabaseTestCase
{
	public function testGetRandom() : void
	{
		// Testing random is hard...
		for ($i = 0; $i < 100; $i++) {
			$client = $this->createClient();
			$client->request("GET", "/item/random");
			$response = $client->getResponse();
			$json = $response->getContent();
			$item = json_decode($json, true);
			$this->assertFalse(empty($item["id"]));
			$this->assertFalse(empty($item["name"]));
		}
	}

	public function testAfterIdNoRecord()
	{
		$db = $this->app["db"];
		$controller = $this->app["controller.item"];
		$lastId = $db->fetchAssoc("SELECT MAX(id) AS max FROM item")["max"];
		$results = $controller->afterId($lastId + 1);

		// We want this to return empty for a few reasons.
		// If this returns something that isn't entirely random, then you could
		// end up inside a false sense of success. Say there was a mechinism for
		// spawning random items in a world upon enemy death. A silent failure
		// means spawning the same gun every time; An obvious failure could
		// result in not spawning anything all the while making devs aware of
		// the issue via logging.
		$this->assertTrue(empty($results));

		$results = $controller->afterId($lastId);
		$this->assertFalse(empty($results));

		$results = $controller->afterId(0);
		$this->assertFalse(empty($results));

		$results = $controller->afterId(-1);
		$this->assertFalse(empty($results));
	}
}