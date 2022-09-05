<?php

declare(strict_types=1);

namespace DmitryRechkin\Collections\Dictionary;

interface DictionaryInterface
{
	/**
	 * Returns hash of all the entire dictionary.
	 *
	 * @return string
	 */
	public function getHash(): string;

	/**
	 * Creates new dictionary with a given array.
	 *
	 * @param array $values
	 * @return DictionaryInterface
	 */
	public function withArray(array $values): DictionaryInterface;

	/**
	 * Clears a given collection.
	 *
	 * @return void
	 */
	public function clear(): void;

	/**
	 * Returns value for the first matched key variation or null.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(string $key, $default = null);

	/**
	 * Returns value for the first matched key variation as string.
	 *
	 * @param string $key
	 * @param string $default
	 * @return string
	 */
	public function getString(string $key, string $default = ''): string;

	/**
	 * Returns value for the first matched key variation as bool.
	 *
	 * @param string $key
	 * @param boolean $default
	 * @return boolean
	 */
	public function getBool(string $key, bool $default = false): bool;

	/**
	 * Returns value for the first matched key variation as float.
	 *
	 * @param string $key
	 * @param float $default
	 * @return float
	 */
	public function getFloat(string $key, float $default = 0.0): float;

	/**
	 * Returns value for the first matched key variation as int.
	 *
	 * @param string $key
	 * @param integer $default
	 * @return integer
	 */
	public function getInt(string $key, int $default = 0): int;

	/**
	 * Returns value for the first matched key variation as array.
	 *
	 * @param string $key
	 * @param array $default
	 * @return array
	 */
	public function getArray(string $key, array $default = []): array;

	/**
	 * Returns value for the first matched key variation as dictionary.
	 *
	 * @param string $key
	 * @param DictionaryInterface $default
	 * @return DictionaryInterface
	 */
	public function getDictionary(string $key, DictionaryInterface $default = null): DictionaryInterface;

	/**
	 * Returns first key of a given dictionary.
	 *
	 * @return string
	 */
	public function getFirstKey(): string;

	/**
	 * Returns array of dictionary keys.
	 *
	 * @return array<string>
	 */
	public function getKeys(): array;

	/**
	 * Returns last key of a given dictionary.
	 *
	 * @return string
	 */
	public function getLastKey(): string;

	/**
	 * Returns array of dictionary values.
	 *
	 * @return array<mixed>
	 */
	public function getValues(): array;

	/**
	 * Returns true when value existing for the one of key variations.
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function has(string $key): bool;

	/**
	 * Removes values for all key variations.
	 *
	 * @param string $key
	 * @return void
	 */
	public function remove(string $key): void;

	/**
	 * Sets a given value to one of the previously set key variations or to the first key variation.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function set(string $key, $value): void;

	/**
	 * Returns size of a given collection.
	 *
	 * @return integer
	 */
	public function size(): int;

	/**
	 * Returns collection as array.
	 *
	 * @return array<string,mixed>
	 */
	public function toArray(): array;
}
