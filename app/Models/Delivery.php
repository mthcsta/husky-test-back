<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FilterAndOrder;

class Delivery extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, FilterAndOrder;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'deliveryman_id',
        'status',
        'collect_point_id',
        'destination_point_id',
    ];

    protected $filters = [
        'client',
        'deliveryman',
        'status',
        'date_start',
        'date_end',
    ];

    private $statusArray = [
        'NOVO',          // atribuído assim que a entrega é criada
        'ENTREGANDO',  // atribuído quando o entregador está se deslocando para o ponto de destino
        'FINALIZADO',     // atribuído quando a entrega foi feita com sucesso
        'CANCELADO',     // atribuído quando a entrega não foi realizada
    ];
    private $statusDefault = 'NOVO';

    public static function getStatusArray()
    {
        return (new self)->statusArray;
    }
    public static function getStatusDefault()
    {
        return (new self)->statusDefault;
    }
    public function getStatusIndex()
    {
        return array_search($this->status, $this->statusArray);
    }
    public function getStatus()
    {
        return $this->statusArray[$this->status];
    }
    public static function getStatusByIndex($index)
    {
        return (new self)->statusArray[$index] ?? null;
    }
    public function setStatus($val)
    {
        if (!isset($this->getStatusArray()[$val])) {
            return;
        }
        $this->status = $this->getStatusArray()[$val];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function deliveryman()
    {
        return $this->belongsTo(Deliveryman::class, 'deliveryman_id');
    }

    public function collectPoint()
    {
        return $this->belongsTo(Point::class, 'collect_point_id');
    }

    public function destinationPoint()
    {
        return $this->belongsTo(Point::class, 'destination_point_id');
    }

    public function filter($array, $filter, $value)
    {
        switch ($filter) {
            case 'status':
                $statusValue = Delivery::getStatusByIndex($value);
                return $array->where('status', $statusValue);
                break;
            case 'deliveryman':
                return $array->whereHas('deliveryman', function ($query) use ($value) {
                    $query->where('name', 'like', "%$value%");
                });
                break;
            case 'client':
                return $array->whereHas('client', function ($query) use ($value) {
                    $query->where('name', 'like', "%$value%");
                });
                break;
            case 'date_start':
                return $array->where('updated_at', '>=', $value);
                break;
            case 'date_end':
                return $array->where('updated_at', '<=', $value);
                break;
        }
    }
}
