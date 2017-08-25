<?php
namespace CogCrate\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use Silex\Application;
use Silex\Api\BootableProviderInterface;
use Silex\Api\ControllerProviderInterface;

use CogCrate\Controller;

class ControllerProvider implements
	ControllerProviderInterface,
	BootableProviderInterface,
	ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app["controller.item"] = function ($app) {
			return new Controller\ItemController($app["db"]);
		};
		$app["controller.multiply"] = function ($app) {
			return new Controller\MultiplyController();
		};
		$app["controller.backpack"] = function ($app) {
			return new Controller\BackpackController($app['service.backpack']);
		};
	}

	public function connect(Application $app)
	{
		$controllers = $app["controllers_factory"];

		$controllers->get("/item/random", "controller.item:getRandom");
		$controllers->get(
			"/multiply/{input}",
			"controller.multiply:multiplyBy321"
		);
		$controllers->get(
			"/backpack/new",
			"controller.backpack:createNew"
		);
		$controllers->get(
			"/backpack/{backpackId}",
			"controller.backpack:get"
		);
		$controllers->get(
			"/backpack/contents/{backpackId}",
			"controller.backpack:contents"
		);
		$controllers->put(
			"/backpack/{itemId}/{backpackId}",
			"controller.backpack:addItem"
		);

		return $controllers;
	}

	public function boot(Application $app)
	{
		$app->mount("/", $this);
	}
}