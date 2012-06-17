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
		$basset->add('admin', 'admin.less');
	});

	$basset->directory('public/assets/css/vendor/bootstrap', function($basset)
	{
		$basset->add('button-groups-dropdowns', 'button-groups-dropdowns.css')
			->add('dropdowns', 'dropdowns.css')
			->add('pagination', 'pagination.css')
			->add('modal', 'modal.css');
	});
});

Basset::scripts('admin', function($basset)
{
	$basset->directory('public/assets/js/vendor', function($basset)
	{
		$basset->add('knockout', 'knockout/knockout-2.1.0.js')
			->add('bootstrap-button', 'bootstrap/button.js')
			->add('bootstrap-dropdown', 'bootstrap/dropdown.js')
			->add('bootstrap-modal', 'bootstrap/modal.js');
	});

	$basset->directory('admin::js', function($basset)
	{
		$basset->add('admin', 'admin.js')
			->add('api', 'api.js');
	});
});
