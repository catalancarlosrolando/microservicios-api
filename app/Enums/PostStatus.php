<?php

namespace App\Enums;

enum PostStatus : string
{
    case DRAFT = 'draft';
    case APPROVED_BY_MODERATOR = 'approved_by_moderator';
    case SCHEDULED = 'scheduled';
    case ARCHIVED = 'archived';

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
            self::DRAFT => 'Borrador',
            self::APPROVED_BY_MODERATOR => 'Aprobado',
            self::SCHEDULED => 'Programado',
            self::ARCHIVED => 'Archivado',
        };
    }
}
