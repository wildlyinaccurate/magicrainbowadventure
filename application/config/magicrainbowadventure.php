<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Entries Per Page
	|--------------------------------------------------------------------------
	|
	| How many entries to display per page
	|
	*/
	'entries_page_page' => 8,

	/*
	|--------------------------------------------------------------------------
	| File Uploads
	|--------------------------------------------------------------------------
	|
	| max_upload_size
	| The maximum size a file upload can be, in kilobytes (KB).
	|
	| entry_uploads_path
	| The base path for entry uploads.
	|
	| entry_uploads_url
	| The URL for accessing entry images. Can be relative.
	|
	| temp_file_prefix
	| The prefix given to tempnam() when handling external images.
	| See http://www.php.net/manual/en/function.tempnam.php
	|
	| entry_thumbnails
	| An array of thumbnails to generate for each entry, with the format:
	|	name => max_width
	|
	*/
	'max_upload_size' => '9216',
	'entry_uploads_path' => path('public') . 'entry',
	'entry_uploads_url' => '/entry',
	'temp_file_prefix' => 'MRA',
	'entry_thumbnails' => array(
		'thumbnail' => array(
			'width' => 62,
			'height' => 62,
			'crop' => true,
			'quality' => 70,
		),
		'preview' => array(
			'width' => 280,
			'height' => 400,
			'quality' => 70,
		),
		'medium' => array(
			'width' => 640,
			'height' => 2560,
		),
		'large' => array(
			'width' => 1010,
			'height' => 8080,
			'quality' => 92,
		),
	),

);
