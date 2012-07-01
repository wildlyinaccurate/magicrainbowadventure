<?php

namespace MagicRainbowAdventure\Tests;

use MagicRainbowAdventure\Tests\Mocks\DropboxApiMock,
	MagicRainbowAdventure\Tests\Mocks\AuthDriverMock,
	Doctrine\Tests\Mocks\EntityManagerMock;

/**
 * Magic Rainbow Adventure Base Test Case
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase {

	public function __construct()
	{
		parent::__construct();

		// \Laravel\Auth::extend('mock', function() {
		// 	return new AuthDriverMock;
		// });

		// \Laravel\Config::set('auth.driver', 'mock');

		\Laravel\Session::load();
		\Laravel\IoC::instance('dropbox::api', new DropboxApiMock);
		\Laravel\IoC::instance('doctrine::manager', $this->_getEntityManager());
	}

	protected function _getEntityManager()
	{
		$config = \Laravel\IoC::resolve('doctrine::manager')->getConfiguration();

		$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
		$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());
		$config->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
		$config->setProxyDir(__DIR__ . '/Entity/Proxies');
		$config->setProxyNamespace('MagicRainbowAdventure\Tests\Entity\Proxies');

		$conn = \Doctrine\DBAL\DriverManager::getConnection(array(
			'driverClass'  => 'Doctrine\Tests\Mocks\DriverMock',
			'wrapperClass' => 'Doctrine\Tests\Mocks\ConnectionMock',
			'user'         => 'john',
			'password'     => 'wayne'
		), $config);

		return EntityManagerMock::create($conn, $config);
	}

}
