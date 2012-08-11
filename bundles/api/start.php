<?php

/**
 * Magic Rainbow Adventure API Bundle
 *
 * Startup Script
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */

Autoloader::namespaces(array(
	'MagicRainbowAdventure\API' => Bundle::path('api') . 'MagicRainbowAdventure/API',
	'Bgy' => Bundle::path('api') . 'vendor/Bgy',
	'Doctrine\REST' => Bundle::path('api') . 'vendor/doctrine-rest/lib/Doctrine/REST',
));
