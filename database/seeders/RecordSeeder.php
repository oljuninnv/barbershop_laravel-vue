<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecordSeeder extends Seeder
{
    public function run()
    {
        // Получаем ID работников с должностью Barber
        $barberWorkerIds = DB::table('workers')
            ->where('post_id', DB::table('posts')->where('name', 'Barber')->value('id'))
            ->pluck('id');

        // Получаем ID пользователей
        $userIds = DB::table('users')->pluck('id');

        // Массив доступных времен
        $availableTimes = [
            '09:00:00',
            '10:40:00',
            '12:40:00',
            '14:20:00',
            '16:00:00',
            '17:40:00',
            '18:20:00',
            '19:50:00',
        ];

        // Создаем записи в таблице records
        $records = [];
        $recordServices = [];

        foreach ($userIds as $userId) {
            // Выбираем случайного работника
            $workerId = $barberWorkerIds->random();

            // Генерируем случайную дату
            $date = Carbon::now()->addDays(rand(1, 30))->format('Y-m-d');

            // Выбираем случайное время из массива
            $time = $availableTimes[array_rand($availableTimes)];

            // Создаем запись в таблице records
            $recordId = DB::table('records')->insertGetId([
                'user_id' => $userId,
                'worker_id' => $workerId,
                'date' => $date,
                'time' => $time,
                'user_name' => 'Пользователь ' . $userId,
                'user_phone' => '79001234567',
                'user_email' => 'user' . $userId . '@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Получаем ID всех услуг
            $serviceIds = DB::table('services')->pluck('id');

            // Создаем записи в таблице record_services
            foreach ($serviceIds as $serviceId) {
                $totalPrice = DB::table('services')->where('id', $serviceId)->value('price');
                $totalDuration = DB::table('services')->where('id', $serviceId)->value('execution_time');

                $recordServices[] = [
                    'record_id' => $recordId,
                    'service_id' => $serviceId,
                    'total_price' => $totalPrice,
                    'total_duration' => $totalDuration,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Вставляем записи в таблицу record_services
        DB::table('record_services')->insert($recordServices);
    }
}