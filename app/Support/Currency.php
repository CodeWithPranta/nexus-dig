<?php

namespace App\Support;

use Throwable;

class Currency
{
    public static function current(): string
    {
        $default = (string) config('currency.default', 'BDT');
        $code = $default;

        try {
            if (app()->bound('session') && session()->isStarted()) {
                $code = (string) session('currency', $default);
            }
        } catch (Throwable) {
            // Session is not available (CLI, queued jobs).
        }

        return array_key_exists($code, config('currency.supported', [])) ? $code : $default;
    }

    public static function set(string $code): bool
    {
        $code = strtoupper($code);

        if (! array_key_exists($code, config('currency.supported', []))) {
            return false;
        }

        session(['currency' => $code]);

        return true;
    }

    public static function convert(float $amount, string $from = 'BDT', ?string $to = null): float
    {
        $from = strtoupper($from);
        $to = strtoupper($to ?? self::current());

        if ($from === $to) {
            return $amount;
        }

        $base = (string) config('currency.base', 'USD');
        $rates = config('currency.exchange_rates', []);

        $usd = $from === $base ? $amount : $amount / (float) ($rates[$from] ?? 1);

        return $usd * (float) ($rates[$to] ?? 1);
    }

    public static function format(float $amount, string $from = 'BDT', ?string $to = null): string
    {
        $to = strtoupper($to ?? self::current());
        $meta = config("currency.supported.$to", []);
        $decimals = (int) ($meta['decimals'] ?? 2);
        $value = self::convert($amount, $from, $to);
        $formatted = number_format($value, $decimals, '.', ',');

        if (app()->getLocale() === 'bn') {
            $formatted = self::bengaliDigits($formatted);
        }

        return (string) ($meta['symbol'] ?? '').$formatted;
    }

    public static function dual(float $amount, string $from = 'BDT'): string
    {
        $current = self::current();
        $other = $current === 'BDT' ? 'USD' : 'BDT';

        return self::format($amount, $from, $current).' ('.self::format($amount, $from, $other).')';
    }

    public static function bengaliDigits(string $value): string
    {
        return strtr($value, [
            '0' => '০',
            '1' => '১',
            '2' => '২',
            '3' => '৩',
            '4' => '৪',
            '5' => '৫',
            '6' => '৬',
            '7' => '৭',
            '8' => '৮',
            '9' => '৯',
        ]);
    }
}
