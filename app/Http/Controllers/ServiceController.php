<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Http\Requests\ServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{
    // 1. Получение всех услуг
    public function index(Request $request)
    {
        $name = $request->get('name');

        if ($name) {
            $services = Service::where('name', 'like', "%$name%")->orWhere('name')->get();
        } else {
            $services = Service::all();
        }
        return $this->successResponse(
            $this->paginate(
                collect(
                    $services,
                )
                    ->toArray()
            )
        );
    }

    // 2. Получение определённой услуги
    public function show($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['error' => 'Услуга не найдена.'], 404);
        }

        return response()->json($service);
    }

    // 3. Добавление услуги
    public function store(ServiceRequest $request)
    {

        $values = $request->all();

        $service = new Service;
        $service->name = $values['name'];
        $service->price = $values['price'];
        $service->execution_time = $values['execution_time'];
        $service->save();

        return response()->json($service, 200);
    }

    // 4. Обновление услуги
    public function update(UpdateServiceRequest $request, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['error' => 'Услуга не найдена.'], 404);
        }

        $values = $request->all();

        // Проверяем наличие каждого поля и обновляем только если оно присутствует в запросе
        if ($request->has('name')) {
            $service->name = $values['name'];
        }

        if ($request->has('price')) {
            $service->price = $values['price'];
        }

        if ($request->has('execution_time')) {
            $service->execution_time = $values['execution_time'];
        }

        $service->save();

        return response()->json([
            'service' => $service,
            'message' => 'Успешно обновлено'
        ], 200);
    }

    // 5. Удаление услуги
    public function destroy($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['error' => 'Услуга не найдена.'], 404);
        }

        $service->delete();
        return response()->json(['message' => 'Услуга успешно удалена.']);
    }
}
