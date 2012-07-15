<?php

namespace MagicRainbowAdventure\Tests;

use MagicRainbowAdventure\Tests\Mocks\DropboxApiMock,
	Doctrine\Tests\Mocks\EntityManagerMock;

/**
 * Magic Rainbow Adventure Base Test Case
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
abstract class FunctionalTestCase extends \PHPUnit_Extensions_Database_TestCase
{

	protected $_pdo;

	protected $_em;

	public function __construct()
	{
		parent::__construct();

		\Laravel\Session::load();
		\Laravel\IoC::instance('dropbox::api', new DropboxApiMock);

		$this->_pdo = $this->createConnection();
		$this->_em = $this->createEntityManager();

		$schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->_em);
		$cmf = $this->_em->getMetadataFactory();
		$classes = $cmf->getAllMetadata();

		$schemaTool->dropDatabase();
		$schemaTool->createSchema($classes);

		\Laravel\IoC::instance('doctrine::manager', $this->_em);
	}

	protected function createEntityManager()
	{
		$config = \Laravel\IoC::resolve('doctrine::manager')->getConfiguration();

		return \Doctrine\ORM\EntityManager::create(array('pdo' => $this->_pdo), $config);
	}

	protected function createConnection()
	{
		if ($this->_pdo === null)
		{
			$this->_pdo = new \PDO('sqlite::memory:');
		}

		return $this->_pdo;
	}

	protected function getConnection()
	{
		return $this->createDefaultDBConnection($this->createConnection(), 'sqlite');
	}

	protected function getDataSet()
	{
		return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . "/_fixtures/basic-fixtures.yml");
	}

}
