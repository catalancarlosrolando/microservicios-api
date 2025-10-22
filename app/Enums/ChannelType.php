<?php

namespace App\Enums;

enum ChannelType: string
{
    case DEPARTMENT = 'department';
    case INSTITUTE = 'institute';
    case SECRETARY = 'secretary';
    case CENTER = 'center';

    /**
     * Obtiene todos los valores como array para migraciones
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Etiquetas legibles para humanos
     */
    public function label(): string
    {
        return match($this) {
            self::DEPARTMENT => 'Departamento',
            self::INSTITUTE => 'Instituto',
            self::SECRETARY => 'Secretaría',
            self::CENTER => 'Centro',
        };
    }

}
