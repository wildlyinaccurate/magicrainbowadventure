<?php

/**
 * Easy cURL class
 *
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class Easy_curl {

	/**
	 * The cURL handler
	 * @var resource
	 */
	private $curl;

	/**
	 * Request timeout in seconds
	 * @var int
	 */
	private $timeout = 15;

	/**
	 * Constructor
	 *
	 * Make sure cURL is loaded
	 */
	public function __construct()
	{
		if ( ! in_array('curl', get_loaded_extensions()))
		{
			throw new Exception('The cURL extension is not loaded.');
		}
	}

	/**
	 * Make a HEAD request. Returns FALSE on failure
	 *
	 * @param	string	$uri
	 * @return	Easy_curl
	 */
	public function get_header($uri)
	{
		$this->_init();

		curl_setopt_array($this->curl, array(
			 CURLOPT_URL => $uri,
			 CURLOPT_RETURNTRANSFER => TRUE,
			 CURLOPT_HEADER => TRUE,
			 CURLOPT_TIMEOUT => $this->timeout,
			 CURLOPT_NOBODY => TRUE
		 ));

		if ( ! curl_exec($this->curl))
		{
			return FALSE;
		}

		return $this;
	}

	/**
	 * Return the content-type of a cURL resource
	 *
	 * @param	string	$uri
	 * @return	string
	 */
	public function get_content_type($uri = NULL)
	{
		if ($uri)
		{
			$this->get_header($uri);
		}

		return curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE);
	}

	/**
	 * Return the content-length of a cURL handle.
	 *
	 * Note that this is NOT an accurate way of getting the size of a resource!
	 *
	 * @return void
	 */
	public function get_content_length()
	{
		return curl_getinfo($this->curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
	}

	/**
	 * Store the contents of a URL in a file
	 *
	 * @param	string	$url
	 * @param	string	$file
	 * @return	bool
	 */
	public function url_to_file($url, $file)
	{
		$this->_init();

		// Create a file handle
		$file_handle = fopen($file, 'wb');

		curl_setopt_array($this->curl, array(
			 CURLOPT_URL => $url,
			 CURLOPT_HEADER => FALSE,
			 CURLOPT_TIMEOUT => $this->timeout,
			 CURLOPT_FILE => $file_handle
		 ));


		if ( ! curl_exec($this->curl))
		{
			return FALSE;
		}

		curl_close($this->curl);
		fclose($file_handle);
		return TRUE;
	}

	/**
	 * Return the error from the last cURL request
	 *
	 * @return string
	 */
	public function get_error()
	{
		return curl_error($this->curl);
	}

	/**
	 * Initialise cURL
	 *
	 * @return void
	 */
	private function _init()
	{
		$this->curl = curl_init();
	}

	/**
	 * Destructor
	 *
	 * Close the cURL handle
	 */
	public function __destruct()
	{
		curl_close($this->curl);
	}

}