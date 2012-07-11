<?php

/**
 * Dropbox API Mock Class
 *
 * Returns hard-coded responses taken from the Dropbox
 * API documentation.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
namespace MagicRainbowAdventure\Tests\Mocks;

class DropboxApiMock
{
	// API Endpoints
	const API_URL     = 'https://api.dropbox.com/1/';
	const CONTENT_URL = 'https://api-content.dropbox.com/1/';

	/**
	 * OAuth consumer object
	 * @var null|OAuth\Consumer
	 */
	private $OAuth;

	/**
	 * The root level for file paths
	 * Either `dropbox` or `sandbox` (preferred)
	 * @var null|string
	 */
	private $root;

	/**
	 * Format of the API response
	 * @var string
	 */
	private $responseFormat = 'php';

	/**
	 * JSONP callback
	 * @var string
	 */
	private $callback = 'dropboxCallback';

	/**
	* Set the root level
	* @param mixed $root
	* @throws Exception
	* @return void
	*/
	public function setRoot($root)
	{
		if($root !== 'sandbox' && $root !== 'dropbox'){
			throw new Exception("Expected a root of either 'dropbox' or 'sandbox', got '$root'");
		} else {
			$this->root = $root;
		}
	}

	/**
	 * Retrieves information about the user's account
	 * @return object stdClass
	 */
	public function accountInfo()
	{
		$response = $this->fetch('POST', self::API_URL, 'account/info');
		return $response;
	}

	/**
	 * Uploads a physical file from disk
	 * Dropbox impose a 150MB limit to files uploaded via the API. If the file
	 * exceeds this limit or does not exist, an Exception will be thrown
	 * @param string $file Absolute path to the file to be uploaded
	 * @param string|bool $filename The destination filename of the uploaded file
	 * @param string $path Path to upload the file to, relative to root
	 * @param boolean $overwrite Should the file be overwritten? (Default: true)
	 * @return object stdClass
	 */
	public function putFile($file, $filename = false, $path = '', $overwrite = true)
	{
		if(file_exists($file)){
			if(filesize($file) <= 157286400){
				return json_decode('{
					"size": "225.4KB",
					"rev": "35e97029684fe",
					"thumb_exists": false,
					"bytes": 230783,
					"modified": "Tue, 19 Jul 2011 21:55:38 +0000",
					"path": "/Getting_Started.pdf",
					"is_dir": false,
					"icon": "page_white_acrobat",
					"root": "dropbox",
					"mime_type": "application/pdf",
					"revision": 220823
				}');
			}
			throw new Exception('File exceeds 150MB upload limit');
		}

		// Throw an Exception if the file does not exist
		throw new Exception('Local file ' . $file . ' does not exist');
	}

	/**
	 * Uploads file data from a stream
	 * Note: This function is experimental and requires further testing
	 * @todo Add filesize check
	 * @param resource $stream A readable stream created using fopen()
	 * @param string $filename The destination filename, including path
	 * @param boolean $overwrite Should the file be overwritten? (Default: true)
	 * @return array
	 */
	public function putStream($stream, $filename, $overwrite = true)
	{
		return json_decode('{
			"size": "225.4KB",
			"rev": "35e97029684fe",
			"thumb_exists": false,
			"bytes": 230783,
			"modified": "Tue, 19 Jul 2011 21:55:38 +0000",
			"path": "/Getting_Started.pdf",
			"is_dir": false,
			"icon": "page_white_acrobat",
			"root": "dropbox",
			"mime_type": "application/pdf",
			"revision": 220823
		}');
	}

	/**
	 * Downloads a file
	 * Returns the base filename, raw file data and mime type returned by Fileinfo
	 * @param string $file Path to file, relative to root, including path
	 * @param string $outFile Filename to write the downloaded file to
	 * @param string $revision The revision of the file to retrieve
	 * @return array
	 */
	public function getFile($file, $outFile = false, $revision = null)
	{
		// Only allow php response format for this call
		if($this->responseFormat !== 'php'){
			throw new Exception('This method only supports the `php` response format');
		}

		$handle = null;
		if($outFile !== false){
			// Create a file handle if $outFile is specified
			if(!$handle = fopen($outFile, 'w')){
				throw new Exception("Unable to open file handle for $outFile");
			} else {
				$this->OAuth->setOutFile($handle);
			}
		}

		$file = $this->encodePath($file);

		$response = array(
			'headers' => array(
				'x-dropbox-metadata' => '{
					"size": "225.4KB",
					"rev": "35e97029684fe",
					"thumb_exists": false,
					"bytes": 230783,
					"modified": "Tue, 19 Jul 2011 21:55:38 +0000",
					"client_mtime": "Mon, 18 Jul 2011 18:04:35 +0000",
					"path": "/Getting_Started.pdf",
					"is_dir": false,
					"icon": "page_white_acrobat",
					"root": "dropbox",
					"mime_type": "application/pdf",
					"revision": 220823
				}',
			),
			'body' => 'AAABAAEAICAAAAEAIACoEAAAFgAAACgAAAAgAAAAQAAAAAEAIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD9/urh/f7q8v396v/9/ur//f7q+/3+6uv9/ur5/f7q//7/6//+/+///czY//4Ajf7/AJD//gCR//8ATP/+ATn//wA9//8AQ//+LjD//tAA///NAP/y0QT/PfMy/wD/Qv8D/jb/Af5N/wD50v8A9///AP3+/wDj//8Ahv//AHX///f24SP7/+pC+//tZvv96X77++dY+/3oN/v96E38/emU/f7q0v3/8PX8dLj+/gCL//8Akv//AIL//wE+/v8AOv//AD7//wBB//9XJP//3wD//8kA/9nWC/8D/kH/AP9A/wH/Mf8A/WP/APj9/wD4//4A////AM///wBx//8Aef//AAAAAAAAAADxACQPAAAAAAAAAAAAAAAAAAAAAAAAAAD7//8T9ebdSv0Tluv/AI3//wCX//4AZv//ATn+/wE8//8AP///AD///5YR///YAP//ywD/mOIc/wD/RP8A/z7/Af8z/wD8jv8A9///APr9/gD6//8Arv//AG/+/wB6/v8AAAAA+CeaKfobl2n8Wq8PAAAAAP///wHwbLEQ+mSyCQAAAAD2AIBZ/gCO//8Akf/+AI7//gFO//8BN/7/AD3//wBA//8XN///zAH//9MA///MAP8+8zL/AP9D/wD/Ov8B/zv/APrO/wD3//8A/v7+AO///wCI//8Adf7/AHn+/wAAAAD5FZIA/wCOAf8AhQD0I5gAAAAAAPcXllD/EZwG1UCRC/sEkNT/AI///wCW/v4Acf7/ATv+/wA8/v4BPf/+AEL//2se/v3cAP//zgD+0dgN/gL+QP4A/0L+Af83/gD+Xf4A9///APj//wD///4Az/7/AHH//gB4/v4Aef/++BaUAAAAAAAAAAAAAAAAAP8AZAAAAAAA+wyTfuJOngT4CpBz/gCP//8Ak/z+AI7+/wFR//8AOP//Aj3//wA///8KO//+sgn//NwA///LAP9r6yf/AP9E/wD/Qf8B/zD/Afuo/wD3//8A+f//AP///wCd//8Acf7/AHj//wB3/v8AAAAA/wCOAAAAAAAAAAAAAAAAAPMrmhL8CZKZ9Q+QR/wDj+z/AJD//gCY/f8AcP/+ATn//gE+//8EPP/+AEP//l8i//7W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'
		);

		// Close the file handle if one was opened
		if($handle) fclose($handle);

		return array(
			'name' => ($outFile) ? $outFile : basename($file),
			'mime' => $this->getMimeType(($outFile) ?: $response['body'], $outFile),
			'meta' => json_decode($response['headers']['x-dropbox-metadata']),
			'data' => $response['body'],
		);
	}

	/**
	 * Retrieves file and folder metadata
	 * @param string $path The path to the file/folder, relative to root
	 * @param string $rev Return metadata for a specific revision (Default: latest rev)
	 * @param int $limit Maximum number of listings to return
	 * @param string $hash Metadata hash to compare against
	 * @param bool $list Return contents field with response
	 * @param bool $deleted Include files/folders that have been deleted
	 * @return object stdClass
	 */
	public function metaData($path = null, $rev = null, $limit = 10000, $hash = false, $list = true, $deleted = false)
	{
		$call = 'metadata/' . $this->root . '/' . $this->encodePath($path);
		$params = array(
			'file_limit' => ($limit < 1) ? 1 : (($limit > 10000) ? 10000 : (int) $limit),
			'hash' => (is_string($hash)) ? $hash : 0,
			'list' => (int) $list,
			'include_deleted' => (int) $deleted,
			'rev' => (is_string($rev)) ? $rev : null,
		);

		if ($list) {
			return json_decode('{
				"size": "0 bytes",
				"hash": "37eb1ba1849d4b0fb0b28caf7ef3af52",
				"bytes": 0,
				"thumb_exists": false,
				"rev": "714f029684fe",
				"modified": "Wed, 27 Apr 2011 22:18:51 +0000",
				"path": "/Public",
				"is_dir": true,
				"icon": "folder_public",
				"root": "dropbox",
				"contents": [{
					"size": "0 bytes",
					"rev": "35c1f029684fe",
					"thumb_exists": false,
					"bytes": 0,
					"modified": "Mon, 18 Jul 2011 20:13:43 +0000",
					"client_mtime": "Wed, 20 Apr 2011 16:20:19 +0000",
					"path": "/Public/latest.txt",
					"is_dir": false,
					"icon": "page_white_text",
					"root": "dropbox",
					"mime_type": "text/plain",
					"revision": 220191
				}],
				"revision": 29007
			}');
		}

		return json_decode('{
			"size": "225.4KB",
			"rev": "35e97029684fe",
			"thumb_exists": false,
			"bytes": 230783,
			"modified": "Tue, 19 Jul 2011 21:55:38 +0000",
			"client_mtime": "Mon, 18 Jul 2011 18:04:35 +0000",
			"path": "/Getting_Started.pdf",
			"is_dir": false,
			"icon": "page_white_acrobat",
			"root": "dropbox",
			"mime_type": "application/pdf",
			"revision": 220823
		}');
	}

	/**
	 * Return "delta entries", intructing you how to update
	 * your application state to match the server's state
	 * Important: This method does not make changes to the application state
	 * @param null|string $cursor Used to keep track of your current state
	 * @return array Array of delta entries
	 */
	public function delta($cursor = null)
	{
		return array(
			'entries' => array(),
			'reset' => false,
			'cursor' => '123456',
			'has_more' => false,
		);
	}

	/**
	 * Obtains metadata for the previous revisions of a file
	 * @param string Path to the file, relative to root
	 * @param integer Number of revisions to return (1-1000)
	 * @return array
	 */
	public function revisions($file, $limit = 10)
	{
		$call = 'revisions/' . $this->root . '/' . $this->encodePath($file);
		$params = array(
			'rev_limit' => ($limit < 1) ? 1 : (($limit > 1000) ? 1000 : (int) $limit),
		);

		return json_decode('[
			{
				"is_deleted": true,
				"revision": 4,
				"rev": "40000000d",
				"thumb_exists": false,
				"bytes": 0,
				"modified": "Wed, 20 Jul 2011 22:41:09 +0000",
				"path": "/hi2",
				"is_dir": false,
				"icon": "page_white",
				"root": "app_folder",
				"mime_type": "application/octet-stream",
				"size": "0 bytes"
			},
			{
				"revision": 1,
				"rev": "10000000d",
				"thumb_exists": false,
				"bytes": 3,
				"modified": "Wed, 20 Jul 2011 22:40:43 +0000",
				"path": "/hi2",
				"is_dir": false,
				"icon": "page_white",
				"root": "app_folder",
				"mime_type": "application/octet-stream",
				"size": "3 bytes"
			}
		]');
	}

	/**
	 * Restores a file path to a previous revision
	 * @param string $file Path to the file, relative to root
	 * @param string $revision The revision of the file to restore
	 * @return object stdClass
	 */
	public function restore($file, $revision)
	{
		return json_decode('{
			"is_deleted": true,
			"revision": 4,
			"rev": "40000000d",
			"thumb_exists": false,
			"bytes": 0,
			"modified": "Wed, 20 Jul 2011 22:41:09 +0000",
			"path": "/hi2",
			"is_dir": false,
			"icon": "page_white",
			"root": "sandbox",
			"mime_type": "application/octet-stream",
			"size": "0 bytes"
		}');
	}

	/**
	 * Returns metadata for all files and folders that match the search query
	 * @param mixed $query The search string. Must be at least 3 characters long
	 * @param string $path The path to the folder you want to search in
	 * @param integer $limit Maximum number of results to return (1-1000)
	 * @param boolean $deleted Include deleted files/folders in the search
	 * @return array
	 */
	public function search($query, $path = '', $limit = 1000, $deleted = false)
	{
		return json_decode('[
			{
				"size": "0 bytes",
				"rev": "35c1f029684fe",
				"thumb_exists": false,
				"bytes": 0,
				"modified": "Mon, 18 Jul 2011 20:13:43 +0000",
				"path": "/Public/latest.txt",
				"is_dir": false,
				"icon": "page_white_text",
				"root": "dropbox",
				"mime_type": "text/plain",
				"revision": 220191
			}
		]');
	}

	/**
	 * Creates and returns a shareable link to files or folders
	 * The link returned is for a preview page from which the user an choose to
	 * download the file if they wish. For direct download links, see media().
	 * @param string $path The path to the file/folder you want a sharable link to
	 * @return object stdClass
	 */
	public function shares($path)
	{
		return json_decode('{
			"url": "http://db.tt/APqhX1",
			"expires": "Tue, 01 Jan 2030 00:00:00 +0000"
		}');
	}

	/**
	 * Returns a link directly to a file
	 * @param string $path The path to the media file you want a direct link to
	 * @return object stdClass
	 */
	public function media($path)
	{
		return json_decode('{
			"url": "http://www.dropbox.com/s/m/a2mbDa2",
			"expires": "Tue, 01 Jan 2030 00:00:00 +0000"
		}');
	}

	/**
	 * Gets a thumbnail for an image
	 * @param string $file The path to the image you wish to thumbnail
	 * @param string $format The thumbnail format, either JPEG or PNG
	 * @param string $size The size of the thumbnail
	 * @return array
	 */
	public function thumbnails($file, $format = 'JPEG', $size = 'small')
	{
		// Only allow php response format for this call
		if($this->responseFormat !== 'php'){
			throw new Exception('This method only supports the `php` response format');
		}

		$format = strtoupper($format);
		// If $format is not 'PNG', default to 'JPEG'
		if($format != 'PNG') $format = 'JPEG';

		$size = strtolower($size);
		$sizes = array('s', 'm', 'l', 'xl', 'small', 'medium', 'large');
		// If $size is not valid, default to 'small'

		$response = array(
			'headers' => array(
				'x-dropbox-metadata' => '{
					"size": "225.4KB",
					"rev": "35e97029684fe",
					"thumb_exists": false,
					"bytes": 230783,
					"modified": "Tue, 19 Jul 2011 21:55:38 +0000",
					"client_mtime": "Mon, 18 Jul 2011 18:04:35 +0000",
					"path": "/Getting_Started.pdf",
					"is_dir": false,
					"icon": "page_white_acrobat",
					"root": "dropbox",
					"mime_type": "application/pdf",
					"revision": 220823
				}',
			),
			'body' => 'AAABAAEAICAAAAEAIACoEAAAFgAAACgAAAAgAAAAQAAAAAEAIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD9/urh/f7q8v396v/9/ur//f7q+/3+6uv9/ur5/f7q//7/6//+/+///czY//4Ajf7/AJD//gCR//8ATP/+ATn//wA9//8AQ//+LjD//tAA///NAP/y0QT/PfMy/wD/Qv8D/jb/Af5N/wD50v8A9///AP3+/wDj//8Ahv//AHX///f24SP7/+pC+//tZvv96X77++dY+/3oN/v96E38/emU/f7q0v3/8PX8dLj+/gCL//8Akv//AIL//wE+/v8AOv//AD7//wBB//9XJP//3wD//8kA/9nWC/8D/kH/AP9A/wH/Mf8A/WP/APj9/wD4//4A////AM///wBx//8Aef//AAAAAAAAAADxACQPAAAAAAAAAAAAAAAAAAAAAAAAAAD7//8T9ebdSv0Tluv/AI3//wCX//4AZv//ATn+/wE8//8AP///AD///5YR///YAP//ywD/mOIc/wD/RP8A/z7/Af8z/wD8jv8A9///APr9/gD6//8Arv//AG/+/wB6/v8AAAAA+CeaKfobl2n8Wq8PAAAAAP///wHwbLEQ+mSyCQAAAAD2AIBZ/gCO//8Akf/+AI7//gFO//8BN/7/AD3//wBA//8XN///zAH//9MA///MAP8+8zL/AP9D/wD/Ov8B/zv/APrO/wD3//8A/v7+AO///wCI//8Adf7/AHn+/wAAAAD5FZIA/wCOAf8AhQD0I5gAAAAAAPcXllD/EZwG1UCRC/sEkNT/AI///wCW/v4Acf7/ATv+/wA8/v4BPf/+AEL//2se/v3cAP//zgD+0dgN/gL+QP4A/0L+Af83/gD+Xf4A9///APj//wD///4Az/7/AHH//gB4/v4Aef/++BaUAAAAAAAAAAAAAAAAAP8AZAAAAAAA+wyTfuJOngT4CpBz/gCP//8Ak/z+AI7+/wFR//8AOP//Aj3//wA///8KO//+sgn//NwA///LAP9r6yf/AP9E/wD/Qf8B/zD/Afuo/wD3//8A+f//AP///wCd//8Acf7/AHj//wB3/v8AAAAA/wCOAAAAAAAAAAAAAAAAAPMrmhL8CZKZ9Q+QR/wDj+z/AJD//gCY/f8AcP/+ATn//gE+//8EPP/+AEP//l8i//7W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'
		);

		return array(
			'name' => basename($file),
			'mime' => $this->getMimeType($response['body']),
			'meta' => json_decode($response['headers']['x-dropbox-metadata']),
			'data' => $response['body'],
		);
	}

	/**
	 * Creates and returns a copy_ref to a file
	 * This reference string can be used to copy that file to another user's
	 * Dropbox by passing it in as the from_copy_ref parameter on /fileops/copy
	 * @param $path File for which ref should be created, relative to root
	 * @return array
	 */
	public function copyRef($path)
	{
		$call = 'copy_ref/' . $this->root . '/' . $this->encodePath($path);
		$response = $this->fetch('GET', self::API_URL, $call);
		return $response;
	}

	/**
	 * Copies a file or folder to a new location
	 * @param string $from File or folder to be copied, relative to root
	 * @param string $to Destination path, relative to root
	 * @param null|string $fromCopyRef Must be used instead of the from_path
	 * @return object stdClass
	 */
	public function copy($from, $to, $fromCopyRef = null)
	{
		$call = 'fileops/copy';
		$params = array(
			'root' => $this->root,
			'from_path' => $this->normalisePath($from),
			'to_path' => $this->normalisePath($to),
		);

		if($fromCopyRef){
			$params['from_path'] = null;
			$params['from_copy_ref'] = $fromCopyRef;
		}

		$response = $this->fetch('POST', self::API_URL, $call, $params);
		return $response;
	}

	/**
	 * Creates a folder
	 * @param string New folder to create relative to root
	 * @return object stdClass
	 */
	public function create($path)
	{
		$call = 'fileops/create_folder';
		$params = array('root' => $this->root, 'path' => $this->normalisePath($path));
		$response = $this->fetch('POST', self::API_URL, $call, $params);
		return $response;
	}

	/**
	 * Deletes a file or folder
	 * @param string $path The path to the file or folder to be deleted
	 * @return object stdClass
	 */
	public function delete($path)
	{
		$call = 'fileops/delete';
		$params = array('root' => $this->root, 'path' => $this->normalisePath($path));
		$response = $this->fetch('POST', self::API_URL, $call, $params);
		return $response;
	}

	/**
	 * Moves a file or folder to a new location
	 * @param string $from File or folder to be moved, relative to root
	 * @param string $to Destination path, relative to root
	 * @return object stdClass
	 */
	public function move($from, $to)
	{
		$call = 'fileops/move';
		$params = array(
				'root' => $this->root,
				'from_path' => $this->normalisePath($from),
				'to_path' => $this->normalisePath($to),
		);
		$response = $this->fetch('POST', self::API_URL, $call, $params);
		return $response;
	}

	/**
	 * Intermediate fetch function
	 * @param string $method The HTTP method
	 * @param string $url The API endpoint
	 * @param string $call The API method to call
	 * @param array $params Additional parameters
	 * @return mixed
	 */
	private function fetch($method, $url, $call, array $params = array())
	{
		// Make the API call via the consumer
		$response = $this->OAuth->fetch($method, $url, $call, $params);

		// Format the response and return
		switch($this->responseFormat){
			case 'json':
				return json_encode($response);
			case 'jsonp':
				$response = json_encode($response);
				return $this->callback . '(' . $response . ')';
			default:
				return $response;
		}
	}

	/**
	 * Set the API response format
	 * @param string $format One of php, json or jsonp
	 * @return void
	 */
	public function setResponseFormat($format)
	{
		$format = strtolower($format);
		if(!in_array($format, array('php', 'json', 'jsonp'))){
			throw new Exception("Expected a format of php, json or jsonp, got '$format'");
		} else {
			$this->responseFormat = $format;
		}
	}

	/**
	* Set the JSONP callback function
	* @param string $function
	* @return void
	*/
	public function setCallback($function)
	{
		$this->callback = $function;
	}

	/**
	 * Get the mime type of downloaded file
	 * If the Fileinfo extension is not loaded, return false
	 * @param string $data File contents as a string or filename
	 * @param string $isFilename Is $data a filename?
	 * @return boolean|string Mime type and encoding of the file
	 */
	private function getMimeType($data, $isFilename = false)
	{
		if(extension_loaded('fileinfo')){
			$finfo = new \finfo(FILEINFO_MIME);
			if($isFilename !== false) return $finfo->file($data);
			return $finfo->buffer($data);
		}
		return false;
	}

	/**
	 * Trim the path of forward slashes and replace
	 * consecutive forward slashes with a single slash
	 * @param string $path The path to normalise
	 * @return string
	 */
	private function normalisePath($path)
	{
		$path = preg_replace('#/+#', '/', trim($path, '/'));
		return $path;
	}

	/**
	 * Encode the path, then replace encoded slashes
	 * with literal forward slash characters
	 * @param string $path The path to encode
	 * @return string
	 */
	private function encodePath($path)
	{
		$path = $this->normalisePath($path);
		$path = str_replace('%2F', '/', rawurlencode($path));
		return $path;
	}
}
