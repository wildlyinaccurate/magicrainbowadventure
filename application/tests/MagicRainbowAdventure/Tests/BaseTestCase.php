<?php

namespace MagicRainbowAdventure\Tests;

use MagicRainbowAdventure\Tests\Mocks\DropboxApiMock,
	Doctrine\Tests\Mocks\EntityManagerMock;

/**
 * Magic Rainbow Adventure Base Test Case
 *
 * Since most of MRA relies heavily on Doctrine, the base test extends from
 * Doctrine's OrmTestCase class.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
abstract class BaseTestCase extends \DoctrineExtensions\PHPUnit\OrmTestCase
{

	public function __construct()
	{
		parent::__construct();

		\Laravel\Session::load();
		\Laravel\IoC::instance('dropbox::api', new DropboxApiMock);
		\Laravel\IoC::instance('doctrine::manager', $this->getEntityManager());
	}

	protected function createEntityManager()
	{
		$eventManager = new \Doctrine\Common\EventManager();
		$eventManager->addEventListener(array('preTestSetUp'), new SchemaSetupListener());

		$config = \Laravel\IoC::resolve('doctrine::manager')->getConfiguration();
		$connection = new \PDO('sqlite::memory:');

		return \Doctrine\ORM\EntityManager::create(array('pdo' => $connection), $config, $eventManager);
	}

	public function getDataSet()
	{
		return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . "/_fixtures/basic-fixtures.yml");
	}

}
