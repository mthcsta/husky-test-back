<?php

namespace App\Http\Controllers;

use App\Models\Deliveryman;

use Illuminate\Http\Request;
use App\Http\Resources\DeliverymanResource;
use App\Traits\PageNumber;


class DeliverymanController extends Controller
{
    use PageNumber;

    public function index(Request $request)
    {
        // valida entradas do usuário
        $validated = $request->validate([
            'search' => 'string|nullable',
            'limit' => 'integer',
            'offset' => 'integer',
            'orders' => 'array',
        ]);

        // pega os valores da requisição ou seus valores padrão
        $search = $validated['search'] ?? '';
        $orders = $validated['orders'] ?? [];
        $limit = $validated['limit'] ?? 10;
        $offset = $validated['offset'] ?? 0;

        // usa o offset e o limite para calcular a página atual
        $page = $this->pageNumber($offset, $limit);

        // captura os entregadores que possuem o nome baseado no valor da busca
        $deliverymen = Deliveryman::where('name', 'like', '%' . $search . '%');

        // adiciona order by baseado no array orders
        Deliveryman::addOrders($deliverymen, $orders);

        // retorna os entregadores com paginação
        $deliverymenPaginate = $deliverymen->paginate($limit, ['*'], 'page', $page);

        return DeliverymanResource::collection($deliverymenPaginate);
    }
    public function show($id)
    {
        return new DeliverymanResource(Deliveryman::findOrFail($id));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if (!$validated) {
            return response()->json([
                'message' => 'Erro ao criar Deliveryman',
                'errors' => $validated->errors()
            ], 400);
        }

        $deliveryman = Deliveryman::create($validated);

        return response()->json([
            'message' => 'Deliveryman criado com sucesso',
            'data' => new DeliverymanResource($deliveryman)
        ], 201);

    }

    public function showNearby($lat, $long)
    {
        $deliveryman = Deliveryman::select('*')
            ->selectRaw("(abs(latitude - ".$lat.") + abs(longitude - ".$long.")) as dst")
            ->orderBy('dst', 'asc')
            ->take(10)
            ->get();
        return DeliverymanResource::collection($deliveryman);
    }

}
