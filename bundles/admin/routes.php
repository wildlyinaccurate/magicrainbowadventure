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
	$basset->directory('admin::js', function($basset)
	{
		$basset->add('MagicRainbowAdventure', 'MagicRainbowAdventure.js')
			->add('BaseRepository', 'models/BaseRepository.js')
			->add('BaseModel', 'models/BaseModel.js');
	});
});
