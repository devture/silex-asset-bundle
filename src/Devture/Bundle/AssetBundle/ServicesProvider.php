<?php
namespace Devture\Bundle\AssetBundle;

class ServicesProvider implements \Pimple\ServiceProviderInterface, \Silex\Api\BootableProviderInterface {

	private $config;
	private $namespace;

	public function __construct($namespace, array $config) {
		$requiredConfigKeys = array(
			'allow_cdn',
			'asset_path',
			'asset_url_prefix',
		);
		foreach ($requiredConfigKeys as $k) {
			if (!array_key_exists($k, $config)) {
				throw new \InvalidArgumentException(sprintf('The %s parameter passed to %s is missing.', $k, __CLASS__));
			}
		}

		$config['asset_path'] = rtrim($config['asset_path'], '/');
		$config['asset_url_prefix'] = rtrim($config['asset_url_prefix'], '/');

		$this->namespace = $namespace;
		$this->config = $config;
	}

	public function register(\Pimple\Container $container) {

	}

	public function boot(\Silex\Application $app) {
		$app['twig']->addExtension(new Twig\AssetExtension($this->namespace, $this->config));
	}

}
