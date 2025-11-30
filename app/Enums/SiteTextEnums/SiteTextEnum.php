<?php

namespace App\Enums\SiteTextEnums;

enum SiteTextEnum: string
{
    case HOME_BANNER = 'home_banner';
    case HOW_WORK = 'how_work';
    case FEATURES = 'features';
    case INCREASE_PROFITS = 'increase_profits';

    public function label(): string
    {
        return match ($this) {
            self::HOME_BANNER => 'بانر الرئيسية',
            self::HOW_WORK => 'كيف جدولها يشتغل؟',
            self::FEATURES => 'المميزات',
            self::INCREASE_PROFITS => 'زيادة الأرباح',
        };
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function asRuleString(): string
    {
        return implode(',', self::getValues());
    }
}
