<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Worker;
use App\Models\Record;
use App\Models\Post;
class GenerateRecordController extends Controller
{
    /**
     * Генерация записей в таблицу Record из полученного списка времени.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(Request $request)
    {
        try {
            // Валидация входящих данных
            $request->validate([
                'worker_id' => 'required|integer|exists:workers,id',
                'date' => 'required|date|before_or_equal:today',
                'times' => 'required|array',
                'times.*' => 'date_format:H:i',
            ]);

            // Проверка, что worker_id имеет соответствующий post_id в таблице Post Barber
            $worker = Worker::findOrFail($request->input('worker_id'));
            if ($worker->post_id !== 'Barber') { // Предполагается, что 'barber' - это значение post_id
                return response()->json([
                    'message' => 'Ошибка: данный пользователь не является барбером',
                ], 422);
            }

            $workerId = $request->input('worker_id');
            $date = $request->input('date');
            $times = $request->input('times');

            $records = [];

            foreach ($times as $time) {
                $records[] = [
                    'worker_id' => $workerId,
                    'date' => $date,
                    'time' => $time,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Вставка записей в базу данных
            Record::insert($records);

            return response()->json(['message' => 'Записи успешно созданы.', 'records' => $records], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Ошибка валидации.',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Произошла ошибка.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

