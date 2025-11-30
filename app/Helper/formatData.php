<?php

function formatId($id): string
{
    $formatter = new class
    {
        use \App\Traits\FormatsData;
    };

    return $formatter->formatId($id);
}

function formatDate($date): ?string
{
    $formatter = new class
    {
        use \App\Traits\FormatsData;
    };

    return $formatter->formatDate($date);
}

function formatTime($time): ?string
{
    $formatter = new class
    {
        use \App\Traits\FormatsData;
    };

    return $formatter->formatTime($time);
}

function formatDateTime($date): ?string
{
    $formatter = new class
    {
        use \App\Traits\FormatsData;
    };

    return $formatter->formatDateTime($date);
}

function formatCurrency($amount): string
{
    $formatter = new class
    {
        use \App\Traits\FormatsData;
    };

    return $formatter->formatAmountCurrency($amount);
}
