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
     * CityGrid publish code
     */
    private $_publisherCode;

    /**
     * Place details endpoint
     */
    const URL_API_PLACES_DETAIL = 'http://api.citygridmedia.com/content/places/v2/detail?';

    /**
     * Default options for place details endpoint URL
     */
    private $_defaultOptionsDetails = array(
        'id_type'       => null,
        'phone'         => null,
        'customer_only' => null,
        'all_results'   => null,
        'review_count'  => null,
        'placement'     => null,
        'format'        => 'json',
        'callback'      => null,
        'i'             => null
    );

    /**
     * Lat-Lon search endpoint URL
     */
    const URL_API_SEARCH_LATLON = 'http://api.citygridmedia.com/content/places/v2/search/latlon?';

    /**
     * Default options for lat-lon search endpoint
     */
    private $_defaultOptionsSearchLatLon = array(
        'what'       => null,
        'type'       => null,
        'lat'        => null,
        'lon'        => null,
        'radius'     => null,
        'page'       => 1,
        'rpp'        => 20,
        'sort'       => 'dist',
        'format'     => 'xml',
        'placement'  => null,
        'has_offers' => null,
        'histograms' => null,
        'i'          => null
    );

    /**
     * Where search endpoint URL
     */
    const URL_API_SEARCH_WHERE = 'http://api.citygridmedia.com/content/places/v2/search/where?';

    /**
     * Default options for where search endpoint
     */
    private $_defaultOptionsSearchWhere = array(
        'what'       => null,
        'type'       => null,
        'where'      => null,
        'page'       => 1,
        'rpp'        => 20,
        'sort'       => 'dist',
        'format'     => 'xml',
        'placement'  => null,
        'has_offers' => null,
        'histograms' => null,
        'i'          => null
    );

    /**
     * Save publisher code
     */
    public function __construct($publisherCode) {
        $this->_publisherCode = $publisherCode;
    }

    /**
     * Get place details from CityGrid
     *
     * @param integer $id place id
     * @options array $options additional request options
     * @throws InvalidArgumentException if options are invalid
     * @return array CityGrid response
     */
    public function details($options) {
        $this->_validateOptions($options);
        $options = array_merge(
            $this->_defaultOptionsDetails,
            $options,
            array(
                'client_ip' => '127.0.0.1',
                'publisher' => $this->_publisherCode
            )
        );

        return $this->_process($options, self::URL_API_PLACES_DETAIL);
    }

    /**
     * Lat-Lon CityGrid search
     *
     * @param array $options search request options
     * @throws InvalidArgumentException if options are invalid
     * @return array response data
     */
    public function searchLatLon($options) {
        $this->_validateOptions($options);
        $options = array_merge(
            $this->_defaultOptionsSearchLatLon,
            $options,
            array(
                'publisher' => $this->_publisherCode
            )
        );

        return $this->_process($options, self::URL_API_SEARCH_LATLON);
    }

    /**
     * Where CityGrid search
     *
     * @param array $options search request params
     * @throws InvalidArgumentException if options are invalid
     * @return array response data
     */
    public function searchWhere($options) {
        $this->_validateOptions($options);
        $options = array_merge(
            $this->_defaultOptionsSearchWhere,
            $options,
            array(
                'publisher' => $this->_publisherCode
            )
        );

        return $this->_process($options, self::URL_API_SEARCH_WHERE);
    }

    /**
     * Process options and execute request
     *
     * @param array $options request options
     * @return array data from CityGrid
     */
    private function _process($options, $baseURL) {
        $query = $this->_queryString($options);
        $url   = $baseURL . $query;
        $data  = $this->_httpGet($url);

        return $this->_decodeData($data);
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
