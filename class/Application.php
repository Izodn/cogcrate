<?php
namespace CogCrate;

use Silex\Application as BaseApp;

use Igorw\Silex\ConfigServiceProvider;

class Application extends BaseApp
{
	public function __construct()
	{
		parent::__construct();
		$appPath = __DIR__ . "/..";
		$this['environment.config_replacements'] = ['root' => $appPath];

		$this->register(new \Silex\Provider\ServiceControllerServiceProvider);
		$this->register(new \Silex\Provider\DoctrineServiceProvider);

		$this->register(new Provider\ServiceProvider);
		$this->register(new Provider\ControllerProvider);

		$this->register(new ConfigServiceProvider(
			$appPath . "/config/default.yml"
		));
	}
}