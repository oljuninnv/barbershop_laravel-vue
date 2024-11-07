<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RecordServicesRequest;
use App\Models\Record;
use App\Models\Service;
use App\Models\RecordService;

class RecordServicesController extends Controller
{
    // 1. Добавление записи
    public function store(RecordServicesRequest $request)
    {
        $data = $request->validated();
        $recordService = RecordService::create($data);

        return response()->json($recordService, 201);
    }

    // 2. Обновление записи
    public function update(RecordServicesRequest $request, $id)
    {
        $data = $request->validated();
        $recordService = RecordService::findOrFail($id);
        $recordService->update($data);

        return response()->json($recordService);
    }

    // 3. Удаление записи
    public function destroy($id)
    {
        $recordService = RecordService::findOrFail($id);
        $recordService->delete();

        return response()->json(['message' => 'Запись успешно удалена.']);
    }

    // 4. Получение всех записей
    public function index()
    {
        $recordService = RecordService::all();
         return $this->successResponse(
            $this->paginate(
                collect(
                    $recordService,
                )
                    ->toArray()
            )
        );
    }

    // 5. Получение одной записи по ID
    public function show($id)
    {
        $recordService = RecordService::findOrFail($id);
        return response()->json($recordService);
    }
}
