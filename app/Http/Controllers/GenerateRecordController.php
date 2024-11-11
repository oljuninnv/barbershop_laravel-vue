<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Worker;
use App\Models\Record;
use App\Models\Post;
class GenerateRecordController extends Controller
{
    public function generate(Request $request)
{
    try {
        // Валидация входящих данных
        $request->validate([
            'worker_id' => 'required|integer|exists:workers,id',
            'date' => 'required|date|after_or_equal:today',
            'times' => 'required|array',
            'times.*' => 'date_format:H:i',
        ]);

        // Получаем ID поста "Barber"
        $barberPost = Post::where('name', 'Barber')->first();

        if (!$barberPost) {
            return response()->json([
                'message' => 'Ошибка: пост "Barber" не найден',
            ], 404);
        }

        $worker = Worker::findOrFail($request->input('worker_id'));

        // Сравниваем post_id работника с id поста "Barber"
        if ($worker->post_id !== $barberPost->id) {
            return response()->json([
                'message' => 'Ошибка: данный пользователь не является барбером',
            ], 422);
        }

        $workerId = $request->input('worker_id');
        $date = $request->input('date');
        $times = $request->input('times');

        $records = [];

        foreach ($times as $time) {
            // Проверяем, существует ли запись на эту дату и время
            $existingRecord = Record::where('worker_id', $workerId)
                ->where('date', $date)
                ->where('time', $time)
                ->first();

            // Если запись существует, пропускаем её
            if ($existingRecord) {
                continue;
            }

            // Если записи нет, добавляем в массив для вставки
            $records[] = [
                'worker_id' => $workerId,
                'date' => $date,
                'time' => $time,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Вставка записей в базу данных, если есть новые записи
        if (!empty($records)) {
            Record::insert($records);
        }

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

