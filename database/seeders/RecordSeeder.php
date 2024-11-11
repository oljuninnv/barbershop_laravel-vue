<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        // Уникальные записи
        $recordServices = [];

        // Генерируем уникальные записи
        $uniqueDatesTimes = [];
        $startDate = Carbon::now()->startOfWeek(); // Начало текущей недели

        // Генерация записей для текущей недели (7-12 записей)
        $this->generateRecordsForWeek($barberWorkerIds, $userIds, $availableTimes, $uniqueDatesTimes, $startDate, rand(7, 12));

        // Генерация записей для следующей недели (10-14 записей)
        $this->generateRecordsForWeek($barberWorkerIds, $userIds, $availableTimes, $uniqueDatesTimes, $startDate->copy()->addWeek(), rand(10, 14));

        // Генерация записей для оставшихся дней до конца месяца
        $remainingDays = Carbon::now()->endOfMonth()->diffInDays($startDate->copy()->addWeeks(2));
        $totalRecords = 30; // Всего записей для каждого барбера
        $recordsToGenerate = $totalRecords - (count($uniqueDatesTimes) / count($barberWorkerIds)); // Оставшиеся записи

        for ($i = 0; $i < $recordsToGenerate; $i++) {
            // Генерируем случайную дату в оставшиеся дни месяца
            $date = Carbon::now()->addDays(rand(1, $remainingDays))->format('Y-m-d');
            $time = $availableTimes[array_rand($availableTimes)];

            // Проверяем уникальность комбинации даты и времени
            while (in_array("$date $time", $uniqueDatesTimes)) {
                $time = $availableTimes[array_rand($availableTimes)]; // Перебираем время, пока не найдем уникальное
            }

            $uniqueDatesTimes[] = "$date $time"; // Добавляем уникальную комбинацию

            // Выбираем случайного работника
            $workerId = $barberWorkerIds->random();

            // Создаем запись в таблице records
            $recordId = DB::table('records')->insertGetId([
                'user_id' => $userIds->random(), // Случайный пользователь
                'worker_id' => $workerId,
                'date' => $date,
                'time' => $time,
                'user_name' => 'Пользователь ' . $userIds->random(),
                'user_phone' => '79001234567',
                'user_email' => 'user' . $userIds->random() . '@example.com',
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

    private function generateRecordsForWeek($barberWorkerIds, $userIds, $availableTimes, &$uniqueDatesTimes, $startDate, $recordCount)
    {
        for ($i = 0; $i < $recordCount; $i++) {
            // Генерируем уникальную дату и время
            $date = $startDate->copy()->addDays(rand(0, 6))->format('Y-m-d'); // Случайный день недели
            $time = $availableTimes[array_rand($availableTimes)];

            // Проверяем уникальность комбинации даты и времени
            while (in_array("$date $time", $uniqueDatesTimes)) {
                $time = $availableTimes[array_rand($availableTimes)]; // Перебираем время, пока не найдем уникальное
            }

            $uniqueDatesTimes[] = "$date $time"; // Добавляем уникальную комбинацию

            // Выбираем случайного работника
            $workerId = $barberWorkerIds->random();

            // Создаем запись в таблице records
            $recordId = DB::table('records')->insertGetId([
                'user_id' => $userIds->random(), // Случайный пользователь
                'worker_id' => $workerId,
                'date' => $date,
                'time' => $time,
                'user_name' => 'Пользователь ' . $userIds->random(),
                'user_phone' => '79001234567',
                'user_email' => 'user' . $userIds->random() . '@example.com',
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
    }
}