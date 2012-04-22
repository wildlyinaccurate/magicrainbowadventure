<?php

/**
 * Easy cURL class
 *
 * @author	Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class EasyCurl
{

	/**
	 * The cURL result
	 * @var mixed
	 */
	public $result;

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
 	 * @param	string	$uri
 	 * @return	void
	 */
	public function __construct($uri = null)
	{
		if ( ! in_array('curl', get_loaded_extensions()))
		{
			throw new Exception('The cURL extension is not loaded.');
		}

		$this->curl = curl_init($uri);
	}

	/**
	 * Initialise a new cURL handle
	 *
 	 * @param	string	$uri
 	 * @return	Easy_curl
	 */
	public static function init($uri)
	{
		return new self($uri);
	}

	/**
	 * Make a HEAD request. Returns false on failure
	 *
	 * @return	Easy_curl
	 */
	public function get_header()
	{
		curl_setopt_array($this->curl, array(
			 CURLOPT_RETURNTRANSFER => true,
			 CURLOPT_HEADER => true,
			 CURLOPT_TIMEOUT => $this->timeout,
			 CURLOPT_NOBODY => true
		 ));

		if ( ! curl_exec($this->curl))
		{
			return false;
		}

		return $this;
	}

	/**
	 * Return the content-type of a cURL resource
	 *
	 * @return	string
	 */
	public function get_content_type()
	{
		if ( ! $this->result)
		{
			$this->get_header();
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
		if ( ! $this->result)
		{
			$this->get_header();
		}

		return curl_getinfo($this->curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
	}

	/**
	 * Store the contents of the current URL in a file
	 *
	 * @param	string	$file_path
	 * @return	bool
	 */
	public function to_file($file_path)
	{
		// Create a file handle
		$file_handle = fopen($file_path, 'wb');

		curl_setopt_array($this->curl, array(
			 CURLOPT_HEADER => false,
			 CURLOPT_TIMEOUT => $this->timeout,
			 CURLOPT_FILE => $file_handle
		 ));

		if ( ! curl_exec($this->curl))
		{
			return false;
		}

		fclose($file_handle);

		return true;
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
	 * Destructor
	 *
	 * Close the cURL handle
	 */
	public function __destruct()
	{
		curl_close($this->curl);
	}

}
