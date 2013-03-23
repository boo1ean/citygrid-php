CityGrid API wrapper for PHP
============================

See [CityGrid API docs](http://docs.citygridmedia.com/display/citygridv2/CityGrid+APIs) for details

Setup
=====

To get access to CityGrid api you need to instantiate api driver with your publisher code.

```php
<?php
require 'CityGrid.php';
$publisher = 'test'; // Your publisher code goes here
$cg = new CityGrid($publisher);
```

Now **$cg** object could be used for api calls.

Places Search
=============

The Places Search API provides programmatic access to CityGrid's local search engine, delivering basic place details together with metadata allowing subsequent refinement and expansion searches.

Search For Places Using Where
-----------------------------

The where endpoint returns places whose geography is specified with free-form text. The text can consist of a zip code, the name of a neighborhood or city, or a street address.

[Query params and response data reference](http://docs.citygridmedia.com/display/citygridv2/Places+API#PlacesAPI-SearchUsingWhere)

**Example:** Find movie theaters in zip code 90045

```php
<?php
$options = array(
    'type'  => 'movietheater',
    'where' => '90045'
);

$result = $cg->search($options);
```

Search For Places Using Latitude and Longitude
----------------------------------------------

The latlon endpoint allows you to search for places using a geographic region defined by latitude and longitude.

[Query params and response data reference](http://docs.citygridmedia.com/display/citygridv2/Places+API#PlacesAPI-SearchUsingLatitudeandLongitude)

**Example:** Find movie theaters within 5 miles of latitude 34.03N, longitude118.28W

```php
<?php
$options = array(
    'type'   => 'movietheater',
    'lat'    => '34.03',
    'lon'    => '-118.28',
    'radius' => '5'
);

$result = $cg->searchLatLon($options);
```

Places Detail
-------------

The Places Details API provides programmatic access to CityGrid's local listings data, including businesses and events.

[Query params and response data reference](http://docs.citygridmedia.com/display/citygridv2/Places+API#PlacesAPI-PlacesDetail)

**Example:**  Find the place with listing id 10100230, placement "search_page", and client ip 127.0.0.1 (default)

```php
<?php
$options = array(
    'id'        => '10100230',
    'id_type'   => 'cs',
    'placement' => 'search_page'
);

$result = $cg->detail($options);
```

Offers Search
=============

The Offers Search API provides programmatic access to CityGrid's local search engine, delivering offers, deals, and coupons together with metadata including URIs for subsequent refinement and expansion searches.

Search For Offers Using Where
-----------------------------

The where endpoint allows you to search for offers using a place name or zip code. It is useful for free-form text and broad geographical region-based searches.

[Query params and response data reference](http://docs.citygridmedia.com/display/citygridv2/Offers+API#OffersAPI-SearchUsingWhere)

**Example:** Find offers for Sushi restaurants in Los Angeles

```php
<?php
$options = array(
    'what'  => 'sushi',
    'where' => 'los angeles,ca'
);

$result = $cg->offers($options);
```

Search For Offers Using Latitude and Longitude
----------------------------------------------

The latlon endpoint allows you to search for offers using a geographic region defined by latitude and longitude.

[Query params and response data reference](http://docs.citygridmedia.com/display/citygridv2/Offers+API#OffersAPI-SearchUsingLatitudeandLongitude)

**Example:** Find offers for sushi in latitude 34.03N, longitude118.28W

```php
<?php
$options = array(
    'what'   => 'sushi',
    'lat'    => '34.03',
    'lon'    => '-118.28',
    'radius' => '5'
);

$result = $cg->offersLatLon($options);
```

Offers Detail
-------------

The Offers Detail API provides programmatic access to CityGrid's local search engine, delivering offers, deals, and coupons for specific listings.

[Query params and response data reference](http://docs.citygridmedia.com/display/citygridv2/Offers+API#OffersAPI-OffersDetail)

**Example:** Look up Offer with id cg_64232690

```php
<?php
$options = array(
    'id' => 'cg_64232690'
);

$result = $cg->offersDetail($options);
```

Reviews Search
==============

The Reviews Search API provides programmatic access to CityGrid's reviews database.

Search For Reviews Using Where
------------------------------

The where endpoint returns places whose geography is specified with free-form text. The text can consist of a zip code, the name of a neighborhood or city, or a street address.

[Query params and response data reference](http://docs.citygridmedia.com/display/citygridv2/Reviews+API#ReviewsAPI-ReviewsSearch)

**Example:** Find reviews for sushi restaurants in Los Angeles

```php
<?php
$options = array(
    'where' => 'los angeles,ca',
    'what'  => 'sushi'
);

$result = $cg->reviews($options);
```

Search For Reviews Using Latitude and Longitude
-----------------------------------------------

The latlon endpoint allows you to search for places using a geographic region defined by latitude and longitude.

[Query params and response data reference](http://docs.citygridmedia.com/display/citygridv2/Reviews+API#ReviewsAPI-SearchUsingLatitudeandLongitude)

**Example:** Find reviews for sushi restaurants around Lat=34.10652 & Lon=-118.411509

```php
<?php
$options = array(
    'lat'    => '34.10652',
    'lon'    => '-118.411509',
    'radius' => '10',
    'what'   => 'sushi'
);

$result = $cg->reviewsLatLon($options);
```
