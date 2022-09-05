<?php

declare(strict_types=1);

namespace DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder;

interface KeyVariationsBuilderInterface
{
	/**
	 * Returns an array of possible key variations in the order of preferred priority.
	 * First key variation will have a priority over others and will be used as a default key.
	 *
	 * @param string $key
	 * @return array<string>
	 */
	public function build(string $key): array;
}
