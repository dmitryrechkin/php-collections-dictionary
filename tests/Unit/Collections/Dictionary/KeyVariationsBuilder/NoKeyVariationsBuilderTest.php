<?php

declare(strict_types=1);

namespace DmitryRechkin\Tests\Unit\Collections\Dictionary\KeyVariationsBuilder;

use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\NoKeyVariationsBuilder;
use PHPUnit\Framework\TestCase;

class NoKeyVariationsBuilderTest extends TestCase
{
	/**
	 * @return void
	 */
	public function testBuildMethodReturnsArrayWithOriginalKey(): void
	{
		$input = 'test_test';
		$expectedOutput = [$input,];

		$builder = new NoKeyVariationsBuilder();

		$this->assertSame($expectedOutput, $builder->build($input));
	}
}
