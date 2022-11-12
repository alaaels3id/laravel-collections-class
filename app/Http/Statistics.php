<?php

namespace App\Http;

class Statistics
{
    public static function berMonthCount($collection): array
    {
        $months = [];

        foreach (self::getMonthsArray() as $key => $month)
        {
            $months[$key] = $collection->filter(self::byMonthName(ucfirst($month)));
        }

        return $months;
    }

    public static function berDayCount($collection): array
    {
        $days = [];

        foreach (self::getDaysInArray() as $day)
        {
            $days[$day] = $collection->filter(self::byDayName(ucfirst($day)));
        }

        return $days;
    }

    public static function getCurrentYearTotalByColumnNameInModel($model, $onlyTotal = false, $column = 'total'): array
    {
        $months = [];

        foreach (self::berMonthCount($model::whereYear('created_at', date('Y'))->get()) as $month => $item)
        {
            $months[$month] = (int)round($item->sum($column));
        }

        if($onlyTotal) return array_values($months);

        return $months;
    }

    public static function berMonthAndDayCount($collection): array
    {
        $months = [];

        foreach (self::getMonthsArray() as $key => $month)
        {
            $months[$key] = self::berDayCount($collection->filter(self::byMonthName(ucfirst($month))));
        }

        return $months;
    }

    public static function getInSelectForm($collection, $name = 'name'): array
    {
        $arr = [];

        foreach ($collection as $item)
        {
            $arr[$item->id] = $item->$name;
        }

        return $arr;
    }

    public static function getTableYearsList($model, $column = 'created_at'): array
    {
        $years = [];

        $collection = $model::select($column)->get()->pluck($column)->toArray();

        foreach ($collection as $date)
        {
            $years[] = $date->format('Y');
        }

        return array_unique($years);
    }

    // Helpers

    private static function getMonthsArray($month = null)
    {
        $months = [
            'jan' => 'january',
            'feb' => 'february',
            'mar' => 'march',
            'apr' => 'april',
            'may' => 'may',
            'jun' => 'june',
            'jul' => 'july',
            'aug' => 'august',
            'sep' => 'september',
            'oct' => 'october',
            'nov' => 'november',
            'dec' => 'december',
        ];

        return $month != null ? $months[$month] : $months;
    }

    private static function getDaysInArray($day = null)
    {
        $days = ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        return $day != null ? $days[$day] : $days;
    }

    private static function byMonthName($month): \Closure
    {
        return function ($item) use($month) {
            return $item->created_at->format('F') == $month;
        };
    }

    private static function byDayName($day): \Closure
    {
        return function ($item) use($day) {
            return $item->created_at->format('l') == $day;
        };
    }
}
