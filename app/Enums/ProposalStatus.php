<?php

namespace App\Enums;

enum ProposalStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Declined = 'declined';
    case Withdrawn = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending Review',
            self::Accepted => 'Mutual Interest Accepted',
            self::Declined => 'Proposal Declined',
            self::Withdrawn => 'Interest Withdrawn',
        };
    }

    public function isPending(): bool
    {
        return $this === self::Pending;
    }

    public function isAccepted(): bool
    {
        return $this === self::Accepted;
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

