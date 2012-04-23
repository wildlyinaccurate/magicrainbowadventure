<?php

namespace Validators;

class EntryValidator extends \Laravel\Validator
{

	/**
	 * Make sure a URL is pointing to a valid image
	 *
	 * @param	string	$attribute
	 * @param	string	$value
	 * @param	array	$parameters
	 * @return	bool
	 */
	public function validate_valid_image_url($attribute, $value, $parameters)
	{
		// Make sure the supplied URL is actually an image
		$curl = new \EasyCurl($value);
		$content_type = $curl->get_content_type($value);

		if ( ! $content_type || ! preg_match('/image\/(.+)/i', $content_type))
		{
			$this->errors->messages[$attribute][] = 'The link you entered does not appear to be an image.';

			return false;
		}

		// Convert the maximum upload size to bytes
		$max_upload_size = \Laravel\Config::get('magicrainbowadventure.max_upload_size') * 1024;

		if ($curl->get_content_length() > $max_upload_size)
		{
			$this->errors->messages[$attribute][] = 'This image is too big! Choose something that is smaller than ' . round($max_upload_size / 1024 / 1024) . 'MB.';

			return false;
		}

		return true;
	}

}
