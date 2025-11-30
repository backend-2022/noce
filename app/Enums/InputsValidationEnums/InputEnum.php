<?php

namespace App\Enums\InputsValidationEnums;

enum InputEnum: string
{
    case TITLE = 'title';
    case TEXTAREA = 'textarea';
    case DESCRIPTION = 'description';
    case NUMBER = 'number';
    case EMAIL = 'email';
    case PASSWORD = 'password';
    case DATE = 'date';
    case TIME = 'time';
    case START_DATE = 'start_date';
    case END_DATE = 'end_date';
    case START_TIME = 'start_time';
    case END_TIME = 'end_time';
    case SELECT = 'select';
    case URL = 'url';
    case PHONE = 'phone';
    case FILE = 'file';

    public function label(): string
    {
        return match ($this) {
            self::NUMBER => 'رقم',
            self::EMAIL => 'بريد إلكتروني',
            self::PASSWORD => 'كلمة المرور',
            self::DATE => 'تاريخ',
            self::TIME => 'وقت',
            self::START_DATE => 'تاريخ البدء',
            self::END_DATE => 'تاريخ النهاية',
            self::START_TIME => 'وقت البدء',
            self::END_TIME => 'وقت النهاية',
            self::TEXTAREA => 'نص منظم',
            self::SELECT => 'اختيار',
            self::TITLE => 'عنوان',
            self::DESCRIPTION => 'وصف',
            self::URL => 'رابط',
            self::PHONE => 'هاتف',
            self::FILE => 'ملف',
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

    public function getValidationRules(bool $required = true, array $options = []): string
    {
        $rules = match ($this) {
            self::TITLE => 'string|min:3|max:255',
            self::DESCRIPTION => 'string|min:3|max:1000',
            self::TEXTAREA => 'string|min:3|max:10000',
            self::NUMBER => 'numeric|min:0|max:1000000',
            self::EMAIL => 'email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            // Password must be at least 8 chars, contain lowercase, uppercase, digit and at least one non‑alphanumeric (special) character
            self::PASSWORD => 'string|min:8|max:255|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).+$/',
            self::DATE => 'date|after_or_equal:today',
            self::TIME => 'date_format:H:i',
            self::START_DATE => 'date|after_or_equal:today',
            self::END_DATE => 'date|after_or_equal:start_date',
            self::START_TIME => 'date_format:H:i',
            self::END_TIME => 'date_format:H:i',
            self::SELECT => 'string|in:' . implode(',', $options),
            self::URL => 'url|max:255',
            self::PHONE => 'string|regex:/^5\d{8}$/',
            self::FILE => 'file|max:10240',
        };

        return $required ? 'required|' . $rules : 'nullable|' . $rules;
    }

}
