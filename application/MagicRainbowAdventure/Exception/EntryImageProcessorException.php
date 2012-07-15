<?php

namespace MagicRainbowAdventure\Exception;

class EntryImageProcessorException extends BaseException
{

	private $_entry;

	const DUPLICATE_ENTRY = 1;

	public function __construct($message, $code = 0, $entry = null)
	{
		$this->_entry = $entry;

		parent::__construct($message, $code);
	}

	public function getEntry()
	{
		return $this->_entry;
	}

}
