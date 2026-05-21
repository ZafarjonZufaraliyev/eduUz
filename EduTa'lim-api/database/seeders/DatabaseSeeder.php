<?php

namespace Database\Seeders;

use App\Enums\CourseLevel;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── Users ─────────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@edutalim.uz'],
            ['name' => 'Admin', 'password' => 'admin123', 'role' => 'admin']
        );

        $teacher1 = User::firstOrCreate(
            ['email' => 'teacher@edutalim.uz'],
            ['name' => 'Abdulloh Karimov', 'password' => 'teacher123', 'role' => 'teacher']
        );

        $teacher2 = User::firstOrCreate(
            ['email' => 'teacher2@edutalim.uz'],
            ['name' => 'Jasur Toshmatov', 'password' => 'teacher123', 'role' => 'teacher']
        );

        $teacher3 = User::firstOrCreate(
            ['email' => 'teacher3@edutalim.uz'],
            ['name' => 'Dilnoza Yusupova', 'password' => 'teacher123', 'role' => 'teacher']
        );

        User::firstOrCreate(
            ['email' => 'student@edutalim.uz'],
            ['name' => 'Ali Valiyev', 'password' => 'student123', 'role' => 'student']
        );

        // ── Categories ────────────────────────────────────────────────────────
        $frontend = Category::firstOrCreate(['slug' => 'frontend'], ['name' => 'Frontend']);
        $backend  = Category::firstOrCreate(['slug' => 'backend'],  ['name' => 'Backend']);
        $devops   = Category::firstOrCreate(['slug' => 'devops'],   ['name' => 'DevOps']);
        $mobile   = Category::firstOrCreate(['slug' => 'mobile'],   ['name' => 'Mobile']);
        $uiux     = Category::firstOrCreate(['slug' => 'ui-ux'],    ['name' => 'UI/UX']);

        // ── Courses ───────────────────────────────────────────────────────────
        $vue = Course::firstOrCreate(['slug' => 'vuejs-3-boshlangichdan-professional'], [
            'title'       => "Vue.js 3 — Boshlang'ichdan Professional",
            'description' => "Ushbu kurs Vue.js 3 ni noldan professional darajada o'rganishga mo'ljallangan. Composition API, Pinia, Vue Router va real loyihalar orqali zamonaviy frontend dasturlashni o'zlashtiring. Kurs oxirida to'liq ishlayotgan SPA dastur yaratgan bo'lasiz.",
            'price'       => 299000,
            'level'       => CourseLevel::Intermediate,
            'category_id' => $frontend->id,
            'teacher_id'  => $teacher1->id,
        ]);

        $python = Course::firstOrCreate(['slug' => 'python-full-course-loyihali'], [
            'title'       => "Python Full Course — Loyihali o'qitish",
            'description' => "Python dasturlash tilini noldan o'rganib real loyihalar yarating. Data science, web scraping va avtomatlashtirish bo'yicha amaliy bilim oling.",
            'price'       => 249000,
            'level'       => CourseLevel::Beginner,
            'category_id' => $backend->id,
            'teacher_id'  => $teacher2->id,
        ]);

        $react = Course::firstOrCreate(['slug' => 'react-18-typescript-tuliq-kurs'], [
            'title'       => "React 18 + TypeScript — To'liq kurs",
            'description' => "React 18 va TypeScript bilan zamonaviy web ilovalar yarating. Hooks, Context API, Redux Toolkit va Next.js asoslarini o'rganing.",
            'price'       => 349000,
            'level'       => CourseLevel::Intermediate,
            'category_id' => $frontend->id,
            'teacher_id'  => $teacher3->id,
        ]);

        $docker = Course::firstOrCreate(['slug' => 'docker-kubernetes-amaliyotda'], [
            'title'       => 'Docker & Kubernetes — Amaliyotda',
            'description' => "Docker va Kubernetes bilan containerization va orchestration ni o'rganing. CI/CD pipeline yaratib production-grade deployment qilishni o'zlashtiring.",
            'price'       => 319000,
            'level'       => CourseLevel::Advanced,
            'category_id' => $devops->id,
            'teacher_id'  => $teacher1->id,
        ]);

        $laravel = Course::firstOrCreate(['slug' => 'laravel-restful-api-zamonaviy-backend'], [
            'title'       => "Laravel RESTful API — Zamonaviy Backend",
            'description' => "Laravel bilan professional REST API yaratishni o'rganing. Authentication, authorization, file upload va real loyihalar orqali tajriba oling.",
            'price'       => 279000,
            'level'       => CourseLevel::Intermediate,
            'category_id' => $backend->id,
            'teacher_id'  => $teacher1->id,
        ]);

        $flutter = Course::firstOrCreate(['slug' => 'flutter-ios-android-ilovalar'], [
            'title'       => "Flutter — iOS va Android ilovalar",
            'description' => "Flutter bilan cross-platform mobil ilovalar yarating. Dart tili, Widget tizimi va state management ni o'rganing.",
            'price'       => 299000,
            'level'       => CourseLevel::Beginner,
            'category_id' => $mobile->id,
            'teacher_id'  => $teacher2->id,
        ]);

        $figma = Course::firstOrCreate(['slug' => 'figma-ui-ux-dizayn-asoslari'], [
            'title'       => "Figma bilan UI/UX Dizayn asoslari",
            'description' => "Figma dasturida professional UI/UX dizayn yaratishni o'rganing. User research, wireframing, prototyping va design system quring.",
            'price'       => 199000,
            'level'       => CourseLevel::Beginner,
            'category_id' => $uiux->id,
            'teacher_id'  => $teacher3->id,
        ]);

        $node = Course::firstOrCreate(['slug' => 'nodejs-express-backend'], [
            'title'       => "Node.js + Express — Backend dasturlash",
            'description' => "Node.js va Express bilan RESTful API yarating. JWT autentifikatsiya, MongoDB va real-time ilovalar yaratishni o'rganing.",
            'price'       => 259000,
            'level'       => CourseLevel::Intermediate,
            'category_id' => $backend->id,
            'teacher_id'  => $teacher2->id,
        ]);

        // ── Lessons ───────────────────────────────────────────────────────────
        $this->seedLessons($vue->id, [
            ["Kursga kirish va reja",              "Kurs bo'yicha umumiy ma'lumot va maqsadlar",                 1],
            ["Node.js va Vite o'rnatish",          "Muhitni sozlash va birinchi loyiha yaratish",                2],
            ["Birinchi Vue ilovasi",               "Hello World yaratish va fayl tuzilmasini tushunish",         3],
            ["Composition API — ref va reactive",  "Reaktivlik asoslari va ma'lumot boshqaruvi",                 4],
            ["computed va watch",                  "Hisoblangan xususiyatlar va kuzatuvchilar",                  5],
            ["Template syntax",                    "v-if, v-for, v-model va v-bind bilan ishlash",              6],
            ["Props va Emits",                     "Komponentlar o'rtasida ma'lumot uzatish",                   7],
            ["Vue Router asoslari",                "SPA routing va navigation guards",                          8],
            ["Pinia bilan state management",       "Global holat boshqaruvi va store yaratish",                 9],
            ["Axios bilan API integratsiya",       "Backend bilan bog'lanish va ma'lumot olish",               10],
            ["Real loyiha — EduTa'lim klon",       "Barcha bilimlarni amalda qo'llash",                        11],
            ["Deploy — Vercel",                    "Ilovani production'ga chiqarish",                           12],
        ]);

        $this->seedLessons($python->id, [
            ["Python o'rnatish va IDE sozlash",    "Muhit tayyorlash va birinchi dastur",                        1],
            ["O'zgaruvchilar va ma'lumot turlari", "int, str, list, dict, tuple, set",                          2],
            ["Shartlar va tsikllar",               "if/else, for, while boshqaruv oqimi",                       3],
            ["Funksiyalar",                        "def, args, kwargs va lambda funksiyalar",                   4],
            ["OOP — Sinflar va meros",             "Class, __init__, inheritance asoslari",                     5],
            ["Fayllar bilan ishlash",              "open, read, write va JSON ishlash",                         6],
            ["Kutubxonalar — requests, pandas",    "Tashqi kutubxonalar bilan ishlash",                         7],
            ["Mini loyiha — Telegram bot",         "Python bilan bot yaratish",                                 8],
        ]);

        $this->seedLessons($react->id, [
            ["React nima va nima uchun?",          "Frontend ekotizimi va React tarixi",                        1],
            ["JSX va komponentlar",                "Funksional komponentlar va JSX sintaksis",                  2],
            ["useState va useEffect",              "Asosiy hooklar va lifecycle",                                3],
            ["TypeScript asoslari",                "Tiplar, interface, generic",                                 4],
            ["React bilan TypeScript",             "Typed komponentlar va props",                               5],
            ["Context API",                        "Global state useContext bilan",                              6],
            ["React Router v6",                    "Client-side routing va nested routes",                      7],
            ["API integratsiya — axios",           "Backend bilan bog'lanish",                                  8],
            ["Deploy — Vercel",                    "Ilovani chiqarish",                                         9],
        ]);

        $this->seedLessons($docker->id, [
            ["Docker nima?",                       "Containerization tushunchasi va afzalliklari",              1],
            ["Dockerfile yozish",                  "Image yaratish va best practices",                          2],
            ["Docker Compose",                     "Multi-container ilovalar boshqaruvi",                       3],
            ["Docker Hub va registry",             "Imagelarni saqlash va ulashish",                            4],
            ["Kubernetes asoslari",                "Orchestration va cluster tushunchasi",                      5],
            ["Kubernetes Deployment va Service",   "Ilovani deploy qilish va expose qilish",                   6],
            ["CI/CD — GitHub Actions",             "Avtomatik build va deploy pipeline",                        7],
            ["Production monitoring",              "Logging, metrics va alerting",                              8],
        ]);

        $this->seedLessons($laravel->id, [
            ["Laravel o'rnatish va MVC",           "Framework arxitekturasi va asoslar",                        1],
            ["Routing va Controller",              "HTTP so'rovlarni boshqarish",                               2],
            ["Eloquent ORM va migrations",         "Database bilan ishlash",                                    3],
            ["Authentication — Sanctum",           "Token asosidagi autentifikatsiya",                          4],
            ["Form validation va Request",         "Ma'lumotlarni tekshirish",                                  5],
            ["File upload va Storage",             "Fayllarni saqlash va boshqarish",                           6],
            ["API Resources",                      "JSON javoblarni formatlash",                                7],
            ["Testing",                            "Unit va Feature testlar yozish",                            8],
        ]);

        $this->seedLessons($flutter->id, [
            ["Flutter o'rnatish va Hello World",  "SDK o'rnatish va birinchi ilova",                           1],
            ["Dart tili asoslari",                "Variables, functions, classes",                              2],
            ["Widget tizimi",                     "StatelessWidget va StatefulWidget",                          3],
            ["Layout widgetlar",                  "Column, Row, Stack, Container",                              4],
            ["Navigation",                        "Sahifalar o'rtasida o'tish",                                5],
            ["State management — Provider",       "Global holatni boshqarish",                                  6],
            ["HTTP so'rovlar",                    "Backend bilan integratsiya",                                 7],
            ["Build va Deploy",                   "APK va IPA yaratish",                                        8],
        ]);

        $this->seedLessons($figma->id, [
            ["Figma interfeysi",                  "Asosiy vositalar va muhit",                                  1],
            ["Frames va Layout",                  "Auto layout va constraints",                                 2],
            ["Komponentlar",                      "Reusable komponentlar yaratish",                             3],
            ["Ranglar va tipografiya",            "Design token va uslub qo'llanma",                           4],
            ["Prototiplash",                      "Interaktiv prototip yaratish",                               5],
            ["Design Handoff",                    "Developerlar uchun spesifikatsiya",                          6],
        ]);

        $this->seedLessons($node->id, [
            ["Node.js asoslari",                  "Event loop va asenkron dasturlash",                         1],
            ["Express.js",                        "Server yaratish va routing",                                 2],
            ["Middleware",                        "Express middleware tushunchasi",                             3],
            ["MongoDB va Mongoose",               "NoSQL database bilan ishlash",                              4],
            ["JWT autentifikatsiya",              "Token asosidagi auth tizimi",                               5],
            ["File upload — Multer",              "Fayllarni server'ga yuklash",                               6],
            ["Socket.io",                         "Real-time aloqa yaratish",                                  7],
            ["Deploy — Railway",                  "Node.js ilovasini production'ga chiqarish",                 8],
        ]);
    }

    private function seedLessons(int $courseId, array $lessons): void
    {
        foreach ($lessons as [$title, $content, $order]) {
            Lesson::firstOrCreate(
                ['course_id' => $courseId, 'order' => $order],
                ['title' => $title, 'content' => $content, 'video_url' => null]
            );
        }
    }
}
