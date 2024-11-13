<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Worker;
use App\Models\Post;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

    // Получаем пользователей, у которых есть связанные работники
    $query = User::whereHas('worker');

    if ($name) {
        // Фильтруем по имени пользователя
        $query->where('name', 'like', "%$name%");
    }

    // Получаем пользователей с их работниками и постами
    $users = $query->with(['worker.post'])->get();

    // Формируем массив с данными пользователей и именами постов
    $result = $users->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'city' => $user->city,
            'birthday' => $user->birthday,
            'post_name' => $user->worker->post ? $user->worker->post->name : null,
        ];
    });

    return $this->successResponse(
        $this->paginate(
            $result->toArray()
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
            'user' => $user,
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

//     public function addUsers()
// {
//     $users = [
//         [
//             'name' => 'Иван Иванов',
//             'login' => 'ivan_ivanov',
//             'email' => 'ivan@example.com',
//             'phone' => '1234567890',
//             'city' => 'Москва',
//             'birthday' => '1990-01-01',
//             'password' => 'password123',
//         ],
//         [
//             'name' => 'Петр Петров',
//             'login' => 'petr_petrov',
//             'email' => 'petr@example.com',
//             'phone' => '0987654321',
//             'city' => 'Санкт-Петербург',
//             'birthday' => '1992-02-02',
//             'password' => 'password123',
//         ],
//         // Добавьте еще 5 пользователей с уникальными данными
//         [
//             'name' => 'Сергей Сергеев',
//             'login' => 'sergey_sergeev',
//             'email' => 'sergey@example.com',
//             'phone' => '1122334455',
//             'city' => 'Екатеринбург',
//             'birthday' => '1993-03-03',
//             'password' => 'password123',
//         ],
//         [
//             'name' => 'Анна Анна',
//             'login' => 'anna_anna',
//             'email' => 'anna@example.com',
//             'phone' => '2233445566',
//             'city' => 'Казань',
//             'birthday' => '1994-04-04',
//             'password' => 'password123',
//         ],
//         [
//             'name' => 'Ольга Ольгина',
//             'login' => 'olga_olgina',
//             'email' => 'olga@example.com',
//             'phone' => '3344556677',
//             'city' => 'Нижний Новгород',
//             'birthday' => '1995-05-05',
//             'password' => 'password123',
//         ],
//         [
//             'name' => 'Дмитрий Дмитриев',
//             'login' => 'dmitriy_dmitriev',
//             'email' => 'dmitriy@example.com',
//             'phone' => '4455667788',
//             'city' => 'Челябинск',
//             'birthday' => '1996-06-06',
//             'password' => 'password123',
//         ],
//         [
//             'name' => 'Елена Еленина',
//             'login' => 'elena_elenina',
//             'email' => 'elena@example.com',
//             'phone' => '5566778899',
//             'city' => 'Ростов-на-Дону',
//             'birthday' => '1997-07-07',
//             'password' => 'password123',
//         ],
//     ];

//     foreach ($users as $userData) {
//         $user = new User();
//         $user->name = $userData['name'];
//         $user->login = $userData['login'];
//         $user->email = $userData['email'];
//         $user->phone = $userData['phone'];
//         $user->city = $userData['city'];
//         $user->birthday = $userData['birthday'];
//         $user->password = Hash::make($userData['password']);
//         $user->save();

//         // Проверяем, добавлять ли в таблицу Worker
//         $post = Post::where('name', 'Undefined')->first();
//         if ($post) {
//             Worker::create([
//                 'user_id' => $user->id,
//                 'work_experience' => 0, // Начальное значение
//                 'post_id' => $post->id, // Используем найденный post_id
//             ]);
//         }
//     }

//     return response()->json(['message' => '7 пользователей успешно добавлены.']);
// }
}
