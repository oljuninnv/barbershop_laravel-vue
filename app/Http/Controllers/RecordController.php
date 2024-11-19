<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RecordRequest;
use App\Models\User;
use App\Models\Worker;
use App\Models\Record;
use App\Models\Service;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use App\Models\RecordService;

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
            $data['is_finished'] = 0;
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

    // Инициализируем переменные для подсчета total_price и total_duration
    $totalPrice = 0;
    $totalDuration = 0;
 // Получаем массив services из запроса
 $services = $request->get('services');

 // Проверяем, что services существует и является массивом
 if (isset($services) && is_array($services)) {
        foreach ($services as $serviceId) {
            // Получаем информацию о сервисе
            $service = Service::find($serviceId);
            if ($service) {
                // Добавляем стоимость и продолжительность сервиса к общим значениям
                $totalPrice = $service->price; // Предполагается, что в таблице Service есть поле price
                $totalDuration = $service->execution_time; // Предполагается, что в таблице Service есть поле duration

                // Проверяем, существует ли запись с данным service_id
                $recordService = RecordService::where('record_id', $record->id)
                    ->where('service_id', $serviceId)
                    ->first();

                if ($recordService) {
                    // Если запись существует, обновляем её
                    $recordService->update(['service_id' => $serviceId]);
                } else {
                    // Если записи нет, создаем новую
                    RecordService::create([
                        'record_id' => $record->id,
                        'service_id' => $serviceId,
                        'total_price' => $totalPrice,
                        'total_duration' => $totalDuration,
                    ]);
                }
            }
        }
    }

    // Обновляем поля total_price и total_duration в записи
    $record->update([
        'total_price' => $totalPrice,
        'total_duration' => $totalDuration,
    ]);

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
        // Находим запись по ID
        $record = Record::find($id);
        if (!$record) {
            return response()->json(['error' => 'Запись не найдена.'], 404);
        }

        // Удаляем связанные записи из таблицы record_services
        $record->recordServices()->delete(); // Предполагается, что связь определена в модели Record

        // Удаляем основную запись
        $record->delete();

        return response()->json(['message' => 'Запись успешно удалена.']);
    }

    public function deleteUserFieldsById($id): JsonResponse
    {
        // Находим запись по ID
        $record = Record::find($id);
        if (!$record) {
            return response()->json(['error' => 'Запись не найдена.'], 404);
        }

        // Удаляем только указанные поля
        $record->user_id = null;
        $record->user_name = null;
        $record->user_email = null;
        $record->user_phone = null;

        // Сохраняем изменения
        $record->save();

        return response()->json(['message' => 'Пользовательские поля успешно удалены.']);
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

    public function BarberRecords($id)
    {
        try {
            // Получаем ID поста "Barber"
            $barberPost = Post::where('name', 'Barber')->first();
    
            if (!$barberPost) {
                return $this->errorResponse('Barber post not found', 404);
            }
    
            $barberPostId = $barberPost->id;
    
            // Проверяем, существует ли работник с переданным ID и постом "Barber"
            $worker = Worker::where('id', $id)->where('post_id', $barberPostId)->first();
    
            if (!$worker) {
                return $this->errorResponse('Worker not found or does not have the Barber post', 404);
            }
    
            // Получаем записи, соответствующие этому работнику, и сортируем по дате
            $records = Record::where('worker_id', $id)
                ->orderBy('date') // Сортировка по дате
                ->orderBy('time') // Дополнительная сортировка по времени, если необходимо
                ->with(['recordServices.service', 'user']) // Подключаем связанные записи услуг и пользователя
                ->get();
    
            if ($records->isEmpty()) {
                return $this->successResponse([], 'No records found for this worker');
            }
    
            // Формируем массив с записями и только названиями услуг
            $recordsWithServices = $records->map(function ($record) {
                // Считаем итоговую стоимость
                $totalPrice = $record->recordServices->sum('total_price');
    
                // Извлекаем только необходимые поля из записи
                $recordData = [
                    'id' => $record->id,
                    'user_id' => $record->user_id,
                    'worker_id' => $record->worker_id,
                    'worker_name' => $record->worker->user->name, // Получаем имя работника
                    'date' => $record->date,
                    'time' => $record->time,
                    'user_name' => $record->user_name,
                    'user_phone' => $record->user_phone,
                    'user_email' => $record->user_email,
                    'created_at' => $record->created_at,
                    'is_finished' => $record->is_finished,
                    'updated_at' => $record->updated_at,
                    'total_price' => $totalPrice, // Итоговая стоимость
                    'services' => $record->recordServices->map(function ($recordService) {
                        return $recordService->service->name; // Получаем только имя услуги
                    })->values(), // Приводим к массиву
                ];
    
                return $recordData; // Возвращаем только нужные данные
            });
    
            return $this->successResponse($recordsWithServices);
        } catch (\Exception $e) {
            // Логируем ошибку для дальнейшего анализа
            \Log::error('Error fetching barber records: ' . $e->getMessage());
    
            return $this->errorResponse('An error occurred while fetching records', 500);
        }
    }

    public function VisitorRecords($id)
    {
        try {
            // Получаем пользователя по ID
            $user = User::where('id', $id)->first();

            if (!$user) {
                return $this->errorResponse('Пользователь не найден', 404);
            }

            // Получаем записи, соответствующие этому пользователю, включая связи с работниками и услугами
            $records = Record::with(['worker', 'recordServices.service'])
                ->where('user_id', $id)
                ->orderBy('date') // Сортировка по дате
                ->orderBy('time') // Дополнительная сортировка по времени
                ->get();

            if ($records->isEmpty()) {
                return $this->successResponse([], 'No records found for this user');
            }

            // Формируем массив с записями и добавляем имя работника и услуги
            $recordsWithDetails = $records->map(function ($record) {
                $totalPrice = $record->recordServices->sum('total_price'); // Сумма всех услуг в записи
                return [
                    'id' => $record->id,
                    'date' => $record->date,
                    'time' => $record->time,
                    'is_finished' => $record->is_finished,
                    'worker_name' => $record->worker->user->name ?? 'Неизвестный работник', // Имя работника
                    'services' => $record->recordServices->map(function ($recordService) {
                        return [
                            'service_name' => $recordService->service->name ?? 'Неизвестная услуга', // Имя услуги
                        ];
                    }),
                    'total_price' => $totalPrice // Общая сумма заказа
                ];
            });

            return $this->successResponse($recordsWithDetails);
        } catch (\Exception $e) {
            // Логируем ошибку для дальнейшего анализа
            \Log::error('Error fetching users records: ' . $e->getMessage());

            return $this->errorResponse('An error occurred while fetching records', 500);
        }
    }

    public function getInfoForRecords(): JsonResponse
    {
        // Получаем работников с должностью "Barber"
        $workers = Worker::with(['user', 'post'])
            ->whereHas('post', function ($query) {
                $query->where('name', 'Barber');
            })
            ->get();

        // Получаем список услуг
        $services = Service::all();

        // Формируем массив данных для ответа
        $barbers = $workers->map(function ($worker) {
            return [
                'worker_id' =>$worker->id,
                'name' =>$worker->user->name,
            ]; // Список имен барберов
        });

        // Формируем список услуг
        $servicesList = $services->map(function ($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'price' => $service->price,
                'image' => $service->image,
            ];
        });

        // Формируем окончательный ответ
        $response = [
            'barbers' => $barbers,
            'services' => $servicesList,
        ];

        return response()->json($response);
    }

    public function getAvailableRecords(Request $request): JsonResponse
    {
        // Получаем параметры из запроса
        $date = $request->input('date');
        $time = $request->input('time');
        $workerId = $request->input('worker_id');

        // Начинаем запрос к записям
        $query = Record::query();

        // Условие для фильтрации по user_id и user_name
        $query->whereNull('user_id')->whereNull('user_name');

        // Если указаны параметры date и time, добавляем их в запрос
        if ($date) {
            $query->where('date', $date);
        }

        if ($time) {
            $query->where('time', $time);
        }

        // Если указан worker_id, проверяем, что worker_id соответствует работникам с должностью "Barber"
        if ($workerId) {
            $query->where('worker_id', $workerId);
        }

        // Получаем работников с должностью "Barber"
        $barberWorkers = Worker::whereHas('post', function ($query) {
            $query->where('name', 'Barber');
        })->pluck('id');

        // Если worker_id не указан, добавляем условие для получения свободных записей только для барберов
        if (!$workerId) {
            $query->whereIn('worker_id', $barberWorkers);
        }

        // Получаем все свободные записи
        $availableRecords = $query->get();

        return response()->json($availableRecords);
    }

    public function finished(Request $request, $id)
{
    try {
        // Находим запись по ID
        $record = Record::findOrFail($id);

        // Получаем значение is_finished из запроса
        $isFinished = $request->input('is_finished');

        // Прямое присвоение значения is_finished
        $record->is_finished = $isFinished;

        // Сохраняем изменения в базе данных
        $record->save();

        return response()->json($record);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Запись не найдена.'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Произошла ошибка при обновлении записи: ' . $e->getMessage()], 500);
    }
}
}
