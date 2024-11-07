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
        $post = Post::where('name', 'Admin')->first(); // Замените 'role' на фактическое имя столбца

        if (!$post) {
            return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
        }

        // Получаем работников с найденным post_id
        $admins = Worker::where('post_id', $post->id)->get();

        return $this->successResponse(
            $this->paginate(
                collect($admins)->toArray()
            )
        );
    }

// 4. Получение всех работников с ролью Barber
public function getBarbers()
    {
        $post = Post::where('name', 'Barber')->first(); // Замените 'role' на фактическое имя столбца

        if (!$post) {
            return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
        }

        // Получаем работников с найденным post_id
        $barbers = Worker::where('post_id', $post->id)->get();

        return $this->successResponse(
            $this->paginate(
                collect($barbers)->toArray()
            )
        );
    }

    // 5. Получение всех работников с ролью Undefined
    public function getUndefined()
    {
        // Находим пост с 'Undefined'
        $post = Post::where('name', 'Undefined')->first(); // Замените 'column_name' на фактическое имя столбца

        if (!$post) {
            return response()->json(['error' => 'Пост с указанным значением не найден.'], 404);
        }

        // Получаем работников с найденным post_id
        $undefinedWorkers = Worker::where('post_id', $post->id)->get();

        return $this->successResponse(
            $this->paginate(
                collect($undefinedWorkers)->toArray()
            )
        );
    }

    // 6. Получение всех работников с ролью Staff
    public function getStaff()
    {
        $post = Post::where('name', 'Staff')->first(); // Замените 'role' на фактическое имя столбца

        if (!$post) {
            return response()->json(['error' => 'Пост с указанной ролью не найден.'], 404);
        }

        // Получаем работников с найденным post_id
        $staff = Worker::where('post_id', $post->id)->get();

        return $this->successResponse(
            $this->paginate(
                collect($staff)->toArray()
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
}
