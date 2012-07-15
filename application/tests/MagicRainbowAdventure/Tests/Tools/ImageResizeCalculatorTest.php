<?php

namespace MagicRainbowAdventure\Tests\Tools;

use MagicRainbowAdventure\Tests\BaseTestCase,
	MagicRainbowAdventure\Tools\ImageResizeCalculator;

class ImageResizeCalculatorTest extends BaseTestCase
{

	public function testGetResizedDimensionsReturnsValidArray()
	{
		$dimensions = ImageResizeCalculator::getResizedDimensions(200, 200, 200, 200);

		$this->assertArrayHasKey('width', $dimensions);
		$this->assertArrayHasKey('height', $dimensions);
	}

	/**
	 * @expectedException	InvalidArgumentException
	 */
	public function testGetResizedDimensionsThrowsExceptionOnInvalidArguments()
	{
		$dimensions = ImageResizeCalculator::getResizedDimensions(200, array(), 200, 200);
	}

	public function testSmallImageIsntResized()
	{
		$dimensions = ImageResizeCalculator::getResizedDimensions(200, 200, 800, 800);

		$this->assertEquals(200, $dimensions['width']);
		$this->assertEquals(200, $dimensions['height']);
	}

	public function testSquareImageIsResizedCorrectly()
	{
		$max_width = 640;
		$max_height = 1280;
		$expected = min($max_width, $max_height);

		$dimensions = ImageResizeCalculator::getResizedDimensions(800, 800, $max_width, $max_height);
		$this->assertEquals($expected, $dimensions['width']);
		$this->assertEquals($expected, $dimensions['height']);

		$dimensions = ImageResizeCalculator::getResizedDimensions(2000, 2000, $max_width, $max_height);
		$this->assertEquals($expected, $dimensions['width']);
		$this->assertEquals($expected, $dimensions['height']);
	}

	public function testLandscapeImageIsResizedCorrectly()
	{
		$max_width = 640;
		$max_height = 1280;

		$dimensions = ImageResizeCalculator::getResizedDimensions(800, 600, $max_width, $max_height);
		$this->assertEquals(640, $dimensions['width']);
		$this->assertEquals(480, $dimensions['height']);

		$dimensions = ImageResizeCalculator::getResizedDimensions(2400, 1800, $max_width, $max_height);
		$this->assertEquals(640, $dimensions['width']);
		$this->assertEquals(480, $dimensions['height']);
	}

	public function testPortraitImageIsResizedCorrectly()
	{
		$max_width = 640;
		$max_height = 1920;

		$dimensions = ImageResizeCalculator::getResizedDimensions(800, 1800, $max_width, $max_height);
		$this->assertEquals(640, $dimensions['width']);
		$this->assertEquals(1440, $dimensions['height']);

		$dimensions = ImageResizeCalculator::getResizedDimensions(1200, 1600, $max_width, $max_height);
		$this->assertEquals(640, $dimensions['width']);
		$this->assertEquals(853, $dimensions['height']);

		$max_width = 1010;
		$max_height = 3030;

		$dimensions = ImageResizeCalculator::getResizedDimensions(800, 1800, $max_width, $max_height);
		$this->assertEquals(800, $dimensions['width']);
		$this->assertEquals(1800, $dimensions['height']);
	}

}
