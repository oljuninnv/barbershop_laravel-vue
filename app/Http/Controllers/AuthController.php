<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
{        
    $values = $request->all();

    // Проверка аутентификации
    if (Auth::attempt(['email' => $values['email'], 'password' => $values['password']])) {
        $user = Auth::user();
        $success['token'] = $user->createToken('User Token')->accessToken;

        // Получаем данные работника, включая имя поста
        $worker = Worker::with('post')->where('user_id', $user->id)->first();

        // Проверяем, найден ли работник
        if (!$worker) {
            $success['data'] = [
                'user' => $user,
                'token' => $success['token']
            ];
    
            return $this->successResponse($success);
        }

        // Формируем ответ
        $success['data'] = [
            'user' => $user,
            'worker' => $worker,
            'token' => $success['token']
        ];

        return $this->successResponse($success);
    }

    return $this->failureResponse(['error' => 'Ошибка в заполнении данных.']);
}

    public function register(RegisterRequest $request): JsonResponse
    {
        // Валидация данных запроса
        $values = $request->all();
            // Создание нового пользователя
            $user = new User;
            $user->name = $values['name'];
            $user->login = $values['login'];
            $user->email = $values['email'];
            $user->phone = $values['phone'];
            $user->city = $values['city'];
            $user->birthday = $values['birthday'];
            $user->password = Hash::make($values['password']);
            $user->save();

            // Генерация токена доступа
            $token = $user->createToken('Access Token')->accessToken;
            $data = [
                'user' => $user,
                'access_token' => $token,
            ];

            return response()->json($data, 200);
        } 
}
