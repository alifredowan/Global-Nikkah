<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Pending = 'pending';
    case Investigating = 'investigating';
    case Resolved = 'resolved';
    case Dismissed = 'dismissed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending Review',
            self::Investigating => 'Under Investigation',
            self::Resolved => 'Resolved & Action Taken',
            self::Dismissed => 'Dismissed / Inconclusive',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

