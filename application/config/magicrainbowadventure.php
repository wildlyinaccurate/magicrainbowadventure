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
	| Max Upload Size
	|--------------------------------------------------------------------------
	|
	| The maximum size a file upload can be, in kilobytes (KB).
	|
	| Default: 8192 (8MB)
	|
	*/
	'max_upload_size' => '8192',

	/*
	|--------------------------------------------------------------------------
	| Dropbox Folder Path
	|--------------------------------------------------------------------------
	|
	| The base path for all files uploaded to Dropbox, relative to /Public.
	|
	*/
	'dropbox_base_path' => 'MagicRainbowAdventure',

	/*
	|--------------------------------------------------------------------------
	| Temporary File Prefix
	|--------------------------------------------------------------------------
	|
	| The prefix given to tempnam() when handling external images.
	|
	| When an entry's image is pulled in from an external URL, the application
	| will create a temporary file to store the image in before it is uploaded
	| to Dropbox.
	|
	| See http://www.php.net/manual/en/function.tempnam.php
	|
	*/
	'temp_file_prefix' => 'MRA',

);
