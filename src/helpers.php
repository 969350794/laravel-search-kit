<?php

declare(strict_types=1);

if (!function_exists('isDatetime')) {
    /**
     * 判断字符串是否为日期
     * @param string $datetime 日期字符串,eg:2021-01-01 00:00:00
     * @return false|int 返回$date对应的时间戳或者false
     */
    function isDatetime(string $datetime): false|int
    {
        if (empty($datetime)){
            return false;
        }

        $utc = strtotime($datetime);
        if ($utc === false || $utc < 0) {
            return false;
        }

        return $utc;
    }
}
