<?php

namespace MagicRainbowAdventure\Tools;

/**
 * Entry Thumbnail Tool
 *
 * Generates and retrieves entry thumbnails
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class EntryThumbnailTool
{

	/**
	 * @var	string
	 */
	public $base_path;

	/**
	 * @var	\Entity\Entry
	 */
	public $entry;

	/**
	 * Path to the original (full-size) image
	 * @var	string
	 */
	public $original_image_path;

	/**
	 * Resizer instance
	 * @var	\Resizer
	 */
	private $resizer;

	/**
	 * Default thumbnail quality
	 * @var	int
	 */
	private $default_quality = 85;

	/**
	 * Constructor ahoy!
	 *
	 * @param	\Entity\Entry	$entry
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function __construct(\Entity\Entry $entry)
	{
		$this->entry = $entry;
		$this->base_path = \Config::get('magicrainbowadventure.entry_uploads_path');
		$this->original_image_path = $this->base_path . '/' . $entry->getFilePath();
		$this->resizer = new \Resizer($this->original_image_path);
	}

	/**
	 * Generate thumbnails from a configuration array
	 *
	 * Array should be structured like:
	 * 	array(
	 * 		thumbnail_name => array(
	 * 			'width' => 100,
	 * 			'height' => 100,
	 * 			'crop' => true, // Optional, default = false
	 * 			'quality' => 80, // Optional, default = 90
	 * 			'gif' => false, // Optional. Unless this is true, thumbnails aren't generated for animated gifs
	 *   	)
	 * 	)
	 *
	 * @param	array	$thumbnails
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function generateFromArray($thumbnails)
	{
		foreach ($thumbnails as $name => $thumbnail)
		{
			$crop = (isset($thumbnail['crop']) && $thumbnail['crop'] === true);
			$quality = (isset($thumbnail['quality'])) ? $thumbnail['quality'] : $this->default_quality;

			$this->generate($name, $thumbnail['width'], $thumbnail['height'], $crop, $quality);
		}
	}

	/**
	 * Generate a thumbnail
	 *
	 * $options is an array that can contain:
	 * 	crop (bool):	Whether to crop the image to the exact dimensions specified
	 * 	quality (int):	The quality of the saved thumbnail
	 * 	gif (bool):		Whether or not
	 *
	 * @param	string	$name
	 * @param	int		$width
	 * @param	int		$height
	 * @param	array	$options
	 * @return	void
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function generate($name, $width, $height, $options = array())
	{
		$quality = array_get($options, 'quality', $this->default_quality);
		$crop = array_get($options, 'crop', false);
		$gif = array_get($options, 'gif', false);

		if ($this->entry->getType() === 'gif' && ! $gif)
		{
			// Don't generate thumbnails for animated gifs unless explicitly told so
			return;
		}

		$resize_option = ($crop) ? 'crop' : 'landscape';
		$thumbnail_path = $this->getThumbnailPath($name);
		$thumbnail_dir = dirname($thumbnail_path);

		// Ensure the path exists
		if ( ! is_dir($thumbnail_dir))
		{
			mkdir($thumbnail_dir, 0777, true);
		}

		$this->resizer->resize($width, $height, $resize_option)
			->save($thumbnail_path, $quality);
	}

	/**
	 * Retrieve the full path to a thumbnail
	 *
	 * @param	string	$name
	 * @return	string
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function getThumbnailPath($name)
	{
		return dirname($this->original_image_path) . "/{$name}/" . basename($this->original_image_path);
	}

	/**
	 * Retrieve the public URL for a thumbnail. If the thumbnail doesn't exist,
	 * or no thumbnail name is provided, the URL for the full-size image
	 * will be returned.
	 *
	 * @param	string	$name
	 * @return	string
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function getThumbnailUrl($name = null)
	{
		$base_url = \Config::get('magicrainbowadventure.entry_uploads_url');

		if ( ! $name || ! file_exists($this->getThumbnailPath($name)))
		{
			return $base_url . '/' . dirname($this->entry->getFilePath()) . '/' . basename($this->original_image_path);
		}

		return $base_url . '/' . dirname($this->entry->getFilePath()) . "/{$name}/" . basename($this->original_image_path);
	}

}
