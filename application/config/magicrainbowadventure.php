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
	| entry_thumbnails
	| An array of thumbnails to generate for each entry, with the format:
	|	name => max_width
	|
	*/
	'max_upload_size' => '9216',
	'entry_uploads_path' => path('public') . 'entry',
	'entry_uploads_url' => '/entry',
	'entry_thumbnails' => array(
		'thumbnail' => array(
			'width' => 62,
			'height' => 62,
			'resize' => 'crop',
			'quality' => 70,
			'gif' => true,
		),
		'preview' => array(
			'width' => 280,
			'height' => 400,
			'resize' => 'crop',
			'quality' => 70,
			'gif' => true,
		),
		'medium' => array(
			'width' => 640,
			'height' => 1920,
		),
		'large' => array(
			'width' => 1010,
			'height' => 3030 ,
			'quality' => 92,
		),
	),

);
