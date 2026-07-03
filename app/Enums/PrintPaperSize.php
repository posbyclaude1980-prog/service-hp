<?php

namespace App\Enums;

enum PrintPaperSize: string
{
    case A4 = 'a4';
    case F4 = 'f4';
    case Continuous = 'continuous';

    public function label(): string
    {
        return match ($this) {
            self::A4 => 'A4',
            self::F4 => 'F4 (Folio)',
            self::Continuous => 'Continuous Form',
        };
    }

    public function cssClass(): string
    {
        return 'paper-'.$this->value;
    }

    /**
     * Lebar efektif dalam karakter (Epson LX-300, 12 CPI).
     */
    public function columns(): int
    {
        return match ($this) {
            self::A4 => 96,
            self::F4 => 100,
            self::Continuous => 80,
        };
    }

    public static function fromRequest(?string $value): self
    {
        return match ($value) {
            'f4' => self::F4,
            'continuous' => self::Continuous,
            default => self::A4,
        };
    }
}
