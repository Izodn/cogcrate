<?php
namespace CogCrate\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

use CogCrate\Service;

class ServiceProvider implements ServiceProviderInterface
{
	public function register(Container $app)
	{
		$app["service.backpackitem"] = function ($app) {
			return new Service\BackpackItemService($app["db"]);
		};
		$app["service.backpack"] = function ($app) {
			return new Service\BackpackService(
				$app["db"],
				$app["service.backpackitem"]
			);
		};
	}
}