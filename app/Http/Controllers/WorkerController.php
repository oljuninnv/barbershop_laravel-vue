<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use App\Models\Post;
use App\Http\Requests\WorkerRequest;
use App\Http\Requests\UpdateWorkerRequest;

class WorkerController extends Controller
{
    // 1. Получение всех работников
    public function index(Request $request)
    {
        $name = $request->get('name');

        if ($name) {
            $workers = Worker::where('name', 'like', "%$name%")->get();
        } else {
            $workers = Worker::all();
        }
        return $this->successResponse(
            $this->paginate(
                collect($workers)->toArray()
            )
        );
    }

    // 2. Получение определённого работника
    public function show($id)
    {
        $worker = Worker::find($id);
        if (!$worker) {
            return response()->json(['error' => 'Пользователь не найден.'], 404);
        }

        return response()->json($worker);
    }

    // 3. Получение всех работников с ролью Admin
    public function getAdmins()
{
    // Получаем пост с ролью 'Admin'
    $post = Post::where('name', 'Admin')->first();

    if (!$post) {
        return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
    }

    // Получаем работников с найденным post_id и загружаем информацию о пользователе
    $admins = Worker::with('user') // Предполагается, что в модели Worker есть связь с моделью User
        ->where('post_id', $post->id)
        ->get();

    // Преобразуем данные для ответа
    $adminsData = $admins->map(function ($worker) {
        return [
            'worker_id' => $worker->id,
            'work_experience' => $worker->work_experience,
            'user_id' => $worker->user_id,
            'user_name' => $worker->user ? $worker->user->name : null, // Получаем имя пользователя
            'user_email' => $worker->user ? $worker->user->email : null, // Получаем email пользователя
            'user_phone' => $worker->user ? $worker->user->phone : null, // Получаем phone пользователя
            'user_birthday' => $worker->user ? $worker->user->birthday : null, // Получаем birthday пользователя
            'user_login' => $worker->user ? $worker->user->login : null, // Получаем login пользователя
            'user_city' => $worker->user ? $worker->user->city : null, // Получаем login пользователя
        ];
    });

    return $this->successResponse(
        $this->paginate(
            $adminsData->toArray()
        )
    );
}

// 4. Получение всех работников с ролью Barber
public function getBarbers()
{
    // Получаем пост с ролью 'Admin'
    $post = Post::where('name', 'Barber')->first();

    if (!$post) {
        return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
    }

    // Получаем работников с найденным post_id и загружаем информацию о пользователе
    $admins = Worker::with('user') // Предполагается, что в модели Worker есть связь с моделью User
        ->where('post_id', $post->id)
        ->get();

    // Преобразуем данные для ответа
    $adminsData = $admins->map(function ($worker) {
        return [
            'worker_id' => $worker->id,
            'work_experience' => $worker->work_experience,
            'user_id' => $worker->user_id,
            'user_name' => $worker->user ? $worker->user->name : null, // Получаем имя пользователя
            'user_email' => $worker->user ? $worker->user->email : null, // Получаем email пользователя
            'user_phone' => $worker->user ? $worker->user->phone : null, // Получаем phone пользователя
            'user_birthday' => $worker->user ? $worker->user->birthday : null, // Получаем birthday пользователя
            'user_login' => $worker->user ? $worker->user->login : null, // Получаем login пользователя
            'user_city' => $worker->user ? $worker->user->city : null, // Получаем login пользователя
        ];
    });

    return $this->successResponse(
        $this->paginate(
            $adminsData->toArray()
        )
    );
}

