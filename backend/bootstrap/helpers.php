<?php

if (!function_exists('getMonthName')) {
    /**
     * Отдает название месяца по его номеру или индекс по названию, например, 1 - январь, 2 - февраль и т.д.
     * По умолчанию принимает индекс месяца, при положительном втором аргументе отдает индекс.
     *
     * @param string|int $month
     * @param bool $reverse положительное значение ожидает на вход наименование месяца и отдает его индекс
     * @return string
     */
    function getMonth(string|int $month, bool $reverse = false): string
    {
        $months = [
            1 => 'январь',
            2 => 'февраль',
            3 => 'март',
            4 => 'апрель',
            5 => 'май',
            6 => 'июнь',
            7 => 'июль',
            8 => 'август',
            9 => 'сентябрь',
            10 => 'октябрь',
            11 => 'ноябрь',
            12 => 'декабрь',
        ];

        if (!$reverse && !is_numeric($month) && (int)$month > 0 && (int)$month < 13) {
            throw new InvalidArgumentException('Первым аргументом должен быть индекс месяца, от 1 до 12');
        }
        if ($reverse && is_numeric($month) ||
            $reverse && !is_string($month) ||
            $reverse && !in_array(mb_strtolower($month), $months)) {
            throw new InvalidArgumentException('Первым аргументом должно быть наименование месяца, например, январь');
        }

        if ($reverse) {
            $month = mb_strtolower($month);
            $months = array_flip($months);
        } else {
            $month = (int)$month;
        }

        return $months[$month];
    }
}

if (!function_exists('getShortFullName')) {
    /**
     * Возвращает краткое ФИО пользователя по полям массива surname, name, patronymic, например, Иванов И. И.
     *
     * @param array $user
     * @return string
     */
    function getShortFullName(array $user): string
    {
        if (!isset($user['surname']) && !isset($user['name']) && !isset($user['patronymic'])) {
            throw new InvalidArgumentException('Поля surname, name, patronymic должны быть установлены в массиве user');
        }
        $shortFullName = '';
        if (isset($user['surname'])) {
            $shortFullName .= $user['surname'];
        }
        if (isset($user['name'])) {
            if (!empty($shortFullName)){
                $shortFullName .= ' ';
            }
            $shortFullName .= mb_substr($user['name'], 0, 1) . '.';
        }
        if (isset($user['patronymic'])) {
            if (!empty($shortFullName)){
                $shortFullName .= ' ';
            }
            $shortFullName .= mb_substr($user['patronymic'], 0, 1) . '.';
        }
        return $shortFullName;
    }
}

if (!function_exists('getFullName')) {
    /**
     * Возвращает полное ФИО пользователя по полям массива surname, name, patronymic, например, Иванов Иван Иванович.
     *
     * @param array $user
     * @return string
     */
    function getFullName(array $user): string
    {
        if (!isset($user['surname']) && !isset($user['name']) && !isset($user['patronymic'])) {
            throw new InvalidArgumentException('Поля фамилия, имя или отчество должны быть установлены в массиве пользователя');
        }
        $fullName = '';
        if (isset($user['surname'])) {
            $fullName .= $user['surname'];
        }
        if (isset($user['name'])) {
            if (!empty($fullName)){
                $fullName .= ' ';
            }
            $fullName .= $user['name'];
        }
        if (isset($user['patronymic'])) {
            if (!empty($fullName)){
                $fullName .= ' ';
            }
            $fullName .= $user['patronymic'];
        }
        return $fullName;
    }
}

if (!function_exists('num2alpha')) {
    /**
     * Преобразует цифру в буквенную строку, например, 0 - A, 1 - B, 26 - AA, и т.д.
     *
     * @param int $n
     * @return string
     */
    function num2alpha(int $n): string
    {
        if ($n < 0) {
            throw new InvalidArgumentException('Первым аргументом должно быть положительное или равным 0 число');
        }
        for ($r = ''; $n >= 0; $n = intval($n / 26) - 1) {
            $r = chr($n % 26 + 0x41) . $r;
        }
        return $r;
    }
}

if (!function_exists('searchFirstKeyInMultidimensionalArray')) {
    /**
     * Поиск первого ключа двумерного массива, в котором находится заданное значение
     *
     * @param string $needle
     * @param array $haystack
     * @return string|false
     */
    function searchFirstKeyInMultidimensionalArray(string $needle, array $haystack): string|false
    {
        foreach ($haystack as $key => $values) {
            if (array_search($needle, $values)) {
                return $key;
            }
        }
        return false;
    }
}

if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
    /**
     * Преобразовывает строку, делая первую букву заглавной. Для многобайтовой кодировки (русских слов).
     *
     * @param $string
     * @return string
     */
    function mb_ucfirst($string): string
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }
}
