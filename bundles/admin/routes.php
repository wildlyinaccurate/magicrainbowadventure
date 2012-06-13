<?php

/**
 * Magic Rainbow Adventure Admin Routes
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
Route::controller(Controller::detect('admin'));

Basset::styles('admin', function($basset)
{
	$basset->directory('admin::css', function($basset)
	{
		$basset->add('bootstrap-modal', 'bootstrap-modal.css');
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
});
