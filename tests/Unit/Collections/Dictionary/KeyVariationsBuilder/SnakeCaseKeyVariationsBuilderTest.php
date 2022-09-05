<?php

declare(strict_types=1);

namespace DmitryRechkin\Tests\Unit\Collections\Dictionary\KeyVariationsBuilder;

use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\SnakeCaseKeyVariationsBuilder;
use PHPUnit\Framework\TestCase;

class SnakeCaseKeyVariationsBuilderTest extends TestCase
{
	/**
	 * @return void
	 */
	public function testBuildReturnsArrayWithOriginalKeyWithKeyInSnakeCaseFormat(): void
	{
		$input = 'testTest1234Test_Test';
		$expectedOutput = [
			$input,
			'test_test_1234_test_test',
		];

		$builder = new SnakeCaseKeyVariationsBuilder();

		$this->assertSame($expectedOutput, $builder->build($input));
	}
}
