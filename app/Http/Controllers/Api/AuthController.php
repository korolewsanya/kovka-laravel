<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseApiController
{
    /**
     * Вход в систему (Android)
     */
    public function login(Request $request)
    {
        // Валидация входных данных
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',

            // === ДОБАВЛЯЕМ ПРОВЕРКУ СОГЛАСИЯ ===
            // Правило 'accepted' требует, чтобы пришло значение 'yes', 'on', 1, '1' или true
            'agree' => 'accepted',
        ], [
            // Кастомный текст ошибки на русском
            'agree.accepted' => 'Вы должны согласиться с политикой конфиденциальности для входа в систему.',
        ]);

        if ($validator->fails()) {
            // Возвращаем первую ошибку (в данном случае — про согласие)
            return $this->error($validator->errors()->first(), 422);
        }

        // Ищем пользователя по email
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Неверный email или пароль', 401);
        }

        // Создаём токен для Android
        $token = $user->createToken('android_app')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
            'role' => $user->role,
        ], 'Успешный вход');
    }

    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success([], 'Выход выполнен');
    }

    /**
     * Получение информации о пользователе
     */
    public function user(Request $request)
    {
        return $this->success($request->user(), 'Данные пользователя');
    }
}
