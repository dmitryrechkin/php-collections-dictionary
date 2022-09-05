<?php

declare(strict_types=1);

namespace DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder;

use DmitryRechkin\NamingConverter\CamelCaseConverter;

class CamelCaseKeyVariationsBuilder implements KeyVariationsBuilderInterface
{
	/**
	 * Returns array with only a given key as is.
	 *
	 * @param string $key
	 * @return array<string>
	 */
	public function build(string $key): array
	{
		return [$key, (new CamelCaseConverter())->convert($key),];
	}
}
