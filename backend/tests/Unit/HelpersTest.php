<?php

namespace Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для функций хелперов в bootstrap/helpers.php
 */
class HelpersTest extends TestCase
{
    public function testGetMonth(): void
    {
        $this->assertEquals('январь', getMonth(1));
        $this->assertEquals('февраль', getMonth(2));
        $this->assertEquals('март', getMonth(3));
        $this->assertEquals('апрель', getMonth(4));
        $this->assertEquals('май', getMonth(5));
        $this->assertEquals('июнь', getMonth(6));
        $this->assertEquals('июль', getMonth(7));
        $this->assertEquals('август', getMonth(8));
        $this->assertEquals('сентябрь', getMonth(9));
        $this->assertEquals('октябрь', getMonth(10));
        $this->assertEquals('ноябрь', getMonth(11));
        $this->assertEquals('декабрь', getMonth(12));
        $this->assertEquals(1, getMonth('январь', true));
        $this->assertEquals(2, getMonth('февраль', true));
        $this->assertEquals(3, getMonth('март', true));
        $this->assertEquals(4, getMonth('апрель', true));
        $this->assertEquals(5, getMonth('май', true));
        $this->assertEquals(6, getMonth('июнь', true));
        $this->assertEquals(7, getMonth('июль', true));
        $this->assertEquals(8, getMonth('август', true));
        $this->assertEquals(9, getMonth('сентябрь', true));
        $this->assertEquals(10, getMonth('октябрь', true));
        $this->assertEquals(11, getMonth('НОЯБРЬ', true));
        $this->assertEquals(12, getMonth('декабрь', true));
        $this->expectException(InvalidArgumentException::class);
        getMonth('abc', true);
        getMonth(13);
        getMonth(0);
    }

    public function testGetShortName(): void
    {
        $data = ['surname' => 'Иванов'];
        $this->assertEquals('Иванов', getShortFullName($data));
        $data = ['name' => 'Иван'];
        $this->assertEquals('И.', getShortFullName($data));
        $data = ['patronymic' => 'Иванович'];
        $this->assertEquals('И.', getShortFullName($data));
        $data = ['surname' => 'Иванов', 'name' => 'Иван'];
        $this->assertEquals('Иванов И.', getShortFullName($data));
        $data = ['name' => 'Иван', 'patronymic' => 'Иванович'];
        $this->assertEquals('И. И.', getShortFullName($data));
        $data = ['surname' => 'Иванов', 'name' => 'Иван', 'patronymic' => 'Иванович'];
        $this->assertEquals('Иванов И. И.', getShortFullName($data));
        $this->expectException(InvalidArgumentException::class);
        $data = ['abc' => 'abc'];
        getShortFullName($data);
    }

    public function testGetFullName(): void
    {
        $data = ['surname' => 'Иванов'];
        $this->assertEquals('Иванов', getFullName($data));
        $data = ['name' => 'Иван'];
        $this->assertEquals('Иван', getFullName($data));
        $data = ['patronymic' => 'Иванович'];
        $this->assertEquals('Иванович', getFullName($data));
        $data = ['surname' => 'Иванов', 'name' => 'Иван'];
        $this->assertEquals('Иванов Иван', getFullName($data));
        $data = ['name' => 'Иван', 'patronymic' => 'Иванович'];
        $this->assertEquals('Иван Иванович', getFullName($data));
        $data = ['surname' => 'Иванов', 'name' => 'Иван', 'patronymic' => 'Иванович'];
        $this->assertEquals('Иванов Иван Иванович', getFullName($data));
        $this->expectException(InvalidArgumentException::class);
        $data = ['abc' => 'abc'];
        getFullName($data);
    }

    public function testNum2alpha(): void
    {
        $this->assertEquals('A', num2alpha(0));
        $this->assertEquals('AA', num2alpha(26));
        $this->expectException(InvalidArgumentException::class);
        num2alpha(-123432);
        $this->expectException(InvalidArgumentException::class);
        num2alpha('435');
    }
}
