<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Kategoriyalar
        $student = Category::create(['name' => "O'quvchi",    'slug' => 'student', 'color' => '#6366f1']);
        $teacher = Category::create(['name' => "O'qituvchi",  'slug' => 'teacher', 'color' => '#10b981']);
        $staff   = Category::create(['name' => 'Xodim',       'slug' => 'staff',   'color' => '#f59e0b']);

        // Sinflar
        $class9a = SchoolClass::create(['name' => '9-A sinf',        'category_id' => $student->id]);
        $class9b = SchoolClass::create(['name' => '9-B sinf',        'category_id' => $student->id]);
        $class10 = SchoolClass::create(['name' => '10-A sinf',       'category_id' => $student->id]);
        $tClass  = SchoolClass::create(['name' => "O'qituvchilar",   'category_id' => $teacher->id]);
        $sClass  = SchoolClass::create(['name' => 'Texnik xodimlar', 'category_id' => $staff->id]);

        // Admin
        $admin = User::create([
            'full_name' => 'Abdullayev Sardor',
            'username'  => 'admin',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
        ]);
        $admin->generateQrCodes();

        // Navbatchi (Guard)
        $guard = User::create([
            'full_name' => 'Nazarov Ulmas',
            'username'  => 'guard',
            'password'  => Hash::make('guard123'),
            'role'      => 'guard',
        ]);
        $guard->generateQrCodes();

        // O'quvchilar
        $students = [
            ['full_name' => 'Aliyev Jasur',      'username' => 'aliyev',    'class_id' => $class9a->id, 'parent_phone' => '+998909000101'],
            ['full_name' => 'Nazarova Dilnoza',  'username' => 'nazarova',  'class_id' => $class9a->id, 'parent_phone' => '+998909000202'],
            ['full_name' => 'Toshmatov Sherzod', 'username' => 'toshmatov', 'class_id' => $class9b->id, 'parent_phone' => '+998909000303'],
            ['full_name' => 'Xoliqova Feruza',   'username' => 'xoliqova',  'class_id' => $class10->id, 'parent_phone' => '+998909000404'],
        ];

        foreach ($students as $s) {
            $u = User::create(array_merge($s, [
                'password'    => Hash::make('pass123'),
                'role'        => 'member',
                'category_id' => $student->id,
                'phone'       => '+99890' . rand(1000000, 9999999),
            ]));
            $u->generateQrCodes();
        }

        // O'qituvchilar
        $teachers = [
            ['full_name' => 'Karimov Bobur',    'username' => 'karimov'],
            ['full_name' => 'Mirzayeva Gulnora', 'username' => 'mirzayeva'],
            ['full_name' => 'Umarov Sanjar',    'username' => 'umarov'],
        ];

        foreach ($teachers as $t) {
            $u = User::create(array_merge($t, [
                'password'    => Hash::make('pass123'),
                'role'        => 'member',
                'category_id' => $teacher->id,
                'class_id'    => $tClass->id,
                'phone'       => '+99890' . rand(1000000, 9999999),
            ]));
            $u->generateQrCodes();
        }

        // Xodimlar
        $u = User::create([
            'full_name'   => 'Xoliqov Mansur',
            'username'    => 'xoliqov',
            'password'    => Hash::make('pass123'),
            'role'        => 'member',
            'category_id' => $staff->id,
            'class_id'    => $sClass->id,
            'phone'       => '+998903000001',
        ]);
        $u->generateQrCodes();

        $this->command->info('✓ Ma\'lumotlar bazasi to\'ldirildi!');
        $this->command->info('Admin: admin / admin123');
        $this->command->info('Guard: guard / guard123');
        $this->command->info('A\'zolar: aliyev, nazarova, toshmatov, karimov... / pass123');
    }
}
