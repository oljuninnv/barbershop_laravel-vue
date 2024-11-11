<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'name' => 'Мужская стрижка',
                'image' => '/public/img/vzroslay.svg',
                'price' => 1500.00,
                'execution_time' => '01:30:00', // 30 минут
            ],
            [
                'name' => 'Укладка причёски',
                'image' => '/public/img/styling.svg',
                'price' => 1000.00,
                'execution_time' => '00:20:00', // 20 минут
            ],
            [
                'name' => 'Стрижка бороды',
                'image' => '/public/img/beard.svg',
                'price' => 800.00,
                'execution_time' => '00:40:00', // 15 минут
            ],
            [
                'name' => 'Бритьё шеи',
                'image' => '/public/img/knife.svg',
                'price' => 500.00,
                'execution_time' => '00:10:00', // 10 минут
            ],
            [
                'name' => 'Увлажнение головы',
                'image' => '/public/img/losion.svg',
                'price' => 300.00,
                'execution_time' => '00:15:00', // 15 минут
            ],
            [
                'name' => 'Уход за бородой',
                'image' => '/public/img/Barbershop.svg',
                'price' => 1500.00,
                'execution_time' => '00:30:00', // 30 минут
            ],
        ];

        DB::table('services')->insert($services);
    }
}