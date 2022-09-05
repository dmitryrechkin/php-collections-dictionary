<?php

declare(strict_types=1);

namespace DmitryRechkin\Collections\Dictionary;

use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\KeyVariationsBuilderInterface;
use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\NoKeyVariationsBuilder;
use DmitryRechkin\NumberParser\NumberParser;

class Dictionary implements DictionaryInterface
{
	/**
	 * @var array<string,mixed>
	 */
	private $data;

	/**
	 * @var KeyVariationsBuilderInterface
	 */
	private $keyVariationsBuilder;

	/**
	 * Constructor.
	 *
	 * @param array $data
	 * @param KeyVariationsBuilderInterface $keyVariationsBuilder
	 */
	public function __construct(array $data = [], KeyVariationsBuilderInterface $keyVariationsBuilder = null)
	{
		$this->data = $data;
		$this->keyVariationsBuilder = $keyVariationsBuilder ?? new NoKeyVariationsBuilder();
	}

	/**
	 * Returns hash of all the entire dictionary.
	 *
	 * @return string
	 */
	public function getHash(): string
	{
		return hash('sha512', json_encode($this->data));
	}

	/**
	 * Creates new dictionary with a given array.
	 *
	 * @param array $data
	 * @return DictionaryInterface
	 */
	public function withArray(array $data): DictionaryInterface
	{
		$dictionary = clone $this;
		$dictionary->data = $data;

		return $dictionary;
	}

	/**
	 * Clears a given collection.
	 *
	 * @return void
	 */
	public function clear(): void
	{
		$this->data = [];
	}

	/**
	 * Returns value for the first matched key variation or null.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key, $default = null)
	{
		$keyVariations = $this->keyVariationsBuilder->build($key);
		foreach ($keyVariations as $keyVariation) {
			if (isset($this->data[$keyVariation])) {
				return $this->data[$keyVariation];
			}
		}

		return $default;
	}

	/**
	 * Returns value for the first matched key variation as string.
	 *
	 * @param string $key
	 * @param string $default
	 * @return string
	 */
	public function getString(string $key, string $default = ''): string
	{
		return (string)$this->get($key, $default);
	}

	/**
	 * Returns value for the first matched key variation as bool.
	 *
	 * @param string $key
	 * @param boolean $default
	 * @return boolean
	 */
	public function getBool(string $key, bool $default = false): bool
	{
		return filter_var($this->get($key, $default), FILTER_VALIDATE_BOOLEAN);
	}

	/**
	 * Returns value for the first matched key variation as float.
	 *
	 * @param string $key
	 * @param float $default
	 * @return float
	 */
	public function getFloat(string $key, float $default = 0.0): float
	{
		return (new NumberParser())->parseFloat($this->get($key, $default));
	}

	/**
	 * Returns value for the first matched key variation as int.
	 *
	 * @param string $key
	 * @param integer $default
	 * @return integer
	 */
	public function getInt(string $key, int $default = 0): int
	{
		return (new NumberParser())->parseInt($this->get($key, $default));
	}

	/**
	 * Returns value for the first matched key variation as array.
	 *
	 * @param string $key
	 * @param array $default
	 * @return array
	 */
	public function getArray(string $key, array $default = []): array
	{
		return (array)$this->get($key, $default);
	}

	/**
	 * Returns value for the first matched key variation as dictionary.
	 *
	 * @param string $key
	 * @param DictionaryInterface $default
	 * @return DictionaryInterface
	 */
	public function getDictionary(string $key, DictionaryInterface $default = null): DictionaryInterface
	{
		$value = $this->get($key, $default ?? []);

		return $value instanceof DictionaryInterface ? $value : $this->withArray((array)$value);
	}

	/**
	 * Returns first key of a given dictionary.
	 *
	 * @return string
	 */
	public function getFirstKey(): string
	{
		return array_key_first($this->data);
	}

	/**
	 * Returns array of dictionary keys.
	 *
	 * @return array<string>
	 */
	public function getKeys(): array
	{
		return array_keys($this->data);
	}

	/**
	 * Returns last key of a given dictionary.
	 *
	 * @return string
	 */
	public function getLastKey(): string
	{
		return array_key_last($this->data);
	}

	/**
	 * Returns array of dictionary values.
	 *
	 * @return array<mixed>
	 */
	public function getValues(): array
	{
		return array_values($this->data);
	}

	/**
	 * Returns true when value existing for the one of key variations.
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function has(string $key): bool
	{
		return null !== $this->get($key);
	}

	/**
	 * Removes values for all key variations.
	 *
	 * @param string $key
	 * @return void
	 */
	public function remove(string $key): void
	{
		$keyVariations = $this->keyVariationsBuilder->build($key);
		foreach ($keyVariations as $keyVariation) {
			unset($this->data[$keyVariation]);
		}
	}

	/**
	 * Sets a given value to one of the previously set key variations or to the first key variation.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function set(string $key, $value): void
	{
		$keyVariations = $this->keyVariationsBuilder->build($key);
		if (empty($keyVariations)) {
			return;
		}

		foreach ($keyVariations as $keyVariation) {
			if (array_key_exists($keyVariation, $this->data)) {
				$this->data[$keyVariation] = $value;
				return;
			}
		}

		$this->data[$keyVariations[0]] = $value;
	}

	/**
	 * Returns size of a given collection.
	 *
	 * @return integer
	 */
	public function size(): int
	{
		return count($this->data);
	}

	/**
	 * Returns collection as array.
	 *
	 * @return array<string,mixed>
	 */
	public function toArray(): array
	{
		return $this->data;
	}
}
