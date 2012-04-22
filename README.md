Magic Rainbow Adventure is a microblog powered by user-submitted content.

## Installation

### Requirements

* Memcached
* PHP 5.3.1+
* PHP Extensions:
	* PDO
	* cURL
	* Mcrypt
	* GD
	* Memcached
* If using a web server other than Apache, `public/.htaccess` will need to be converted

## Configuration

### Caching

If you would like to use a caching mechanism other than Memcached, you can change this in the following files:

* `application/config/cache.php`
* `application/config/session.php`
