<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use App\Models\Post;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Получение всех пользователей, которые не являются сотрудниками +
    public function getNonWorkers(Request $request)
    {
        $name = $request->get('name');

        $query = User::whereDoesntHave('worker');

        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        $users = $query->get();

        return $this->successResponse(
            $this->paginate(
                collect($users)->toArray()
            )
        );
    }

    // 2. Получение всех пользователей, которые являются сотрудниками +
    public function getWorkers(Request $request)
    {
        $name = $request->get('name');

        $query = User::whereHas('worker');

        if ($name) {
            $query->where('name', 'like', "%$name%");
        }

        $users = $query->get();

        return $this->successResponse(
            $this->paginate(
                collect($users)->toArray()
            )
        );
    }

    // 3. Обновление данных пользователя +
    public function update(UpdateUserRequest $request, $id)
    {

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Пользователь не найден.'], 404);
        }

        $oldLogin = $user->login;
        $oldImagePath = $user->image;

        $values = $request->all();

        // Проверяем наличие каждого поля и обновляем только если оно присутствует в запросе
        if ($request->has('name')) {
            $user->name = $values['name'];
        }

        if ($request->has('email')) {
            $user->email = $values['email'];
        }

        if ($request->has('city')) {
            $user->city = $values['city'];
        }

        if ($request->has('phone')) {
            $user->phone = $values['phone'];
        }

        if ($request->has('birthday')) {
            $user->birthday = $values['birthday'];
        }

        if ($request->hasFile('image')) {
            // Получаем расширение загружаемого файла
            $extension = $request->file('image')->getClientOriginalExtension();

            // Формируем имя файла
            $newImageName = $user->login . '.' . $extension;

            // Удаляем старое изображение, если логин изменился
            if ($oldLogin !== $user->login) {
                if ($oldImagePath && Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            } else {
                // Если логин не изменился, но есть старое изображение
                if ($oldImagePath && Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }

            // Сохраняем файл с новым именем
            $imagePath = $request->file('image')->storeAs('users', $newImageName, 'public');

            // Сохраняем путь к изображению в базе данных
            $user->image = $imagePath;
        }


        $user->save();

        return response()->json([
            'user' => new UserResource($user),
            'message' => 'Успешно обновлено'
        ], 200);
        }

    // 4. Удаление пользователя по id +
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Пользователь не найден.'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Пользователь успешно удалён.']);
    }

    // 5. Получение всех пользователей +
    public function index(Request $request)
    {
        $name = $request->get('name');

        if ($name) {
            $users = User::where('name', 'like', "%$name%")->orWhere('name')->get();
        } else {
            $users = User::all();
        }
        return $this->successResponse(
            $this->paginate(
                collect(
                    $users,
                )
                    ->toArray()
            )
        );
    }

    // 6. Добавление как обычного пользователя или как нового сотрудника +
    public function store(UserRequest $request)
    {
        $values = $request->all();

        $user = new User();
        $user->name = $values['name'];
        $user->login = $values['login'];
        $user->email = $values['email'];
        $user->phone = $values['phone'];
        $user->city = $values['city'];
        $user->birthday = $values['birthday'];
        $user->password = Hash::make($values['password']);

        if ($request->hasFile('image')) {
            // Получаем логин пользователя
            $login = $user->login;

            // Получаем расширение загружаемого файла
            $extension = $request->file('image')->getClientOriginalExtension();

            // Формируем имя файла
            $imageName = $login . '.' . $extension;

            // Сохраняем файл с новым именем
            $imagePath = $request->file('image')->storeAs('users', $imageName, 'public'); // Сохраняем на диск public

            // Сохраняем путь к изображению в базе данных
            $user->image = $imagePath;
        }

        $user->save();

        // Проверяем, добавлять ли в таблицу Worker 
        if ($request->input('is_worker', true)) {
            // Находим post_id, где значение равно 'Undefined'
            $post = Post::where('name', 'Undefined')->first();
    
            if ($post) {
                // Создаём запись в таблице Worker с user_id, work_experience и post_id
                $worker = Worker::create([
                    'user_id' => $user->id,
                    'work_experience' => 0, // Начальное значение
                    'post_id' => $post->id, // Используем найденный post_id
                ]);
            } else {
                return response()->json(['error' => 'Пост с указанным значением не найден.'], 404);
            }
        }

        return response()->json(['user' => $user, 'worker' => $worker, 'message' => 'Пользователь успешно добавлен.']);
    }

    // 7. Получение информации о пользователе по id +
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Пользователь не найден.'], 404);
        }

        return response()->json($user);
    }
}
