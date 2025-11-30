<?php

namespace App\Traits;

use Carbon\Carbon;
use Exception;

trait FormatsData
{
    protected const CURRENCY_SYMBOL = 'ر.س';

    protected const DATE_FORMAT = 'Y-m-d';

    protected const DATE_TIME_FORMAT = 'Y-m-d H:i';

    protected const TIME_FORMAT = 'H:i';

    public function formatId($id): string
    {
        return str_pad($id, 6, '0', STR_PAD_LEFT);
    }

    public function formatDate($date, $format = self::DATE_FORMAT): ?string
    {
        if (! $date) {
            return null;
        }

        try {
            return Carbon::parse($date)->format($format); // the date in format Y-m-d ex: 2024-01-15
        } catch (Exception $e) {
            return null;
        }
    }

    public function formatTime($time, $format = self::TIME_FORMAT): ?string
    {
        if (! $time) {
            return null;
        }

        try {
            return Carbon::parse($time)->format($format); // ex: 14:30
        } catch (Exception $e) {
            return null;
        }
    }

    public function formatDateTime($date, $format = self::DATE_TIME_FORMAT): ?string
    {
        if (! $date) {
            return null;
        }
        try {
            return Carbon::parse($date)->format($format);  // the date in format Y-m-d H:i ex: 2024-01-15 14:30
        } catch (Exception $e) {
            return null;
        }
    }

    public function formatAmountCurrency($amount, $currency = self::CURRENCY_SYMBOL): string
    {
        return number_format((float) ($amount ?? 0), 2).' '.$currency;

    }

    public function formatArabicDate($date): ?string
    {
        if (! $date) {
            return null;
        }

        try {
            $carbon = Carbon::parse($date);

            $arabicMonths = [
                1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
                5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
                9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
            ];

            $day = $carbon->day;
            $month = $arabicMonths[$carbon->month];
            $year = $carbon->year;

            return "$day $month $year";
        } catch (Exception $e) {
            return null;
        }
    }

    public function formatArabicDateTime($date): ?string
    {
        if (! $date) {
            return null;
        }

        try {
            $carbon = Carbon::parse($date);

            $arabicMonths = [
                1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
                5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
                9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
            ];

            $day = $carbon->day;
            $month = $arabicMonths[$carbon->month];
            $year = $carbon->year;
            $time = $carbon->format('H:i');

            return "$day $month $year - $time";
        } catch (Exception $e) {
            return null;
        }
    }

    public function formatPhoneNumber($phone, $phoneCode): string
    {
        return '+'.$phoneCode.$phone;
    }
}
