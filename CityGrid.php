<?php

// Check for dependencies
if (!function_exists('curl_init')) {
    throw new Exception('citygrid-php needs the CURL PHP extension.');
}

if (!function_exists('json_decode')) {
    throw new Exception('citygrid-php needs the JSON PHP extension.');
}

/**
 * Provides class wrapper for CityGrid API
 */
class CityGrid
{
    /**
     * API methods map
     */
    protected $methods = array(
        'search'        => 'http://api.citygridmedia.com/content/places/v2/search/where?',
        'searchLatLon'  => 'http://api.citygridmedia.com/content/places/v2/search/latlon?',
        'detail'        => 'http://api.citygridmedia.com/content/places/v2/detail?',
        'reviews'       => 'http://api.citygridmedia.com/content/reviews/v2/search/where?',
        'reviewsLatLon' => 'http://api.citygridmedia.com/content/reviews/v2/search/latlon?',
        'offers'        => 'http://api.citygridmedia.com/content/offers/v2/search/where?',
        'offersLatLon'  => 'http://api.citygridmedia.com/content/offers/v2/search/latlon?',
        'offersDetail'  => 'http://api.citygridmedia.com/content/offers/v2/detail?'
    );

    /**
     * Opt decorators for detail endpoint
     */
    protected $optDecorators = array(
        'detail' => array('client_ip' => '127.0.0.1')
    );

    /**
     * Default options
     */
    private $_defaultOptions = array(
        'publisher' => null,
        'format'    => 'json'
    );

    /**
     * Save publisher code
     */
    public function __construct($publisherCode) {
        $this->_defaultOptions['publisher'] = $publisherCode;
    }

    /**
     * Check API methods map
     *
     * @param string $method api method name
     * @param array $params api method options
     * @retrun array response
     */
    public function __call($method, $params) {
        if (array_key_exists($method, $this->methods) && isset($params[0])) {
            return $this->_callMethod($method, $params[0]);
        }

        throw new InvalidArgumentException("Call to undefined method $method.");
    }

    /**
     * Call specific api method
     *
     * @param string $method method name
     * @param array $options api call options
     * @return array response from citygrid
     */
    protected function _callMethod($method, $options) {
        $this->_validateOptions($options);
        $options = $this->_getOptions($options);

        if (isset($this->optDecorators[$method])) {
            $options = array_merge($this->optDecorators[$method], $options);
        }

        return $this->_process($options, $this->methods[$method]);
    }

    /**
     * Process options and execute request
     *
     * @param array $options request options
     * @return mixed data from CityGrid (if foramt is set to json returns array)
     * otherwise raw response
     */
    private function _process($options, $baseURL) {
        $query = $this->_queryString($options);
        $url   = $baseURL . $query;
        $data  = $this->_httpGet($url);

        if ('json' === $options['format']) {
            $data = $this->_decodeData($data);
        }

        return $data;
    }

    /**
     * Validate additions options array
     *
     * @throws InvalidArgumentException if options are invalid
     */
    private function _validateOptions($options) {
        if (!is_array($options)) {
            throw new InvalidArgumentException('CityGrid options should be array.');
        }
    }

    /**
     * Options decorator adds publisher id and stuff
     *
     * @param array $options list of options
     * @return array decorated options
     */
    private function _getOptions($options) {
        return array_merge($this->_defaultOptions, $options);
    }

    /**
     * Compose request query string from options array
     *
     * @param array $options custom request options
     * @return string composed query string
     */
    private function _queryString($options) {
        $query = http_build_query($options);
        return $query;
    }

    /**
     * Execute HTTP GET request via curl
     *
     * @param string $url request url
     * @return string reseponse
     */
    private function _httpGet($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * Decode response data
     *
     * @param string $data raw response data
     * @return array decoded data
     */
    private function _decodeData($data) {
        return json_decode($data, true);
    }
}
