<?php

namespace App\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Groom (Brother)',
            self::Female => 'Bride (Sister)',
        };
    }

    public function opposite(): self
    {
        return match ($this) {
            self::Male => self::Female,
            self::Female => self::Male,
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

