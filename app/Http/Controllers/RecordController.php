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
}
