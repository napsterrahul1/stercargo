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
        'date','invoice_no', 'from_source','to_destination','freight_paid _by','sender','receiver','billing_party',
        'product','description','pcs','actual_weight','charge_weight','calc_on','charge','bill_paid_by','FOV','fuel','LR_charge','oda_charge','door_dly_charge','created_by','updated_by','invoice_value','final_amount','total_amount','code','freight_paid_by','freight_rate','freight_amount','fov_charges','fuel_charges'



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
    }public function destinations()
    {
        return $this->belongsTo('App\Area','to_destination');
    }
    public static function docketWeight($id)
    {
        $doc= explode(",",$id);
        $weight = '';
        $weight = Docket::whereIn('id', $doc)->sum('charge_weight');
        
        return $weight;
    }
    public static function docketAmount($id)
    {
        $doc= explode(",",$id);
        $weight = '';
        $weight = Docket::whereIn('id', $doc)->sum('final_amount');
        
        return $weight;
    }

 
   

}
