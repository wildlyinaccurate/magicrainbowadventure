<?php

namespace MagicRainbowAdventure\Validation;

/**
 * Entry Validator Class
 *
 * Provides validation methods relevant to creating and editing entries.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class EntryValidator extends \Laravel\Validator
{

	/**
	 * Make sure a URL is pointing to a valid image
	 *
	 * @param	string	$attribute
	 * @param	string	$value
	 * @param	array	$parameters
	 * @return	bool
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public function validate_valid_image_url($attribute, $value, $parameters)
	{
		// Make sure the supplied URL is actually an image
		$curl = new \MagicRainbowAdventure\Tools\Curl($value);
		$content_type = $curl->get_content_type($value);

		if ( ! $content_type || ! preg_match('/image\/(.+)/i', $content_type))
		{
			$this->errors->messages[$attribute][] = \Laravel\Lang::line('entries.invalid_image_url');

			return false;
		}

		// Convert the maximum upload size to bytes
		$max_upload_size = \Laravel\Config::get('magicrainbowadventure.max_upload_size');

		if ($curl->get_content_length() > $max_upload_size * 1024)
		{
			$this->errors->messages[$attribute][] = sprintf(\Laravel\Lang::line('entries.image_too_big'), round($max_upload_size / 1024));

			return false;
		}

		return true;
	}

}
