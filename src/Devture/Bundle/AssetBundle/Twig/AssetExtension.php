<?php
namespace Devture\Bundle\AssetBundle\Twig;

class AssetExtension extends \Twig_Extension {

	private $namespace;
	private $config;

	public function __construct($namespace, array $config) {
		$this->namespace = $namespace;
		$this->config = $config;
	}

	public function getName() {
		return 'devture_asset_extesion_' . $this->namespace;
	}

	public function getFunctions() {
		$makeName = function ($name) {
			return sprintf('%s_%s', $this->namespace, $name);
		};

		return array(
			new \Twig_SimpleFunction($makeName('is_cdn_allowed'), array($this, 'isCdnAllowed')),
			new \Twig_SimpleFunction($makeName('url'), array($this, 'getAssetUrl')),
			new \Twig_SimpleFunction($makeName('url_with_cdn'), array($this, 'getAssetUrlWithCdn')),
			new \Twig_SimpleFunction($makeName('content'), array($this, 'getAssetContent')),
		);
	}

	/**
	 * @return boolean
	 */
	public function isCdnAllowed() {
		return $this->config['allow_cdn'];
	}

	public function getAssetUrl($relativePath) {
		$relativePathTrimmed = ltrim($relativePath, '/');

		$filePath = $this->config['asset_path'] . '/' . $relativePathTrimmed;
		$fileUri = $this->config['asset_url_prefix'] . '/' . $relativePathTrimmed;

		if (file_exists($filePath)) {
			$fileUri = $fileUri . '?' . filemtime($filePath);
		}

		return $fileUri;
	}

	public function getAssetUrlWithCdn($relativePath, $cdnFullUri) {
		if ($this->isCdnAllowed()) {
			return $cdnFullUri;
		}
		return $this->getAssetUrl($relativePath);
	}

	public function getAssetContent($relativePath) {
		$relativePathTrimmed = ltrim($relativePath, '/');

		$filePath = $this->config['asset_path'] . '/' . $relativePathTrimmed;

		if (!file_exists($filePath)) {
			return null;
		}

		return file_get_contents($filePath);
	}

}
