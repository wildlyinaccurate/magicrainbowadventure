<?php

namespace MagicRainbowAdventure\Processors;

use MagicRainbowAdventure\Exception\EntryImageProcessorException,
	MagicRainbowAdventure\Helpers\ImageHelper,
	MagicRainbowAdventure\Tools\Curl;

/**
 * Uploaded Entry Processor
 *
 * Store an uploaded entry image and generate thumbnails.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class EntryImageProcessor
{

	/**
	 * Base path in which to save processed images
	 * @var	string
	 */
	public $base_path;

	/**
	 * File path of the processed image, relative to $base_path
	 * @var	string
	 */
	private $file_path;

	/**
	 * Hash of the processed file
	 * @var	string
	 */
	private $file_hash;

	/**
	 * Dimensions of the processed file
	 * @var	array
	 */
	private $image_dimensions;

	/**
	 * Constructor ahoy!
	 *
	 * @param	string	$base_path
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function __construct($base_path)
	{
		if ( ! is_writable($base_path))
		{
			throw new EntryImageProcessorException("Unable to open base path for writing: {$base_path}");
		}

		$this->base_path = $base_path;
	}

	/**
	 * Return the file path of the processed image
	 *
	 * @return	string
	 * @throws	ImageNotProcessedException
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function getFilePath()
	{
		if ($this->file_path === null)
		{
			throw new EntryImageProcessorException('No file has been processed.');
		}

		return $this->file_path;
	}

	/**
	 * Return the file path of the processed image
	 *
	 * @return	string
	 * @throws	ImageNotProcessedException
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function getFileHash()
	{
		if ($this->file_hash === null)
		{
			throw new EntryImageProcessorException('No file has been processed.');
		}

		return $this->file_hash;
	}

	/**
	 * Return the dimensions of the processed image
	 *
	 * @return	array
	 * @throws	ImageNotProcessedException
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function getImageDimensions()
	{
		if ($this->file_hash === null)
		{
			throw new EntryImageProcessorException('No file has been processed.');
		}

		if ($this->image_dimensions === null)
		{
			$image_size = getimagesize($this->base_path . '/' . $this->file_path);

			$this->image_dimensions = array(
				'width' => $image_size[0],
				'height' => $image_size[1],
			);
		}

		return $this->image_dimensions;
	}

	/**
	 * Process an entry image from an existing file
	 *
	 * @param	string	$original_file_path
	 * @return	void
	 * @throws	EntryImageProcessorException
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function fromFile($original_file_path)
	{
		if ( ! is_readable($original_file_path))
		{
			throw new EntryImageProcessorException("Unable to open file: {$original_file_path}");
		}

		// Determine the extension of the file so that we can save it correctly
		$finfo = new \Finfo(FILEINFO_MIME_TYPE);
		$extension = ImageHelper::getExtensionFromMime($finfo->file($original_file_path));

		// Name the file based on a hash of the contents
		$this->file_hash = hash_file('sha1', $original_file_path);
		$this->file_path = $this->generateFilePath("{$this->file_hash}.{$extension}");
		$this->checkDuplicate();

		// Move the file to it's new destination
		rename($original_file_path, $this->base_path . '/' . $this->file_path);
	}

	/**
	 * Process an entry image from a remote URL
	 *
	 * @param	string	$url
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function fromUrl($url)
	{
		$curl = new Curl($url);
		$image_contents = $curl->execute(true);

		// Determine the extension of the file so that we can save it correctly
		$extension = ImageHelper::getExtensionFromMime($curl->get_content_type());

		// Name the file based on a hash of the contents
		$this->file_hash = hash('sha1', $image_contents);
		$this->file_path = $this->generateFilePath("{$this->file_hash}.{$extension}");
		$this->checkDuplicate();

		// Save the file
		$handle = fopen($this->base_path . '/' . $this->file_path, 'wb');
		fwrite($handle, $image_contents);
		fclose($handle);
	}

	/**
	 * Generate the file path for a file, ensuring all sub-directories exist
	 *
	 * @param	string	$file_name
	 * @return	string
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	private function generateFilePath($file_name)
	{
		$file_path = date('Y/m');
		$full_path = $this->base_path . '/' . $file_path;

		// Ensure the directory exists
		if ( ! is_dir($full_path))
		{
			mkdir($full_path, 0777, true);
		}

		return "{$file_path}/{$file_name}";
	}

	/**
	 * Check the database for an entry with the same file hash
	 *
	 * @throws	EntryImageProcessorException
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	private function checkDuplicate()
	{
		$em = \Laravel\IoC::resolve('doctrine::manager');
		$duplicate = $em->getRepository('Entity\Entry')->findOneBy(array(
			'hash' => $this->file_hash,
			'approved' => true,
		));

		if ($duplicate !== null)
		{
			throw new EntryImageProcessorException('Duplicate entry found.', EntryImageProcessorException::DUPLICATE_ENTRY, $duplicate);
		}
	}

}