    // 5. Получение всех работников с ролью Undefined
    public function getUndefined()
    {
        // Получаем пост с ролью 'Admin'
    $post = Post::where('name', 'Undefined')->first();

    if (!$post) {
        return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
    }

    // Получаем работников с найденным post_id и загружаем информацию о пользователе
    $admins = Worker::with('user') // Предполагается, что в модели Worker есть связь с моделью User
        ->where('post_id', $post->id)
        ->get();

    // Преобразуем данные для ответа
    $adminsData = $admins->map(function ($worker) {
        return [
            'worker_id' => $worker->id,
            'work_experience' => $worker->work_experience,
            'user_id' => $worker->user_id,
            'user_name' => $worker->user ? $worker->user->name : null, // Получаем имя пользователя
            'user_email' => $worker->user ? $worker->user->email : null, // Получаем email пользователя
            'user_phone' => $worker->user ? $worker->user->phone : null, // Получаем phone пользователя
            'user_birthday' => $worker->user ? $worker->user->birthday : null, // Получаем birthday пользователя
            'user_login' => $worker->user ? $worker->user->login : null, // Получаем login пользователя
            'user_city' => $worker->user ? $worker->user->city : null, // Получаем login пользователя
        ];
    });

    return $this->successResponse(
        $this->paginate(
            $adminsData->toArray()
        )
    );
    }

    // 6. Получение всех работников с ролью Staff
    public function getStaff()
    {
        // Получаем пост с ролью 'Admin'
    $post = Post::where('name', 'Staff')->first();

    if (!$post) {
        return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
    }

    // Получаем работников с найденным post_id и загружаем информацию о пользователе
    $admins = Worker::with('user') // Предполагается, что в модели Worker есть связь с моделью User
        ->where('post_id', $post->id)
        ->get();

    // Преобразуем данные для ответа
    $adminsData = $admins->map(function ($worker) {
        return [
            'worker_id' => $worker->id,
            'work_experience' => $worker->work_experience,
            'user_id' => $worker->user_id,
            'user_name' => $worker->user ? $worker->user->name : null, // Получаем имя пользователя
            'user_email' => $worker->user ? $worker->user->email : null, // Получаем email пользователя
            'user_phone' => $worker->user ? $worker->user->phone : null, // Получаем phone пользователя
            'user_birthday' => $worker->user ? $worker->user->birthday : null, // Получаем birthday пользователя
            'user_login' => $worker->user ? $worker->user->login : null, // Получаем login пользователя
            'user_city' => $worker->user ? $worker->user->city : null, // Получаем login пользователя
        ];
    });

    return $this->successResponse(
        $this->paginate(
            $adminsData->toArray()
        )
    );}

    // 7. Обновление определённого работника
    public function update(UpdateWorkerRequest $request, $id)
    {
        $worker = Worker::find($id);
        if (!$worker) {
            return response()->json(['error' => 'Пользователь не найден.'], 404);
        }

        $values = $request->all();

        if ($request->has('user_id')){
            $worker->user_id = $values['user_id'];
        }

        if ($request->has('work_experience')) {
            $worker->work_experience = $values['work_experience'];
        }

        if ($request->has('post_id')) {
            $worker->post_id = $values['post_id'];
        }

        $worker->save();

        return response()->json([
            'worker' => $worker,
            'message' => 'Успешно обновлено'
        ], 200);
    }

    // 8. Создание нового работника

    public function store(WorkerRequest $request)
    {
        $values = $request->all();

        $worker = new Worker();
        $worker->user_id = $values['user_id'];
        $worker->work_experience = $values['work_experience'];
        $worker->post_id = $values['post_id'];

        $worker->save();

        return response()->json(['worker' => $worker, 'message' => 'Работник успешно добавлен.']);
    }

    // 9. Удаление определённого работника
    public function destroy($id)
    {
        $worker = Worker::find($id);
        if (!$worker) {
            return response()->json(['error' => 'Пользователь не найден.'], 404);
        }

        $worker->delete();
        return response()->json(['message' => 'Работник успешно удалён.']);
    }

    // 4. Получение всех работников с ролью Barber
public function getBarbersForMainPage()
{
   // Находим пост с названием 'Barber'
   $post = Post::where('name', 'Barber')->first();

   if (!$post) {
       return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
   }

   // Получаем работников с найденным post_id и загружаем связанные данные из таблицы User
   $barbers = Worker::with('user:id,name,image') // Загружаем только необходимые поля
       ->where('post_id', $post->id)
       ->get();

   // Преобразуем данные для ответа
   $barberData = $barbers->map(function ($worker) {
       return [
           'id' => $worker->id,
           'name' => $worker->user->name,
           'image' => $worker->user->image,
           'work_experience' => $worker->work_experience,
       ];
   });

   return $this->successResponse(
       $this->paginate($barberData->toArray())
   );
}
}
