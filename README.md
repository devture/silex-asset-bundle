# Asset Bundle

Provides asset linking.

## Configuration example

Example for the article bundle data source:

	"AssetBundle": {
		"allow_cdn": true,
		"asset_path": "/srv/http/example.com/web",
		"asset_url_prefix": ""
	}

## Initializing

	$namespace = 'asset';
	$app->register(new \Devture\Bundle\AssetBundle\ServicesProvider($asset, $this['config']['AssetBundle']));

All provided Twig functions will be prefixed with `asset_` (the namespace used when registering).

## Usage

### Basic Asset Loading

Using the `asset_url(LOCAL_FILE)` function would generate a URL to the file.
The file will be timestamped with its last-modification-time, for caching (cache-busting) purposes.

	<script type='text/javascript' src='{{ asset_url('/js/jquery.min.js') }}'></script>

Result:

	<script type='text/javascript' src='/js/jquery.min.js?1448329282'></script>

### CDN-backed Asset Loading

Just like `asset_url(LOCAL_FILE)`, you can use `asset_url_with_cdn(LOCAL_FILE, CDN_MIRROR_URI)`
to specify a resource which is available both at the `LOCAL_FILE` path and at the `CDN_MIRROR_URI` URI.

Based on the `allow_cdn` configuration parameter (passed when registering the bundle),
the appropriate URI will be used.

Example:

	<script type='text/javascript'
		src='{{ asset_url('/js/jquery.min.js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js') }}'></script>

Result (if CDN usage is allowed in the configuration):

	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>

Result (if CDN usage is NOT allowed in the configuration):

	<script type='text/javascript' src='/js/jquery.min.js?1448329282'></script>


If you rely on CDN-loading, you may also with to make use of [Subresource Integrity](http://www.w3.org/TR/SRI/),
to ensure that the resource you're loading is the one you expect.

Example:

	<script type='text/javascript'
		src='{{ asset_url('/js/jquery.min.js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js') }}'
		integrity="sha384-6ePHh72Rl3hKio4HiJ841psfsRJveeS+aLoaEf3BWfS+gTF0XdAqku2ka8VddikM"
		crossorigin="anonymous"></script>
