<?php

declare(strict_types=1);

namespace DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder;

class KeyVariationsBuilders implements KeyVariationsBuilderInterface
{
	/**
	 * @var array<KeyVariationsInterface>
	 */
	private $builders;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->builders = [];
	}

	/**
	 * Adds key variations to collection.
	 *
	 * @param KeyVariationsBuilderInterface $builder
	 * @return KeyVariationsBuilders
	 */
	public function add(KeyVariationsBuilderInterface $builder): KeyVariationsBuilders
	{
		$this->builders[] = $builder;
		return $this;
	}

	/**
	 * Returns array with only a given key as is.
	 *
	 * @param string $key
	 * @return array<string>
	 */
	public function build(string $key): array
	{
		$keys = [];
		foreach ($this->builders as &$builder) {
			$keys = array_merge($keys, $builder->build($key));
		}

		return array_values(array_unique($keys));
	}
}
