# iHerb API

This PHP library will help you to interactive with iHerb.com.

## Installation

Add this line to your application's composer.json:

```json
{
    "require": {
        "stajor/iherb-api": "^1.0"
    }
}
```
and run `composer update`

**Or** run this command in your command line:

    $ composer require stajor/iherb-api
    
## Usage

```php
$api = new \iHerb\API();
$collection = $api->getTopSellers();
print_r($collection->toArray());

```



## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/Stajor/iherb-api. This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [Contributor Covenant](http://contributor-covenant.org) code of conduct.

## License

The gem is available as open source under the terms of the [MIT License](https://opensource.org/licenses/MIT).