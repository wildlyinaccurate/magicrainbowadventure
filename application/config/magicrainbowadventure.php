<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Entries Per Page
	|--------------------------------------------------------------------------
	|
	| How many entries to display per page
	|
	| Default: 10
	|
	*/
	'entries_page_page' => 10,

	/*
	|--------------------------------------------------------------------------
	| File Uploads
	|--------------------------------------------------------------------------
	|
	| max_upload_size
	| The maximum size a file upload can be, in kilobytes (KB). Default: 8192 (8MB)
	|
	| thumbnail_cache_path
	| The base path for cached thumbnails (stored locally).
	|
	| dropbox_base_path
	| The base path for all files uploaded to Dropbox, relative to /Public.
	|
	| temp_file_prefix
	| The prefix given to tempnam() when handling external images.
	| See http://www.php.net/manual/en/function.tempnam.php
	|
	*/
	'max_upload_size' => '8192',
	'thumbnail_cache_path' => path('public') . 'assets',
	'dropbox_base_path' => 'MagicRainbowAdventure',
	'temp_file_prefix' => 'MRA',

);
