<?php

namespace MagicRainbowAdventure\Logging;

/**
 * Query Counter
 *
 * Counts all Doctrine queries that were executed.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class QueryCounter implements \Doctrine\DBAL\Logging\SQLLogger
{
	/** @var int $queries Number of executed SQL queries. */
	public $queries = 0;

	/**
	 * {@inheritdoc}
	 */
	public function startQuery($sql, array $params = null, array $types = null)
	{
		$this->queries++;
	}

	/**
	 * {@inheritdoc}
	 */
	public function stopQuery()
	{
	}
}
