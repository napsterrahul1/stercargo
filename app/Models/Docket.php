<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docket extends Model
{
//    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'invoice_no', 'from_source','to_destination','freight_paid _by','sender','receiver','billing_party',
        'product','description','pcs','actual_weight','charge_weight','calc_on','charge','bill_paid_by','FOV','fuel','LR_charge','oda_charge','door_dly_charge','created_by','updated_by'



    ];
        protected $guarded = [];

        protected $table = 'docket';


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

     public function cities()
    {
        return $this->belongsTo('App\Area','from_source');
    }

 
   

}
