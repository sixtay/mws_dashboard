<?php

namespace App;

use App\Order;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderItem
 * @package App\Models
 * @version November 2, 2017, 10:58 pm UTC
 *
 * @property \App\Models\Order order
 * @property integer order_id
 * @property string aSIN
 * @property string sKU
 * @property string itemStatus
 * @property string productName
 * @property integer quantity
 * @property string itemPrice
 */
class OrderItem extends Model
{
    use SoftDeletes;

    public $table = 'order_items';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
    protected $hidden = ['id','order_id','deleted_at', 'created_at', 'updated_at', 'aSIN', 'sKU', 'productName'];


    public $fillable = [
        'order_id',
        'aSIN',
        'sKU',
        'itemStatus',
        'productName',
        'quantity',
        'itemPrice'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'aSIN' => 'string',
        'sKU' => 'string',
        'itemStatus' => 'string',
        'productName' => 'string',
        'quantity' => 'string',
        'itemPrice' => 'array'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTotalAttribute()
    {
        if (empty($this->itemPrice)){
            return 0;
        }
        if (empty($this->itemPrice['component'][0])) {
            return (float) $this->itemPrice['component']['amount'];
        }
        return array_reduce(array_pluck($this->itemPrice['component'], 'amount'),function ($carry, $item)
        {
            $item  = (float) $item;
            return $carry + $item;
        }, 0);
    }
}
