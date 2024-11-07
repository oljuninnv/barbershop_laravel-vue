<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestoreConfirmRequest;
use Illuminate\Http\Request;
use Mail;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function forgetPassword(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->get();

            if (count($user) > 0) {
                $token = Str::random(40);
                // $domain = URL::to('/');
                // $url = $domain.'/auth/restore/confirm?token='.$token;
                $url = 'http://localhost:5173/auth/restore/confirm?token=' . $token;

                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['token'] = $token;
                $data['title'] = 'Смена пароля';
                $data['body'] = "Пожалуйста, нажмите ниже, чтобы сменить пароль";

                Mail::send('password', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });

                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    ['email' => $request->email],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $datetime
                    ]
                );

                return response()->json(['success' => true, 'msg' => 'Please check your mail to reset your password!']);
            } else {
                return response()->json(['success' => false, 'msg' => 'User not found!']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function resetPasswordLoad(Request $request)
    {
        $resetData = PasswordReset::where('token', $request->token)->get();
        if (isset($request->token) && count($resetData) > 0) {
            $user = User::where('email', $resetData[0]['email'])->get();
            // return view('resetPassword', compact('user'));

            if ($user) {
                return response()->json(['msg' => 'User found', 'success' => true], 200);
            } else {
                return response()->json(['msg' => 'User not found', 'success' => false], 404);
            }
        } else {
            return response()->json(['msg' => 'Invalid token', 'success' => false], 404);
        }
    }

    // public function resetPassword(Request $request)
    // {
    //     $request->validate([
    //         'password' => 'required|string|min:6|confirmed'
    //     ]);

    //     $user = User::find($request->id);
    //     $user->password =  Hash::make($request->password);
    //     $user->save();

    //     PasswordReset::where('email',$user->email)->delete();

    //     // $data = [
    //     //     'password' => $user->password,
    //     //     'email' => $user->email,
    //     //     'message' => 'Password changed successfully!'
    //     // ];

    //     return response()->json(['msg' => 'Password changed successfully!','success' => true], 200);
    //     // return "<h1>Пароль успешно сменён</h1>";
    // }

    public function resetPassword(RestoreConfirmRequest $request)
    {
        // Проверка на наличие токена
        $resetData = PasswordReset::where('token', $request->get('token'))->first();

        if (!$resetData) {
            return response()->json(['msg' => 'Invalid token', 'success' => false], 404);
        }

        // Проверка на наличие пользователя
        $user = User::where('email', $resetData->email)->first();

        if (!$user) {
            return response()->json(['msg' => 'User not found', 'success' => false], 404);
        }

        // Изменение пароля
        $user->password = Hash::make($request->get('password'));
        $user->save();

        // Удаление записи о сбросе пароля
        PasswordReset::where('email', $user->email)->delete();

        $data = [
            'msg' => 'Password changed successfully!',
            'success' => true,
        ];

        return $this->successResponse($data);
    }
}