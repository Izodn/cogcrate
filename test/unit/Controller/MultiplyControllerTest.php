<?php
namespace CogCrate\Test\Unit\Controller;

use CogCrate\Test\ApplicationTestCase;
use CogCrate\Controller\MultiplyController;

class MultiplyControllerTest extends ApplicationTestCase
{
	private function getApiResponse(float $input) : array
	{
		$client = $this->createClient();
		$client->request("GET", "/multiply/$input");
		$response = $client->getResponse();
		return json_decode($response->getContent(), true);
	}

	public function testMultiplyApi() : void
	{
		$controller = $this->app["controller.multiply"];
		$this->assertEquals(
			$this->getApiResponse(2)["result"],
			642
		);
		$this->assertEquals(
			$this->getApiResponse(-2)["result"],
			-642
		);
	}

	public function testMultiplyZero() : void
	{
		$this->assertEquals(MultiplyController::multiply(0.0), 0.0);
	}
	public function testMultiplyOne() : void
	{
		$this->assertEquals(MultiplyController::multiply(1.0), 321.0);
		$this->assertEquals(MultiplyController::multiply(1.0, 0, 1), 1.0);
		$this->assertEquals(MultiplyController::multiply(1.0, 0, 2), 2.0);
	}
	public function testMultiplyPositive() : void
	{
		$this->assertEquals(MultiplyController::multiply(2.0), 642.0);
		$this->assertEquals(MultiplyController::multiply(2.0, 0, 2), 4.0);
		$this->assertEquals(MultiplyController::multiply(2.2, 0, 2), 4.4);
	}
	public function testMultiplyNegative() : void
	{
		$this->assertEquals(MultiplyController::multiply(-2.0), -642.0);
		$this->assertEquals(MultiplyController::multiply(-2.0, 0, 2), -4.0);
		$this->assertEquals(MultiplyController::multiply(-2.2, 0, 2), -4.4);
	}
}