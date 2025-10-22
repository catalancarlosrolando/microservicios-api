<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use App\Enums\PostType;
use App\Models\Channel;
use App\Enums\PostStatus;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener usuarios
        $adminUser = User::role('admin')->first();
        $regularUsers = User::role('user')->limit(5)->get();

        // 2. Validar usuarios
        if (!$adminUser || $regularUsers->isEmpty()) {
            $this->command->warn('No users found...');
            return;
        }

        // 3. Obtener canales y medios
        $channels = Channel::all();
        $medias = Media::where('is_active', true)->get();

        // 4. Validar canales
        if ($channels->isEmpty()) {
            $this->command->warn('No channels found...');
            return;
        }

        // 5. Definir array de 11 posts

        $posts = [
            [
                'user_id' => $adminUser->id,
                'name' => 'Taller: Estrategias de investigación 2025',
                'content' => 'Invitamos a la comunidad académica al taller sobre metodologías y estrategias de investigación aplicada. Se compartirán buenas prácticas y herramientas.',
                'type' => PostType::TEXT->value,
                'status' => PostStatus::APPROVED_BY_MODERATOR->value,
                'moderator_comments' => 'Contenido pertinente y aprobado.',
                'scheduled_at' => Carbon::now()->addDays(3),
                'published_at' => null,
                'deadline' => Carbon::now()->addMonths(2),
                'timeout' => Carbon::now()->addMonths(3),
            ],
            [
                'user_id' => $adminUser->id,
                'name' => 'Convocatoria de becas 2026',
                'content' => 'Apertura de la convocatoria de becas para estudiantes de posgrado. Requisitos, plazos y proceso de postulación disponibles en el enlace oficial.',
                'type' => PostType::TEXT->value,
                'status' => PostStatus::DRAFT->value,
                'moderator_comments' => 'Se deben cambiar contenidos bibliograficos.',
                'scheduled_at' => Carbon::now()->addDays(7),
                'published_at' => null,
                'deadline' => Carbon::now()->addMonths(6),
                'timeout' => Carbon::now()->addMonths(9),
            ],
            [
                'user_id' => $adminUser->id,
                'name' => 'Llamado a ponencias - Revista Institucional',
                'content' => 'La Revista Institucional abre el llamado a ponencias para su próximo número. Se buscan artículos originales en áreas de ciencia y educación.',
                'type' => PostType::TEXT->value,
                'status' => PostStatus::SCHEDULED->value,
                'moderator_comments' => 'Programado para aprobacion.',
                'scheduled_at' => Carbon::now()->addDays(1),
                'published_at' => null,
                'deadline' => Carbon::now()->addDays(20),
                'timeout' => Carbon::now()->addDays(40),
            ],
            [
                'user_id' => $adminUser->id,
                'name' => 'Recordatorio: cierre de inscripciones',
                'content' => 'Recordamos a los interesados que el cierre de inscripciones para el curso intensivo es este viernes. No pierdas la oportunidad de participar.',
                'type' => PostType::TEXT->value,
                'status' => PostStatus::APPROVED_BY_MODERATOR->value,
                'moderator_comments' => 'Aprobado — publicar de inmediato.',
                'scheduled_at' => Carbon::now(),
                'published_at' => Carbon::now()->addHours(1),
                'deadline' => Carbon::now()->addDays(5),
                'timeout' => Carbon::now()->addWeeks(2),
            ],
            [
                'user_id' => $adminUser->id,
                'name' => 'Streaming: Presentación de proyectos',
                'content' => 'Transmisión en vivo de la jornada de presentación de proyectos estudiantiles. Conéctate para ver las demos y hacer preguntas en tiempo real.',
                'type' => PostType::TEXT->value,
                'status' => PostStatus::DRAFT->value,
                'moderator_comments' => 'Incluye enlace de streaming cuando esté disponible.',
                'scheduled_at' => Carbon::now()->addDays(5),
                'published_at' => null,
                'deadline' => Carbon::now()->addMonths(1),
                'timeout' => Carbon::now()->addMonths(2),
            ],
        ];

        // 6. Procesar cada post
        foreach ($posts as $postData) {
            $post = Post::firstOrCreate(
                ['name' => $postData['name'], 'user_id' => $postData['user_id']],
                $postData
            );

            // 7. Establecer relaciones N:M
            if ($post->wasRecentlyCreated) {
                // Canales (1-3)
                $channelCount = $channels->count();
                if ($channelCount > 0) {
                    $take = rand(1, min(3, $channelCount));
                    $selectedChannelIds = $channels->random($take)->pluck('id')->toArray();
                    // Ajusta el nombre de la relación si tu Post model usa otro
                    $post->channels()->syncWithoutDetaching($selectedChannelIds);
                }

                // Medios (1-4)
                $mediaCount = $medias->count();
                if ($mediaCount > 0) {
                    $takeMedias = rand(1, min(4, $mediaCount));
                    $selectedMediaIds = $medias->random($takeMedias)->pluck('id')->toArray();
                    // Ajusta el nombre de la relación si tu Post model usa otro
                    $post->medias()->syncWithoutDetaching($selectedMediaIds);
                }
            }
        }

        $this->command->info('Posts seeded successfully with relationships!');
    }
}
