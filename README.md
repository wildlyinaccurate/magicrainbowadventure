Magic Rainbow Adventure is a microblog powered by user-submitted content.

## Requirements

* Memcached
* PHP 5.3.1+
* PHP Extensions:
	* PDO
	* Memcached (unless you change the cache and session drivers - see below)
    * GD

## Installation

**1. Checkout from GitHub:**

    git checkout git://github.com/wildlyinaccurate/magicrainbowadventure-laravel.git
    git submodule update --init

**2. Database setup:**

Create an empty database and configure the connection settings in `application/config/database.php`. Run `php doctrine orm:schema-tool:create`.

**3. Directory permissions**

    chmod 777 storage/cache
    chmod 777 storage/logs
    chmod 777 public/entry
    chmod -R 777 application/models/Proxies

**4. Create an admin account**

To create an admin account, sign up as a regular user and change the user's `type` value in the database to `administrator`.

## Configuration

### Database

You can configure the database connection settings in `application/config/database.php`.

### Caching

By default, Magic Rainbow Adventure uses Memcached for caching. If you would like to change this, you can do so in `application/config/cache.php`

### Sessions

By default, Magic Rainbow Adventure stores session data in Memcached. If you like, you can change this in `application/config/session.php`

## Tests

To run the unit and functional tests, run `php artisan test`

## Administration

The administration dashboard can be found at yoursite.com/admin.
