<?php

declare(strict_types=1);

namespace DmitryRechkin\Tests\Unit\Collections\Dictionary;

use DmitryRechkin\Collections\Dictionary\Dictionary;
use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\CamelCaseKeyVariationsBuilder;
use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\KeyVariationsBuilderInterface;
use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\KeyVariationsBuilders;
use DmitryRechkin\Collections\Dictionary\KeyVariationsBuilder\SnakeCaseKeyVariationsBuilder;
use PHPUnit\Framework\TestCase;

class DictionaryTest extends TestCase
{
	/**
	 * @return void
	 */
	public function testGetHashReturnsSha512HashOverSerializedInternalArray(): void
	{
		$inputArray = ['test_test_1' => 'value1', 'key2' => 'value2'];
		$dictionary = new Dictionary($inputArray);

		$this->assertSame(hash('sha512', json_encode($inputArray)), $dictionary->getHash());
	}

	/**
	 * @return void
	 */
	public function testWitharrayReturnsDictionaryForAGivenArray(): void
	{
		$inputArray = ['test_test_1' => 'value1'];
		$dictionary = new Dictionary($inputArray);

		$newDictionary = $dictionary->withArray($inputArray);
		$this->assertNotSame($dictionary, $newDictionary);
		$this->assertSame($inputArray, $newDictionary->toArray());
	}

	/**
	 * @return void
	 */
	public function testToArrayReturnsSameValueAsInitialOne(): void
	{
		$inputArray = ['test_test_1' => 'value1'];

		$dictionary = new Dictionary($inputArray);
		$this->assertSame($inputArray, $dictionary->toArray());
	}

	/**
	 * @return void
	 */
	public function testGetForUnderscoreKeyReturnsExpectedValueForCamelCaseKey(): void
	{
		$expectedOutput = 'value1';
		$inputArray = ['test_test_1' => $expectedOutput];

		$dictionary = new Dictionary($inputArray, $this->getKeyVariationsBuilder());

		$this->assertSame($expectedOutput, $dictionary->get('test_test_1'));
		$this->assertSame($expectedOutput, $dictionary->get('testTest1'));
	}

	/**
	 * @return void
	 */
	public function testGetForCamelCaseKeyReturnsExpectedValueForUnderscoreKey(): void
	{
		$expectedOutput = 'value1';
		$inputArray = ['testTest1' => $expectedOutput];

		$dictionary = new Dictionary($inputArray, $this->getKeyVariationsBuilder());

		$this->assertSame($expectedOutput, $dictionary->get('testTest1'));
		$this->assertSame($expectedOutput, $dictionary->get('test_test_1'));
	}

	/**
	 * @return void
	 */
	public function testGetForNonExistingKeyReturnsNull(): void
	{
		$dictionary = new Dictionary([], $this->getKeyVariationsBuilder());

		$this->assertSame(null, $dictionary->get('non-existing-key'));
	}

	/**
	 * @return void
	 */
	public function testGetForNonExistingKeyReturnsDefaultValue(): void
	{
		$dictionary = new Dictionary([], $this->getKeyVariationsBuilder());

		$this->assertSame('xxxx', $dictionary->get('non-existing-key', 'xxxx'));
	}

	/**
	 * @return void
	 */
	public function testSetWithCamelCaseFollowedByUnderscoreKeyUpdatesEntryWithTheFirstKey(): void
	{
		$dictionary = new Dictionary([], $this->getKeyVariationsBuilder());
		$dictionary->set('testTest1', 'value1');
		$dictionary->set('test_test_1', 'value2');

		$this->assertSame(['testTest1' => 'value2'], $dictionary->toArray());
	}

	/**
	 * @return void
	 */
	public function testSetWithUnderscoreFollowedByCamelCaseKeyUpdatesEntryWithTheFirstKey(): void
	{
		$dictionary = new Dictionary([], $this->getKeyVariationsBuilder());
		$dictionary->set('test_test_1', 'value1');
		$dictionary->set('testTest1', 'value2');

		$this->assertSame(['test_test_1' => 'value2'], $dictionary->toArray());
	}

	/**
	 * @return void
	 */
	public function testGetFirstKeyReturnsFirstKeyOfDictionary(): void
	{
		$expectedOutput = 'key1';

		$dictionary = new Dictionary([$expectedOutput => 'value1', 'key2' => 'value2']);

		$this->assertSame($expectedOutput, $dictionary->getFirstKey());
	}

	/**
	 * @return void
	 */
	public function testGetLastKeyReturnsLastKeyOfDictionary(): void
	{
		$expectedOutput = 'key2';

		$dictionary = new Dictionary(['key1' => 'value1', $expectedOutput => 'value2']);

		$this->assertSame($expectedOutput, $dictionary->getLastKey());
	}

	/**
	 * @return void
	 */
	public function testGetKeysReturnsArrayOfKeys(): void
	{
		$expectedOutput = ['key1', 'key2'];

		$dictionary = new Dictionary(['key1' => 'value1', 'key2' => 'value2']);

		$this->assertSame($expectedOutput, $dictionary->getKeys());
	}

