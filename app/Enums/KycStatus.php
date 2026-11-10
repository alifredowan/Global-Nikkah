<?php

namespace App\Enums;

enum KycStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending Review',
            self::Approved => 'Identity Verified',
            self::Rejected => 'Verification Rejected',
        };
    }

    public function isApproved(): bool
    {
        return $this === self::Approved;
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

