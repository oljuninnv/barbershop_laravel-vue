<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Worker;

class PostController extends Controller
{
    // 1. Вывод всех должностей
    public function index(Request $request)
    {
        $name = $request->get('name');

        if ($name) {
            $posts = Post::where('name', 'like', "%$name%")->orWhere('name')->get();
        } else {
            $posts = Post::all();
        }
        return $this->successResponse(
            $this->paginate(
                collect(
                    $posts,
                )
                    ->toArray()
            )
        );
    }

    // 2. Получение определённой должности по id
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Должность не найдена.'], 404);
        }

        return response()->json($post);
    }

    // 3. Создание новой должности
    public function store(PostRequest $request)
    {
        $values = $request->all();

        $post = new Post;
        $post->name = $values['name'];
        $post->save();

        return response()->json($post, 200);
    }

    // 4. Обновление определённой должности
    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);
        
        if (!$post) {
            return response()->json(['error' => 'Должность не найдена.'], 404);
        }

        $values = $request->all();

        $post->name = $values['name'];

        $post->save();

        return response()->json([
            'user' => $post,
            'message' => 'Успешно обновлено'
        ], 200);
    }

    // 5. Удаление определённой должности
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Должность не найдена.'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Должность успешно удалена.']);
    }

    public function information($id) {
        // Находим должность по ID
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['error' => 'Должность не найдена.'], 404);
        }
    
        // Получаем количество сотрудников с данной должностью
        $employeeCount = Worker::where('post_id', $id)->count();
    
        // Получаем список имен сотрудников
        $employeeNames = Worker::where('post_id', $id)
            ->join('users', 'workers.user_id', '=', 'users.id')
            ->pluck('users.name');
    
        // Формируем ответ
        return response()->json([
            'post_name' => $post->name,
            'employee_count' => $employeeCount,
            'employee_names' => $employeeNames
        ]);
    }
}
