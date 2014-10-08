<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  com_finder
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/indexer.php';

use Joomla\Registry\Registry;

/**
 * Test class for FinderIndexer.
 * Generated by PHPUnit on 2012-06-10 at 14:41:28.
 */
class FinderIndexerTest extends TestCaseDatabase
{
	/**
	 * The mock database object
	 *
	 * @var  JDatabaseDriver
	 */
	protected $db;

	/**
	 * The factory database object
	 *
	 * @var  JDatabaseDriver
	 */
	protected $factoryDb;

	/**
	 * @var FinderIndexer
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		// Store the factory state so we can mock the necessary objects
		$this->saveFactoryState();

		JFactory::$application = $this->getMockCmsApp();
		JFactory::$session     = $this->getMockSession();

		// Set up our mock database
		$this->db = JFactory::getDbo();
		$this->db->name = 'mysqli';

		// Register the object
		$this->object = FinderIndexer::getInstance();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		// Restore the factory state
		$this->restoreFactoryState();
	}

	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return  PHPUnit_Extensions_Database_DataSet_CsvDataSet
	 *
	 * @since   3.1
	 */
	protected function getDataSet()
	{
		$dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');

		$dataSet->addTable('jos_extensions', JPATH_TEST_DATABASE . '/jos_extensions.csv');

		return $dataSet;
	}

	/**
	 * Method to override the factory database instance
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	protected function saveFactoryDatabase()
	{
		$this->factoryDb = JFactory::$database;
		JFactory::$database = $this->db;
	}

	/**
	 * Method to restore the factory database instance
	 *
	 * @return  void
	 *
	 * @since   3.1
	 */
	protected function restoreFactoryDatabase()
	{
		JFactory::$database = $this->factoryDb;
	}

	/**
	 * Tests the getInstance method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testGetInstance()
	{
		// Override the database in this method
		$this->saveFactoryDatabase();

		$this->assertThat(
			FinderIndexer::getInstance(),
			$this->isInstanceOf('FinderIndexerDriverMysql')
		);

		// Restore the database
		$this->restoreFactoryDatabase();
	}

	/**
	 * Tests the getInstance method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testGetInstanceSqlazure()
	{
		// Override the database in this method
		$this->saveFactoryDatabase();

		JFactory::$database->name = 'sqlazure';

		$this->assertThat(
			FinderIndexer::getInstance(),
			$this->isInstanceOf('FinderIndexerDriverSqlsrv')
		);

		// Restore the database
		$this->restoreFactoryDatabase();
	}

	/**
	 * Tests the getInstance method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testGetInstanceException()
	{
		// Override the database in this method
		$this->saveFactoryDatabase();

		JFactory::$database->name = 'nosql';

		$this->setExpectedException('RuntimeException');

		FinderIndexer::getInstance();

		// Restore the database
		$this->restoreFactoryDatabase();
	}

	/**
	 * Tests the setState method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testGetState()
	{
		$this->assertThat(
			FinderIndexer::getState(),
			$this->isInstanceOf('JObject')
		);
	}

	/**
	 * Tests the setState method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testSetState()
	{
		// Override the database in this method
		$this->saveFactoryDatabase();

		// Set up our test object
		$test = new JObject;
		$test->string = 'Testing FinderIndexer::setState()';

		// First, assert we can successfully set the state
		$this->assertThat(
			FinderIndexer::setState($test),
			$this->isTrue()
		);

		// Set the session data to test retrieval
		FinderIndexer::setState($test);

		// Now assert we can successfully get the state data we just stored
		$this->assertThat(
			FinderIndexer::getState(),
			$this->isInstanceOf('JObject')
		);

		// Restore the database
		$this->restoreFactoryDatabase();
	}

	/**
	 * Tests the setState method with an invalid data object
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testSetStateBadData()
	{
		// Override the database in this method
		$this->saveFactoryDatabase();

		// Set up our test object
		$test = new Registry;
		$test->set('string', 'Testing FinderIndexer::setState()');

		// Attempt to set the state
		$this->assertFalse(
			FinderIndexer::setState($test),
			'setState method is not compatible with Registry'
		);

		// Restore the database
		$this->restoreFactoryDatabase();
	}

	/**
	 * Tests the resetState method
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function testResetState()
	{
		// Override the database in this method
		$this->saveFactoryDatabase();

		// Reset the state
		FinderIndexer::resetState();

		// Test we get a null object
		$this->assertThat(
			JFactory::getSession()->get('_finder.state', null),
			$this->isNull()
		);

		// Restore the database
		$this->restoreFactoryDatabase();
	}
}
