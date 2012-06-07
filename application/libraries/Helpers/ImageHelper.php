<?php

namespace Helpers;

/**
 * Image Helper Class
 *
 * Provides methods useful for processing images.
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class ImageHelper
{

	/**
	 * Determine whether or not the given file is an animated GIF.
	 *
	 * @param	string	$file_path
	 * @return	bool
	 * @author  Frank <frank@huddler.com>
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 * @link	http://www.php.net/manual/en/function.imagecreatefromgif.php#104473
	 */
	public static function isAnimatedGif($file_path)
	{
		if ( ! ($fh = @fopen($file_path, 'rb')))
		{
			return false;
		}

		$count = 0;

		// An animated gif contains multiple "frames", with each frame having a
		// header made up of:
		//	* a static 4-byte sequence (\x00\x21\xF9\x04)
		//	* 4 variable bytes
		//	* a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)

		// We read through the file til we reach the end of the file, or we've found
		// at least 2 frame headers
		while ( ! feof($fh) && $count < 2)
		{
			$chunk = fread($fh, 1024 * 100); //read 100kb at a time
			$count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
		}

		fclose($fh);

		return $count > 1;
	}

}
