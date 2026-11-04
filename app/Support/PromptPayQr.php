<?php

namespace App\Support;

use InvalidArgumentException;

class PromptPayQr
{
    public static function payload(string $promptPayId, float $amount): string
    {
        $proxyId = preg_replace('/\D+/', '', $promptPayId);
        if (strlen($proxyId) === 10 && str_starts_with($proxyId, '0')) {
            $proxyType = '01';
            $proxyId = '0066' . substr($proxyId, 1);
        } elseif (strlen($proxyId) === 13) {
            $proxyType = '02';
        } elseif (strlen($proxyId) === 15) {
            $proxyType = '03';
        } else {
            throw new InvalidArgumentException('PromptPay ID must be a 10-digit phone, 13-digit national ID, or 15-digit e-wallet ID.');
        }

        if ($amount <= 0) {
            throw new InvalidArgumentException('PromptPay amount must be greater than zero.');
        }

        $merchantAccount = self::tlv('00', 'A000000677010111')
            . self::tlv($proxyType, $proxyId);

        $payload = self::tlv('00', '01')
            . self::tlv('01', '12')
            . self::tlv('29', $merchantAccount)
            . self::tlv('52', '0000')
            . self::tlv('53', '764')
            . self::tlv('54', number_format($amount, 2, '.', ''))
            . self::tlv('58', 'TH')
            . self::tlv('59', 'PROMPTPAY')
            . self::tlv('60', 'BANGKOK')
            . self::tlv('62', self::tlv('05', 'ANNUALTRASH'))
            . '6304';

        return $payload . strtoupper(str_pad(dechex(self::crc16($payload)), 4, '0', STR_PAD_LEFT));
    }

    private static function tlv(string $tag, string $value): string
    {
        return $tag . str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    private static function crc16(string $payload): int
    {
        $crc = 0xFFFF;
        $length = strlen($payload);

        for ($index = 0; $index < $length; $index++) {
            $crc ^= ord($payload[$index]) << 8;
            for ($bit = 0; $bit < 8; $bit++) {
                $crc = ($crc & 0x8000) !== 0
                    ? (($crc << 1) ^ 0x1021)
                    : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return $crc;
    }
}