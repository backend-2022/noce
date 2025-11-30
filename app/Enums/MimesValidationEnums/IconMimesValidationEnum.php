<?php

namespace App\Enums\MimesValidationEnums;

enum IconMimesValidationEnum: string
{
    case JPEG = 'jpeg';
    case JPG = 'jpg';
    case PNG = 'png';
    case WEBP = 'webp';
    case GIF = 'gif';
    case SVG = 'svg';

    public function label(): string
    {
        return match ($this) {
            self::JPEG => 'صورة JPEG',
            self::JPG => 'صورة JPG',
            self::PNG => 'صورة PNG',
            self::WEBP => 'صورة WEBP',
            self::GIF => 'صورة GIF',
            self::SVG => 'ملف SVG',
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

    public static function validationRule(int $maxImageSizeKb = 2048): string
    {
        return 'file|mimes:'.self::asRuleString().'|max:'.$maxImageSizeKb;
    }
}
