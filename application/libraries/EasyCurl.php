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
	 * Make a GET request. Returns false on failure.
	 *
	 * @param  bool     $binary
	 * @param  string	$file_path
	 * @return Easy_curl|bool
	 */
	public function execute($binary = false, $file_path = null)
	{
		curl_setopt_array($this->curl, array(
			 CURLOPT_RETURNTRANSFER => true,
			 CURLOPT_HEADER => false,
			 CURLOPT_BINARYTRANSFER => $binary,
			 CURLOPT_TIMEOUT => $this->timeout,
		 ));

		$handle = false;

		if ($file_path !== null)
		{
			$handle = fopen($file_path, 'wb');
			curl_setopt($this->curl, CURLOPT_FILE, $handle);
		}

		$this->result = curl_exec($this->curl);

		if ($handle !== false)
		{
			fclose($handle);
		}

		if ( ! $this->result)
		{
			return false;
		}

		return $this;
	}

	/**
	 * Make a HEAD request. Returns false on failure
	 *
	 * @return	Easy_curl|bool
	 */
	public function get_header()
	{
		curl_setopt_array($this->curl, array(
			 CURLOPT_RETURNTRANSFER => true,
			 CURLOPT_HEADER => true,
			 CURLOPT_TIMEOUT => $this->timeout,
			 CURLOPT_NOBODY => true
		 ));

		$this->result = curl_exec($this->curl);

		if ( ! $this->result)
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
