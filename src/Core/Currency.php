<?php

namespace App\Core;

class Currency
{
    private static array $currencies = [
        'INR' => ['symbol' => '₹', 'name' => 'Indian Rupee', 'rate_to_inr' => 1.0, 'subunits' => 100],
        'USD' => ['symbol' => '$', 'name' => 'US Dollar', 'rate_to_inr' => 83.5, 'subunits' => 100],
        'EUR' => ['symbol' => '€', 'name' => 'Euro', 'rate_to_inr' => 91.0, 'subunits' => 100],
        'GBP' => ['symbol' => '£', 'name' => 'British Pound', 'rate_to_inr' => 108.5, 'subunits' => 100],
        'AED' => ['symbol' => 'AED', 'name' => 'UAE Dirham', 'rate_to_inr' => 22.7, 'subunits' => 100],
        'CAD' => ['symbol' => 'CA$', 'name' => 'Canadian Dollar', 'rate_to_inr' => 61.2, 'subunits' => 100],
        'AUD' => ['symbol' => 'AU$', 'name' => 'Australian Dollar', 'rate_to_inr' => 54.8, 'subunits' => 100],
    ];

    /**
     * Format amount in cents to human readable currency string with symbol
     */
    public static function format(int $cents, string $currencyCode = 'INR'): string
    {
        $code = strtoupper($currencyCode);
        $curr = self::$currencies[$code] ?? self::$currencies['INR'];
        $amount = $cents / ($curr['subunits'] ?? 100);

        if ($code === 'INR') {
            return $curr['symbol'] . number_format($amount, 2, '.', ',');
        }

        return $curr['symbol'] . number_format($amount, 2);
    }

    /**
     * Convert cents from one currency to another using reference rate
     */
    public static function convert(int $cents, string $from, string $to): int
    {
        $fromCode = strtoupper($from);
        $toCode = strtoupper($to);

        if ($fromCode === $toCode) {
            return $cents;
        }

        $fromRate = self::$currencies[$fromCode]['rate_to_inr'] ?? 1.0;
        $toRate = self::$currencies[$toCode]['rate_to_inr'] ?? 1.0;

        // Convert from base INR
        $amountInInr = $cents * $fromRate;
        $targetCents = $amountInInr / $toRate;

        return (int)round($targetCents);
    }

    /**
     * List all supported currencies
     */
    public static function all(): array
    {
        return self::$currencies;
    }

    /**
     * Get symbol for currency code
     */
    public static function getSymbol(string $currencyCode): string
    {
        $code = strtoupper($currencyCode);
        return self::$currencies[$code]['symbol'] ?? '₹';
    }
}
