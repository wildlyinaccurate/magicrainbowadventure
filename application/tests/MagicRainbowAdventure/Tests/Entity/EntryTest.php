<?php

namespace MagicRainbowAdventure\Tests\Entity;

use \Entity\Entry;

class EntryTest extends \MagicRainbowAdventure\Tests\BaseTestCase
{

	protected $entry;

	public function setUp()
	{
		$this->entry = new Entry;
	}

	public function testSetTitleAndSlug()
	{
		$this->entry->setTitle('Test Entry Title');
		$this->assertEquals($this->entry->getUrlTitle(), 'test-entry-title');
	}

}
