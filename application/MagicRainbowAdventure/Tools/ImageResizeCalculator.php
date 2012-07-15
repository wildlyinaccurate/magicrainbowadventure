<?php

namespace MagicRainbowAdventure\Tools;

/**
 * Image resize dimensions calculator
 *
 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
 */
class ImageResizeCalculator
{

	/**
	 * Calculate the resized dimensions of an image, based on its
	 * current dimensions and maximum given dimensions.
	 *
	 * @param	int		$current_width
	 * @param	int		$current_height
	 * @param	int		$max_width
	 * @param	int		$max_height
	 * @return	array
	 * @author  Joseph Wynn <joseph@wildlyinaccurate.com>
	 */
	public static function getResizedDimensions($current_width, $current_height, $max_width, $max_height)
	{
		if ( ! is_numeric($current_width) ||  ! is_numeric($current_height) ||  ! is_numeric($max_width) ||  ! is_numeric($max_height))
		{
			throw new \InvalidArgumentException('Dimensions must be numeric');
		}

		$resized_width = $current_width;
		$resized_height = $current_height;

		if ($current_width > $max_width || $current_height > $max_height)
		{
			$width_ratio = $current_width / $max_width;
			$height_ratio = $current_height / $max_height;

			if ($width_ratio > $height_ratio)
			{
				$resized_width = $max_width;
				$resized_height = round($current_height / $width_ratio);
			}
			else
			{
				$resized_height = $max_height;
				$resized_width = round($current_width / $height_ratio);
			}
		}

		return array(
			'width' => $resized_width,
			'height' => $resized_height,
		);
	}

}
