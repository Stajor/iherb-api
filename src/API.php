<?php namespace iHerb;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class API {
    const API_URL = 'https://www.iherb.com';

    protected $http;

    public function __construct($options = []) {
        $settings = array_merge([
            'country'   => 'US',
            'currency'  => 'USD',
            'language'  => 'en-US',
            'limit'     => 24
        ], $options);

        $cookie     = ["sccode={$settings['country']}"];
        $cookie[]   = "lan={$settings['language']}";
        $cookie[]   = "scurcode={$settings['currency']}";
        $cookie[]   = "noitmes={$settings['limit']}";

        $this->http = new Client([
            'base_uri'  => self::API_URL,
            'cookies'   => CookieJar::fromArray(['iher-pref1' => implode('&', $cookie)], '.iherb.com')
        ]);
    }

    public function getByCategory(string $category, $params = []): Collection {
        $xpath      = $this->request("/c/{$category}", $params);
        $selector   = '//div[starts-with(@class, "products ")]//div[starts-with(@class, "product ")]';
        $collection = new Collection();

        /** @var \DOMElement $node */
        foreach ($xpath->query($selector) AS $node) {
            $button = $node->getElementsByTagName('button')->item(0);
            $link   = $node->getElementsByTagName('a')->item(1);

            if (is_null($button)) {
                continue;
            }

            $info = json_decode($button->getAttribute('data-cart-info'), true)['lineItems'][0];
            $collection->push([
                'id'                => $info['productId'],
                'name'              => $info['productName'],
                'url'               => $link->getAttribute('href'),
                'image_url'         => $info['iURLMedium'],
                'list_price'        => $info['listPrice'],
                'discount_price'    => $info['discountPrice']
            ]);
        }

        return $collection;
    }

    public function getTopSellers() {
        $xpath      = $this->request('/Catalog/TopSellers/');
        $collection = new Collection();

        /** @var \DOMElement $node */
        foreach ($xpath->query('//div[starts-with(@class, "best-sellers-row ")]') AS $node) {
            $href   = $node->getElementsByTagName('a')->item(0)->getAttribute('href');
            $id     = explode('/', $href);

            $collection->push([
                'id'            => end($id),
                'name'          => $node->getElementsByTagName('span')->item(1)->textContent,
                'url'           => $href,
                'image_url'     => $node->getElementsByTagName('img')->item(0)->getAttribute('src'),
                'list_price'    => trim($node->getElementsByTagName('span')->item(3)->textContent)
            ]);
        }

        return $collection;
    }

    private function request(string $method, array $params = []): \DOMXPath {
        libxml_use_internal_errors(true);

        $response   = $this->http->get($method, ['query' => $params]);
        $doc        = new \DOMDocument();

        $doc->loadHTML($response->getBody());

        return new \DOMXPath($doc);
    }
}