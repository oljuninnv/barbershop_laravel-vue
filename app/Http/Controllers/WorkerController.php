<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
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
    public function getAdmins(Request $request)
{
    $name = $request->get('name');
    
    // Получаем пост с ролью 'Admin'
    $post = Post::where('name', 'Admin')->first();

    if (!$post) {
        return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
    }

    // Начинаем запрос для получения работников
    $query = Worker::with('user') // Предполагается, что в модели Worker есть связь с моделью User
        ->where('post_id', $post->id);

    // Фильтруем по имени пользователя, если оно указано
    if ($name) {
        $query->whereHas('user', function ($q) use ($name) {
            $q->where('name', 'like', "%$name%");
        });
    }

    // Получаем работников
    $admins = $query->get();

    // Преобразуем данные для ответа
    $adminsData = $admins->map(function ($worker) {
        // Рассчитываем опыт работы в годах
        $workExperience = $worker->adopted_at ? Carbon::parse($worker->adopted_at)->diffInYears(Carbon::now()) : null;

        return [
            'worker_id' => $worker->id,
            'work_experience' => $workExperience, // Используем рассчитанный опыт работы
            'adopted_at' => $worker->adopted_at, 
            'user_id' => $worker->user_id,
            'user_name' => $worker->user ? $worker->user->name : null, // Получаем имя пользователя
            'user_email' => $worker->user ? $worker->user->email : null, // Получаем email пользователя
            'user_phone' => $worker->user ? $worker->user->phone : null, // Получаем phone пользователя
            'user_birthday' => $worker->user ? $worker->user->birthday : null, // Получаем birthday пользователя
            'user_login' => $worker->user ? $worker->user->login : null, // Получаем login пользователя
            'user_city' => $worker->user ? $worker->user->city : null, // Получаем city пользователя
        ];
    });

    return $this->successResponse(
        $this->paginate(
            $adminsData->toArray()
        )
    );
}

public function getAccountants(Request $request)
{
    $name = $request->get('name');
    
    // Получаем пост с ролью 'Admin'
    $post = Post::where('name', 'Accountant')->first();

    if (!$post) {
        return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
    }

    // Начинаем запрос для получения работников
    $query = Worker::with('user') // Предполагается, что в модели Worker есть связь с моделью User
        ->where('post_id', $post->id);

    // Фильтруем по имени пользователя, если оно указано
    if ($name) {
        $query->whereHas('user', function ($q) use ($name) {
            $q->where('name', 'like', "%$name%");
        });
    }

    // Получаем работников
    $admins = $query->get();

    // Преобразуем данные для ответа
    $adminsData = $admins->map(function ($worker) {
        // Рассчитываем опыт работы в годах
        $workExperience = $worker->adopted_at ? Carbon::parse($worker->adopted_at)->diffInYears(Carbon::now()) : null;

        return [
            'worker_id' => $worker->id,
            'work_experience' => $workExperience, // Используем рассчитанный опыт работы
            'adopted_at' => $worker->adopted_at, 
            'user_id' => $worker->user_id,
            'user_name' => $worker->user ? $worker->user->name : null, // Получаем имя пользователя
            'user_email' => $worker->user ? $worker->user->email : null, // Получаем email пользователя
            'user_phone' => $worker->user ? $worker->user->phone : null, // Получаем phone пользователя
            'user_birthday' => $worker->user ? $worker->user->birthday : null, // Получаем birthday пользователя
            'user_login' => $worker->user ? $worker->user->login : null, // Получаем login пользователя
            'user_city' => $worker->user ? $worker->user->city : null, // Получаем city пользователя
        ];
    });

    return $this->successResponse(
        $this->paginate(
            $adminsData->toArray()
        )
    );
}

