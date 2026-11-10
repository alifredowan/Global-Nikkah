<?php

namespace App\Enums;

enum WaliApprovalStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case NotRequired = 'not_required';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending Guardian Approval',
            self::Approved => 'Guardian Approved',
            self::Rejected => 'Guardian Rejected',
            self::NotRequired => 'Guardian Approval Not Required',
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