	/**
	 * @return void
	 */
	public function testGetValuesReturnsArrayOfValues(): void
	{
		$expectedOutput = ['value1', 'value2'];

		$dictionary = new Dictionary(['key1' => 'value1', 'key2' => 'value2']);

		$this->assertSame($expectedOutput, $dictionary->getValues());
	}

	/**
	 * @return void
	 */
	public function testHasReturnsTrueForExistingKey(): void
	{
		$dictionary = new Dictionary(['key1' => 'value1']);

		$this->assertTrue($dictionary->has('key1'));
	}

	/**
	 * @return void
	 */
	public function testHasReturnsFalseForNonExistingKey(): void
	{
		$dictionary = new Dictionary(['key1' => 'value1']);

		$this->assertFalse($dictionary->has('non-existing-key'));
	}

	/**
	 * @return void
	 */
	public function testRemoveReturnsExpectedItem(): void
	{
		$dictionary = new Dictionary(['key1' => 'value1']);
		$dictionary->remove('key1');

		$this->assertSame([], $dictionary->toArray());
	}

	/**
	 * @return void
	 */
	public function testSizeReturnsExpectedSizeOfDictionary(): void
	{
		$this->assertSame(1, (new Dictionary(['key1' => 'value1']))->size());
		$this->assertSame(0, (new Dictionary([]))->size());
	}

	/**
	 * @return void
	 */
	public function testGetStringReturnsStringValueAsString(): void
	{
		$this->assertSame('string', (new Dictionary(['key1' => 'string']))->getString('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetStringReturnsFloatValueAsString(): void
	{
		$this->assertSame('100', (new Dictionary(['key1' => 100]))->getString('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetFloatReturnsNumericStringValueAsFloat(): void
	{
		$this->assertSame(100.0, (new Dictionary(['key1' => '100.0']))->getFloat('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetFloatReturnsStringValueAsZero(): void
	{
		$this->assertSame(0.0, (new Dictionary(['key1' => 'aaaa']))->getFloat('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetFloatReturnsFloatValueAsIs(): void
	{
		$this->assertSame(99.99, (new Dictionary(['key1' => 99.99]))->getFloat('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetIntReturnsNumericStringValueAsInt(): void
	{
		$this->assertSame(100, (new Dictionary(['key1' => '100']))->getInt('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetIntReturnsStringValueAsZero(): void
	{
		$this->assertSame(0, (new Dictionary(['key1' => 'aaaa']))->getInt('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetIntReturnsIntValueAsIs(): void
	{
		$this->assertSame(99, (new Dictionary(['key1' => 99]))->getInt('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetArrayReturnsIntValueAsArray(): void
	{
		$this->assertSame([1], (new Dictionary(['key1' => 1]))->getArray('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetArrayReturnsFloatValueAsArray(): void
	{
		$this->assertSame([1.5], (new Dictionary(['key1' => 1.5]))->getArray('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetArrayReturnsStringValueAsArray(): void
	{
		$this->assertSame(['xxxx'], (new Dictionary(['key1' => 'xxxx']))->getArray('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetArrayReturnsArrayValueAsIs(): void
	{
		$this->assertSame([1, 2, 3], (new Dictionary(['key1' => [1, 2, 3]]))->getArray('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetDictionaryReturnsDictionaryForProperty(): void
	{
		$input = [
			'key1' => [
				'key2' => 'value2',
				'key3' => 'value3',
			]
		];

		$this->assertSame($input['key1'], (new Dictionary($input))->getDictionary('key1')->toArray());
	}

	/**
	 * @return void
	 */
	public function testGetBoolReturnsIntValueAsBool(): void
	{
		$this->assertSame(true, (new Dictionary(['key1' => 1]))->getBool('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetBoolReturnsStringValueAsBool(): void
	{
		$this->assertSame(true, (new Dictionary(['key1' => 'yes']))->getBool('key1'));
	}

	/**
	 * @return void
	 */
	public function testGetBoolReturnsBoolValueAsIs(): void
	{
		$this->assertSame(true, (new Dictionary(['key1' => true]))->getBool('key1'));
	}

	/**
	 * @return void
	 */
	public function testClearRemovesAllItems(): void
	{
		$dictionary = new Dictionary(['key1' => 'value1', 'key2' => 'value2']);
		$dictionary->clear();

		$this->assertSame(0, $dictionary->size());
		$this->assertSame([], $dictionary->toArray());
	}

	/**
	 * @return KeyVariationsBuilderInterface
	 */
	private function getKeyVariationsBuilder(): KeyVariationsBuilderInterface
	{
		$builder = new KeyVariationsBuilders();
		$builder->add(new SnakeCaseKeyVariationsBuilder());
		$builder->add(new CamelCaseKeyVariationsBuilder());

		return $builder;
	}
}
