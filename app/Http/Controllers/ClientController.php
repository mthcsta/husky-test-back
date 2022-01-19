<?php

namespace App\Http\Controllers;

use App\Models\Client;

use Illuminate\Http\Request;
use App\Http\Resources\ClientResource;
use App\Traits\PageNumber;


class ClientController extends Controller
{
    use PageNumber;

    public function index(Request $request)
    {
        // valida entradas do usuário
        $validated = $request->validate([
            'search' => 'nullable|string',
            'limit' => 'integer',
            'offset' => 'integer',
            'orders' => 'array',
        ]);

        // pega os valores da requisição ou seus valores padrão
        $search = $validated['search'] ?? '';
        $orders = $validated['orders'] ?? [];
        $limit = $validated['limit'] ?? 10;
        $offset = $validated['offset'] ?? 0;

        // calcula a página atual com base no offset e no limite
        $pageNumber = $this->pageNumber($offset, $limit);

        // captura os clientes que possuem o nome baseado no valor da busca
        $clients = Client::where('name', 'like', '%' . $search . '%');

        // adiciona order by baseado no array orders
        Client::addOrders($clients, $orders);

        // retorna os clientes com paginação
        $clientsPaginate = $clients->paginate($limit, ['*'], 'page', $pageNumber);

        return ClientResource::collection($clientsPaginate);
    }
    public function show($id)
    {
        return new ClientResource(Client::findOrFail($id));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        if (!$validated) {
            return response()->json([
                'message' => 'Erro ao criar cliente',
                'errors' => $validated->errors()
            ], 400);
        }

        $client = Client::create($validated);

        return response()->json([
            'message' => 'Cliente criado com sucesso',
            'data' => new ClientResource($client)
        ], 201);

    }

}
