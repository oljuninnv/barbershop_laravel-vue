<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Создаем массив пользователей
        $users = [
            [
                'login' => 'ivan_ivanov',
                'name' => 'Иван Иванов',
                'phone' => '1234567890',
                'email' => 'ivan@example.com',
                'city' => 'Москва',
                'birthday' => '1990-01-01',
                'image' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'login' => 'petr_petrov',
                'name' => 'Петр Петров',
                'phone' => '0987654321',
                'email' => 'petr@example.com',
                'city' => 'Санкт-Петербург',
                'birthday' => '1985-05-05',
                'image' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'login' => 'sergey_sergeev',
                'name' => 'Сергей Сергеев',
                'phone' => '2233445566',
                'email' => 'sergey@example.com',
                'city' => 'Казань',
                'birthday' => '1988-07-20',
                'image' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'login' => 'dmitry_dmitriev',
                'name' => 'Дмитрий Дмитриев',
                'phone' => '3344556677',
                'email' => 'dmitry@example.com',
                'city' => 'Челябинск',
                'birthday' => '1983-11-11',
                'image' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'login' => 'alexey_alexeev',
                'name' => 'Алексей Алексеев',
                'phone' => '4455667788',
                'email' => 'alexey@example.com',
                'city' => 'Волгоград',
                'birthday' => '1987-04-04',
                'image' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'login' => 'nikolay_nikolayev',
                'name' => 'Николай Николаев',
                'phone' => '5566778899',
                'email' => 'nikolay@example.com',
                'city' => 'Саратов',
                'birthday' => '1980-02-02',
                'image' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'login' => 'andrey_andreev',
                'name' => 'Андрей Андреев',
                'phone' => '6677889900',
                'email' => 'andrey@example.com',
                'city' => 'Уфа',
                'birthday' => '1992-06-06',
                'image' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'login' => 'vladimir_vladimirov',
                'name' => 'Владимир Владимиров',
                'phone' => '7788990011',
                'email' => 'vladimir@example.com',
                'city' => 'Тюмень',
                'birthday' => '1989-08-08',
                'image' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'login' => 'maxim_maximov',
                'name' => 'Максим Максимов',
                'phone' => '8899001122',
                'email' => 'maxim@example.com',
                'city' => 'Калуга',
                'birthday' => '1993-10-10',
                'image' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'login' => 'oleg_olegov',
                'name' => 'Олег Олегов',
                'phone' => '9900112233',
                'email' => 'oleg@example.com',
                'city' => 'Воронеж',
                'birthday' => '1991-12-12',
                'image' => null,
                'password' => Hash::make('password123'),
            ],
        ];

        // Вставляем пользователей в таблицу users
        DB::table('users')->insert($users);

        // Получаем ID всех пользователей
        $userIds = DB::table('users')->pluck('id')->toArray();

        // Должности, которые будут назначены
        $postIds = DB::table('posts')->pluck('id')->toArray();

        // Количество работников, которых мы хотим создать
        $numberOfWorkers = min(count($userIds), count($postIds)); // Не больше, чем пользователей или должностей

        // Перемешиваем массив пользователей для случайного выбора
        shuffle($userIds);

        // Создаем работников для случайных пользователей
        for ($i = 0; $i < $numberOfWorkers; $i++) {
            // Генерируем случайную дату между 2018-01-01 и 2024-12-31
            $startDate = strtotime('2018-01-01');
            $endDate = strtotime('2024-12-31');
            $randomTimestamp = mt_rand($startDate, $endDate);
            $randomDate = date('Y-m-d H:i:s', $randomTimestamp); // Форматируем дату

            DB::table('workers')->insert([
                'user_id' => $userIds[$i], // Берем случайного пользователя
                'adopted_at' => $randomDate, // Устанавливаем случайную дату
                'post_id' => $postIds[$i], // Назначаем должность
            ]);
        }
    }
}