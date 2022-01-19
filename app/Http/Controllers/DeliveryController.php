<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Client;

use Illuminate\Http\Request;
use App\Http\Resources\DeliveryResource;
use App\Traits\PageNumber;

class DeliveryController extends Controller
{
    use PageNumber;

    public function index(Request $request)
    {
        // valida entradas do usuário
        $validated = $request->validate([
            'limit' => 'integer',
            'offset' => 'integer',
            'orders' => 'array',
            'filters' => 'array',
        ]);

        // pega os valores da requisição ou seus valores padrão
        $orders = $validated['orders'] ?? [];
        $filters = $validated['filters'] ?? [];
        $limit = $validated['limit'] ?? 10;
        $offset = $validated['offset'] ?? 0;

        // calcula a página atual com base no offset e no limite
        $pageNumber = $this->pageNumber($offset, $limit);

        // captura as entregas
        $deliveries = Delivery::with('client', 'deliveryman', 'collectPoint', 'destinationPoint');

        // adiciona filtro baseado no array filters
        Delivery::addFilters($deliveries, $filters);

        // adiciona order by baseado no array orders
        Delivery::addOrders($deliveries, $orders);

        // retorna as entregas com paginação
        $deliveriesPaginate = $deliveries->paginate($limit, ['*'], 'page', $pageNumber);

        return DeliveryResource::collection($deliveriesPaginate);
    }
    public function show($id)
    {
        return new DeliveryResource(Delivery::findOrFail($id));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|integer',
            'collect_point_id' => 'required|integer',
            'destination_point_id' => 'required|integer',
        ]);

        if (!$validated) {
            return response()->json([
                'message' => 'Erro ao criar entrega',
                'errors' => $validated->errors()
            ], 400);
        }

        $delivery = Delivery::create($validated);

        return new DeliveryResource($delivery);
    }

    public function update(Request $request, $id)
    {
        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json([
                'message' => 'Entrega não encontrada',
            ], 404);
        }

        $validated = $request->validate([
            'client_id' => 'required|integer',
            'collect_point_id' => 'required|integer',
            'destination_point_id' => 'required|integer',
            'status' => 'integer',
            'deliveryman_id' => 'integer',
        ]);

        if (!$validated) {
            return response()->json([
                'message' => 'Erro ao atualizar entrega',
                'errors' => $validated->errors()
            ], 400);
        }

        $delivery->client_id = $request->client_id;
        $delivery->collect_point_id = $request->collect_point_id;
        $delivery->destination_point_id = $request->destination_point_id;
        if (isset($request->status)) {
            $delivery->setStatus($request->status);
        }
        if (isset($request->deliveryman_id)) {
            $delivery->deliveryman_id = $request->deliveryman_id;
        }
        $delivery->save();

        return response()->json([
            'message' => 'Entrega atualizada com sucesso',
            'data' => new DeliveryResource($delivery)
        ], 200);
    }

    public function destroy($id)
    {
        $delivery = Delivery::find($id);

        if (!$delivery) {
            return response()->json([
                'message' => 'Entrega não encontrada',
            ], 404);
        }

        $delivery->delete();

        return response()->json([
            'message' => 'Entrega removida com sucesso',
            'data' => new DeliveryResource($delivery)
        ], 200);
    }

}
