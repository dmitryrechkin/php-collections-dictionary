<?php

declare(strict_types=1);

namespace DmitryRechkin\Tests\Unit\Collections\Dictionary\KeyVariationsBuilder;

use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\CamelCaseKeyVariationsBuilder;
use PHPUnit\Framework\TestCase;

class CamelCaseKeyVariationsBuilderTest extends TestCase
{
	/**
	 * @return void
	 */
	public function testBuildMethodReturnsArrayWithOriginalKeyAndCamelCasedKey(): void
	{
		$input = 'test_test_1';
		$expectedOutput = [
			$input,
			'testTest1',
		];

		$builder = new CamelCaseKeyVariationsBuilder();

		$this->assertSame($expectedOutput, $builder->build($input));
	}
}