// 4. Получение всех работников с ролью Barber
public function getBarbers(Request $request)
{
    $name = $request->get('name');
    
    // Получаем пост с ролью 'Admin'
    $post = Post::where('name', 'Barber')->first();

    if (!$post) {
        return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
    }

    // Начинаем запрос для получения работников
    $query = Worker::with('user') // Предполагается, что в модели Worker есть связь с моделью User
        ->where('post_id', $post->id);

    // Фильтруем по имени пользователя, если оно указано
    if ($name) {
        $query->whereHas('user', function ($q) use ($name) {
            $q->where('name', 'like', "%$name%");
        });
    }

    // Получаем работников
    $barbers = $query->get();

    // Преобразуем данные для ответа
    $barbersData = $barbers->map(function ($worker) {
        // Рассчитываем опыт работы в годах
        $workExperience = $worker->adopted_at ? Carbon::parse($worker->adopted_at)->diffInYears(Carbon::now()) : null;

        return [
            'worker_id' => $worker->id,
            'work_experience' => $workExperience, // Используем рассчитанный опыт работы
            'adopted_at' => $worker->adopted_at, 
            'user_id' => $worker->user_id,
            'user_name' => $worker->user ? $worker->user->name : null, // Получаем имя пользователя
            'user_email' => $worker->user ? $worker->user->email : null, // Получаем email пользователя
            'user_phone' => $worker->user ? $worker->user->phone : null, // Получаем phone пользователя
            'user_birthday' => $worker->user ? $worker->user->birthday : null, // Получаем birthday пользователя
            'user_login' => $worker->user ? $worker->user->login : null, // Получаем login пользователя
            'user_city' => $worker->user ? $worker->user->city : null, // Получаем city пользователя
        ];
    });

    return $this->successResponse(
        $this->paginate(
            $barbersData->toArray()
        )
    );
}

    // 5. Получение всех работников с ролью Undefined
    public function getUndefined(Request $request)
    {
        $name = $request->get('name');
        
        // Получаем пост с ролью 'Admin'
        $post = Post::where('name', 'Undefined')->first();
    
        if (!$post) {
            return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
        }
    
        // Начинаем запрос для получения работников
        $query = Worker::with('user') // Предполагается, что в модели Worker есть связь с моделью User
            ->where('post_id', $post->id);
    
        // Фильтруем по имени пользователя, если оно указано
        if ($name) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', "%$name%");
            });
        }
    
        // Получаем работников
        $undefineds = $query->get();
    
        // Преобразуем данные для ответа
        $undefinedsData = $undefineds->map(function ($worker) {
            // Рассчитываем опыт работы в годах
            $workExperience = $worker->adopted_at ? Carbon::parse($worker->adopted_at)->diffInYears(Carbon::now()) : null;
    
            return [
                'worker_id' => $worker->id,
                'work_experience' => $workExperience, // Используем рассчитанный опыт работы
                'adopted_at' => $worker->adopted_at, 
                'user_id' => $worker->user_id,
                'user_name' => $worker->user ? $worker->user->name : null, // Получаем имя пользователя
                'user_email' => $worker->user ? $worker->user->email : null, // Получаем email пользователя
                'user_phone' => $worker->user ? $worker->user->phone : null, // Получаем phone пользователя
                'user_birthday' => $worker->user ? $worker->user->birthday : null, // Получаем birthday пользователя
                'user_login' => $worker->user ? $worker->user->login : null, // Получаем login пользователя
                'user_city' => $worker->user ? $worker->user->city : null, // Получаем city пользователя
            ];
        });
    
        return $this->successResponse(
            $this->paginate(
                $undefinedsData->toArray()
            )
        );
    }

    // 6. Получение всех работников с ролью Staff
    public function getStaff(Request $request)
    {
        $name = $request->get('name');
        
        // Получаем пост с ролью 'Admin'
        $post = Post::where('name', 'Staff')->first();
    
        if (!$post) {
            return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
        }
    
        // Начинаем запрос для получения работников
        $query = Worker::with('user') // Предполагается, что в модели Worker есть связь с моделью User
            ->where('post_id', $post->id);
    
        // Фильтруем по имени пользователя, если оно указано
        if ($name) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', "%$name%");
            });
        }
    
        // Получаем работников
        $staffs = $query->get();
    
        // Преобразуем данные для ответа
        $staffsData = $staffs->map(function ($worker) {
            // Рассчитываем опыт работы в годах
            $workExperience = $worker->adopted_at ? Carbon::parse($worker->adopted_at)->diffInYears(Carbon::now()) : null;
    
            return [
                'worker_id' => $worker->id,
                'work_experience' => $workExperience, // Используем рассчитанный опыт работы
                'adopted_at' => $worker->adopted_at, 
                'user_id' => $worker->user_id,
                'user_name' => $worker->user ? $worker->user->name : null, // Получаем имя пользователя
                'user_email' => $worker->user ? $worker->user->email : null, // Получаем email пользователя
                'user_phone' => $worker->user ? $worker->user->phone : null, // Получаем phone пользователя
                'user_birthday' => $worker->user ? $worker->user->birthday : null, // Получаем birthday пользователя
                'user_login' => $worker->user ? $worker->user->login : null, // Получаем login пользователя
                'user_city' => $worker->user ? $worker->user->city : null, // Получаем city пользователя
            ];
        });
    
        return $this->successResponse(
            $this->paginate(
                $staffsData->toArray()
            )
        );
    }

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

        if ($request->has('adopted_at')) {
            $worker->adopted_at = $values['adopted_at'];
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
        $worker->adopted_at = $values['adopted_at'];
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
            // Рассчитываем опыт работы в годах
            $workExperience = $worker->adopted_at ? Carbon::parse($worker->adopted_at)->diffInYears(Carbon::now()) : null;
    
            return [
                'id' => $worker->id,
                'name' => $worker->user->name,
                'image' => $worker->user->image,
                'work_experience' => $workExperience, // Используем рассчитанный опыт работы
            ];
        });
    
        return $this->successResponse(
            $this->paginate($barberData->toArray())
        );
    }
}
