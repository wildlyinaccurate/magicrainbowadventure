<?php

namespace MagicRainbowAdmin\Controllers;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

/**
 * Admin Base Controller
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class AdminBaseController extends \Base_Controller
{

	public function __construct()
	{
		$this->layout = 'admin::layouts/admin';

		parent::__construct();

		// Log as much of the admin actions as possible
		$this->log->pushHandler(new RotatingFileHandler(path('storage') . 'logs/' . 'magicrainbowadventure-admin.log'), Logger::INFO);

	}

}
