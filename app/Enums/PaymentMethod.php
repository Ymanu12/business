<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Stripe      = 'stripe';
    case PayPal      = 'paypal';
    case MtnMomo     = 'mtn_momo';
    case OrangeMoney = 'orange_money';
    case Flooz       = 'flooz';
    case TMoney      = 'tmoney';
    case MoovMoney   = 'moov_money';
    case Wallet      = 'wallet';

    public function label(): string
    {
        return match ($this) {
            self::Stripe      => 'Carte bancaire (Stripe)',
            self::PayPal      => 'PayPal',
            self::MtnMomo     => 'MTN Mobile Money',
            self::OrangeMoney => 'Orange Money',
            self::Flooz       => 'Flooz (Moov)',
            self::TMoney      => 'T-Money (Togocel)',
            self::MoovMoney   => 'Moov Money',
            self::Wallet      => 'Wallet AfriTask',
        };
    }

    public function logo(): string
    {
        return match ($this) {
            self::Stripe      => 'stripe.svg',
            self::PayPal      => 'paypal.svg',
            self::MtnMomo     => 'mtn.svg',
            self::OrangeMoney => 'orange.svg',
            self::Flooz       => 'flooz.svg',
            self::TMoney      => 'tmoney.svg',
            self::MoovMoney   => 'moov.svg',
            self::Wallet      => 'wallet.svg',
        };
    }

    public function isAfrican(): bool
    {
        return in_array($this, [
            self::MtnMomo,
            self::OrangeMoney,
            self::Flooz,
            self::TMoney,
            self::MoovMoney,
        ]);
    }
}
