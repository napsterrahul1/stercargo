<?php

namespace App\Models;

use App\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Link
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Link whereUrl($value)
 * @mixin \Eloquent
 */
class Loading extends Model
{
    //
    //Shipment Types
    const PICKUP = 1;
    const DROPOFF = 2;

    //Payment Methods
    const CASH_METHOD = 1;
    const PAYPAL_METHOD = 2;

    //Payment Types
    const POSTPAID = 1;
    const PREPAID = 2;

    //Sort Types
    const LATEST = 1;
    const OLDEST = 2;

    //Shipments Status Manager
    const SAVED_STATUS = 1;
    const REQUESTED_STATUS = 2;
    const APPROVED_STATUS = 3;
    const CLOSED_STATUS = 4;
    const CAPTAIN_ASSIGNED_STATUS = 5;
    const RECIVED_STATUS = 6;
    const IN_STOCK_STATUS = 7;
    const PENDING_STATUS = 8;
    const DELIVERED_STATUS = 9;
    const SUPPLIED_STATUS = 10;
    const RETURNED_STATUS = 11;
    const RETURNED_ON_SENDER = 12;
    const RETURNED_ON_RECEIVER = 13;
    const RETURNED_STOCK = 14;
    const RETURNED_CLIENT_GIVEN = 15;

    const CLIENT_STATUS_CREATED = 1;
    const CLIENT_STATUS_READY = 2;
    const CLIENT_STATUS_IN_PROCESSING = 3;
    const CLIENT_STATUS_TRANSFERED = 4;
    const CLIENT_STATUS_RECEIVED_BRANCH = 5;
    const CLIENT_STATUS_OUT_FOR_DELIVERY = 6;
    const CLIENT_STATUS_DELIVERED = 7;
    const CLIENT_STATUS_SUPPLIED = 8;

    protected $table = 'prs';
    protected $fillable = ['date','vehicle_number','vendor_name','hire_amount','docket','client_id','receiver_name','boy_name','total_docket','amount_to_be_collected','total_weight'];

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id');
    }
    public function getStatus()
    {
        $result = null;
        foreach (Self::status_info() as $status) {
            $status_id = $this->status_id;
            $result = (isset($status['status']) && $status['status'] == $status_id) ? $status['text'] : null;
            if ($result != null) {
                return $result;
            }
        }

        return $result;
    }

    static public function status_info()
    {
        $array = [
            [
                'status' => Self::SAVED_STATUS,
                'text' => translate('Saved'),
                'route_name' => 'admin.shipments.saved.index',
                'permissions' => 1014,
                'route_url' => 'saved',
                'optional_params' => '/{type?}'
            ],

            [
                'status' => Self::REQUESTED_STATUS,
                'text' => translate('Requested'),
                'route_name' => 'admin.shipments.requested.index',
                'permissions' => 1015,
                'route_url' => 'requested',
                'optional_params' => '/{type?}'
            ],

            [
                'status' => Self::APPROVED_STATUS,
                'text' => translate('Approved'),
                'route_name' => 'admin.shipments.approved.index',
                'permissions' => 1016,
                'route_url' => 'approved'
            ],

            [
                'status' => Self::CLOSED_STATUS,
                'text' => translate('Closed'),
                'route_name' => 'admin.shipments.closed.index',
                'permissions' => 1017,
                'route_url' => 'closed'
            ],

            [
                'status' => Self::CAPTAIN_ASSIGNED_STATUS,
                'text' => translate('Assigned'),
                'route_name' => 'admin.shipments.assigned.index',
                'permissions' => 1018,
                'route_url' => 'assigned'
            ],

            [
                'status' => Self::RECIVED_STATUS,
                'text' => translate('Received'),
                'route_name' => 'admin.shipments.captain.given.index',
                'permissions' => 1019,
                'route_url' => 'deliverd-to-driver'
            ],
            [
                'status' => Self::DELIVERED_STATUS,
                'text' => translate('Deliverd'),
                'route_name' => 'admin.shipments.delivred.index',
                'permissions' => 1020,
                'route_url' => 'delivred'
            ],
            [
                'status' => Self::SUPPLIED_STATUS,
                'text' => translate('Supplied'),
                'route_name' => 'admin.shipments.supplied.index',
                'permissions' => 1041,
                'route_url' => 'supplied'
            ],

            [
                'status' => Self::RETURNED_STATUS,
                'text' => translate('Returned'),
                'route_name' => 'admin.shipments.returned.sender.index',
                'permissions' => 1024,
                'route_url' => 'returned-on-sender'
            ],

            [
                'status' => Self::RETURNED_STOCK,
                'text' => translate('Returned Stock'),
                'route_name' => 'admin.shipments.returned.stock.index',
                'permissions' => 1025,
                'route_url' => 'returned-stock'
            ],

            [
                'status' => Self::RETURNED_CLIENT_GIVEN,
                'text' => translate('Returned & Deliverd'),
                'route_name' => 'admin.shipments.returned.deliverd.index',
                'permissions' => 1026,
                'route_url' => 'returned-deliverd'
            ],



        ];
        return $array;
    }


}
