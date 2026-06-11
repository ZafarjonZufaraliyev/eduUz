<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class C1TikuvchilikSeeder extends Seeder
{
    public function run(): void
    {
        $tillar = Category::firstOrCreate(['name' => 'Tillar']);
        $kasb   = Category::firstOrCreate(['name' => 'Kasb-hunar']);

        // C1 Ingliz tili kursi
        $c1 = Course::firstOrCreate(
            ['slug' => 'ingliz-tili-c1'],
            [
                'title'       => 'Ingliz tili C1 — Ilg\'or daraja',
                'description' => 'C1 darajadagi ingliz tili kursi: murakkab grammatika, akademik yozuv, tinglab tushunish va erkin suhbat. IELTS/TOEFL tayyorgarligiga mos.',
                'price'       => 350000,
                'level'       => 'advanced',
                'thumbnail'   => null,
                'category_id' => $tillar->id,
                'teacher_id'  => 4,
            ]
        );

        Lesson::firstOrCreate(
            ['course_id' => $c1->id, 'title' => 'Kirish: C1 darajasi nima va nima o\'rganamiz?'],
            [
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content'   => "C1 darajasi — bu tilni deyarli ona tili sifatida ishlatish imkoniyati.\n\nBu darsda:\n- C1 va B2 orasidagi farq\n- Kurs rejalari va maqsadlar\n- IELTS/TOEFL uchun foydali maslahatlar",
                'order'     => 1,
                'is_free'   => true,
            ]
        );

        // Tikuvchilik kursi
        $tikuvchilik = Course::firstOrCreate(
            ['slug' => 'tikuvchilik-asoslari'],
            [
                'title'       => 'Tikuvchilik — Boshlang\'ichdan Profesionalgacha',
                'description' => 'Tikuvchilik san\'atini o\'rganing: mato tanlash, andoza yasash, tikuv mashinasida ishlash va zamonaviy kiyim tikish. Amaliy mashg\'ulotlar bilan.',
                'price'       => 280000,
                'level'       => 'beginner',
                'thumbnail'   => null,
                'category_id' => $kasb->id,
                'teacher_id'  => 5,
            ]
        );

        $tikuvchilikhDarslari = [
            [
                'title'   => 'Kirish: tikuv mashinasi bilan tanishish',
                'content' => "Tikuv mashinasining asosiy qismlari:\n- Ip o'tkazish tartibi\n- Tezlik boshqaruvi\n- Oddiy chok turlari",
                'order'   => 1,
                'is_free' => true,
            ],
            [
                'title'   => 'Mato turlari va ularni tanlash',
                'content' => "Asosiy mato turlari:\n- Paxta (cotton)\n- Ipak\n- Sintetik matolar\n\nQaysi kiyim uchun qaysi mato mos keladi.",
                'order'   => 2,
                'is_free' => false,
            ],
            [
                'title'   => 'Andoza yasash asoslari',
                'content' => "O'lcham olish:\n- Bel, ko'krak, bo'y o'lchamlari\n- Andoza chizish usullari\n- Mato kesish texnikasi",
                'order'   => 3,
                'is_free' => false,
            ],
        ];

        foreach ($tikuvchilikhDarslari as $dars) {
            Lesson::firstOrCreate(
                ['course_id' => $tikuvchilik->id, 'title' => $dars['title']],
                array_merge($dars, ['course_id' => $tikuvchilik->id])
            );
        }

        $this->command->info('C1 kursi: ' . $c1->title . ' (1 dars)');
        $this->command->info('Tikuvchilik kursi: ' . $tikuvchilik->title . ' (3 dars)');
    }
}
