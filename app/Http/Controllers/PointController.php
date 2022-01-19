<?php

namespace App\Http\Controllers;

use App\Models\Point;

use Illuminate\Http\Request;
use App\Http\Resources\PointResource;
use App\Traits\PageNumber;

class PointController extends Controller
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
        $search =  $validated['search'] ?? '';
        $orders =  $validated['orders'] ?? [];
        $limit =  $validated['limit'] ?? 10;
        $offset =  $validated['offset'] ?? 0;

        // calcula a página atual com base no offset e no limite
        $pageNumber = $this->pageNumber($offset, $limit);

        // captura os pontos que possuem o endereço baseado no valor da busca
        $points = Point::where('address', 'like', '%' . $search . '%');

        // adiciona order by baseado no array orders
        Point::addOrders($points, $orders);

        // retorna os pontos com paginação
        $pointsPaginate = $points->paginate($limit, ['*'], 'page', $pageNumber);

        return PointResource::collection($pointsPaginate);
    }

    public function show($id)
    {
        return new PointResource(Point::findOrFail($id));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if (!$validated) {
            return response()->json([
                'message' => 'Erro ao criar Pointe',
                'errors' => $validated->errors()
            ], 400);
        }

        $point = Point::create($validated);

        return response()->json([
            'message' => 'Pointe criado com sucesso',
            'data' => new PointResource($point)
        ], 201);

    }

}
