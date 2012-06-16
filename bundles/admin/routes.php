<?php

/**
 * Magic Rainbow Adventure Admin Routes
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
Route::controller(Controller::detect('admin'));

Basset::styles('admin', function($basset)
{
	$basset->directory('public/assets/css/vendor/bootstrap', function($basset)
	{
		$basset->add('button-groups-dropdowns', 'button-groups-dropdowns.css')
			->add('dropdowns', 'dropdowns.css');
	});
});

Basset::scripts('admin', function($basset)
{
	$basset->directory('public/assets/js/vendor', function($basset)
	{
		$basset->add('JSON-js', 'JSON-js/json2.js')
			->add('underscore', 'underscore/underscore-min.js')
			->add('backbone', 'backbone/backbone-min.js');
	});

	$basset->directory('public/assets/js/vendor/bootstrap', function($basset)
	{
		$basset->add('bootstrap-button', 'button.js')
			->add('bootstrap-dropdown', 'dropdown.js');
	});

	$basset->directory('admin::js', function($basset)
	{
		$basset->add('admin', 'admin.js');
	});
});
