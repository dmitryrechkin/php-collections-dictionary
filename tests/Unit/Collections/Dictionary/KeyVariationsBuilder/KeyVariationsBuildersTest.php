<?php

declare(strict_types=1);

namespace DmitryRechkin\Tests\Unit\Collections\Dictionary\KeyVariationsBuilder;

use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\CamelCaseKeyVariationsBuilder;
use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\KeyVariationsBuilders;
use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\SnakeCaseKeyVariationsBuilder;
use PHPUnit\Framework\TestCase;

class KeyVariationsBuildersTest extends TestCase
{
	/**
	 * @return void
	 */
	public function testAddMethodReturnsSelf(): void
	{
		$builder = new KeyVariationsBuilders();
		$this->assertSame($builder, $builder->add(new SnakeCaseKeyVariationsBuilder()));
	}

	/**
	 * @return void
	 */
	public function testBuildMethodReturnsCombinesResults(): void
	{
		$input = 'testTest1234_test_test';
		$expectedOutput = [
			$input,
			'test_test_1234_test_test',
			'testtest1234TestTest',
		];

		$builder = new KeyVariationsBuilders();
		$builder->add(new SnakeCaseKeyVariationsBuilder());
		$builder->add(new CamelCaseKeyVariationsBuilder());

		$this->assertSame($expectedOutput, $builder->build($input));
	}
}
