Magic Rainbow Adventure is a microblog powered by user-submitted content.

## Requirements

* Memcached
* PHP 5.3.1+
* PHP Extensions:
	* PDO
	* cURL
	* Mcrypt
	* Memcached

## Installation

**1. Checkout from GitHub:**

    git checkout git://github.com/wildlyinaccurate/magicrainbowadventure-laravel.git
    git submodule update --init

**2. Database setup:**

Create an empty database and configure the connection settings in `application/config/database.php`. Run `php doctrine orm:schema-tool:create`.

**3. Directory permissions**

    chmod 777 storage/cache
    chmod 777 storage/logs
    chmod 777 public/uploads/MagicRainbowAdventure
    chmod -R 777 application/models/Proxies

## Configuration

### Database

You can configure the database connection settings in `application/config/database.php`.

### Caching

If you would like to use a caching mechanism other than Memcached, you can change this in the following files:

* `application/config/cache.php`
* `application/config/session.php`
