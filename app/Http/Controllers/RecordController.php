<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RecordRequest;
use App\Models\User;
use App\Models\Worker;
use App\Models\Record;
use App\Models\Post;

class RecordController extends Controller
{
     // 1. Добавление записи
     public function store(RecordRequest $request)
{
    $data = $request->validated();

    // Проверка наличия worker_id и его соответствия post_id
    if (isset($data['worker_id'])) {
        $worker = Worker::where('id', $data['worker_id'])->first();
        if (!$worker || $worker->post_id !== Post::where('name', 'Barber')->first()->id) {
            return response()->json(['error' => 'Работник не найден или не имеет должности Barber.'], 404);
        }
    }

    // Если user_id передан, заполняем поля из таблицы users
    if (isset($data['user_id'])) {
        $user = User::find($data['user_id']);
        if ($user) {
            $data['user_name'] = $user->name;
            $data['user_phone'] = $user->phone;
            $data['user_email'] = $user->email;
        }
    }

    // Создаем запись
    $record = Record::create($data);

    return response()->json($record, 201);
}

 
     // 2. Редактирование записи
     public function update(RecordRequest $request, $id)
     {
         $data = $request->validated();
         $record = Record::findOrFail($id);
     
         // Проверка наличия worker_id и его соответствия post_id
         if (isset($data['worker_id'])) {
             $worker = Worker::where('id', $data['worker_id'])->first();
             if (!$worker || $worker->post_id !== Post::where('name', 'Barber')->first()->id) {
                 return response()->json(['error' => 'Работник не найден или не имеет должности Barber.'], 404);
             }
         }
     
         // Если user_id передан, заполняем поля из таблицы users
         if (isset($data['user_id'])) {
             $user = User::find($data['user_id']);
             if ($user) {
                 $data['user_name'] = $user->name;
                 $data['user_phone'] = $user->phone;
                 $data['user_email'] = $user->email;
             }
         }
     
         // Обновляем запись
         $record->update($data);
     
         return response()->json($record);
     }
 
     // 3. Получение записи по id +
     public function show($id)
     {
        $record = Record::find($id);
        if (!$record) {
            return response()->json(['error' => 'Запись не найдена.'], 404);
        }

        return response()->json($record);
     }
 
     // 4. Удаление записи +
     public function destroy($id)
     {
        $record = Record::find($id);
        if (!$record) {
            return response()->json(['error' => 'Запись не найдена.'], 404);
        }

        $record->delete();
        return response()->json(['message' => 'Запись успешно удалена.']);
     }
 
     // 5. Получение всех записей +
     public function index()
     {
         $records = Record::all();
         return $this->successResponse(
            $this->paginate(
                collect(
                    $records,
                )
                    ->toArray()
            )
        );
     }

     public function BarberRecords($id)
{
    try {
        // Получаем ID поста "Barber"
        $barberPost = Post::where('name', 'Barber')->first();

        if (!$barberPost) {
            return $this->errorResponse('Barber post not found', 404);
        }

        $barberPostId = $barberPost->id;

        // Проверяем, существует ли работник с переданным ID и постом "Barber"
        $worker = Worker::where('id', $id)->where('post_id', $barberPostId)->first();

        if (!$worker) {
            return $this->errorResponse('Worker not found or does not have the Barber post', 404);
        }

        // Получаем записи, соответствующие этому работнику, и сортируем по дате
        $records = Record::where('worker_id', $id)
            ->orderBy('date') // Сортировка по дате
            ->orderBy('time') // Дополнительная сортировка по времени, если необходимо
            ->get();

        if ($records->isEmpty()) {
            return $this->successResponse([], 'No records found for this worker');
        }

        return $this->successResponse($records);
    } catch (\Exception $e) {
        // Логируем ошибку для дальнейшего анализа
        \Log::error('Error fetching barber records: ' . $e->getMessage());

        return $this->errorResponse('An error occurred while fetching records', 500);
    }
}
}
