<?php

namespace App\Exports;

use App\Models\PRS;
use Auth;
use App\Shipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PRSExportExcel implements FromCollection,WithHeadings,WithStyles
{
    public function __construct(string $states)
    {
        $this->states = $states;
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
            return ["Code", 'date','vehicle_number','vendor_name','hire_amount','docket','client_id','receiver_name','boy_name','total_docket','amount_to_be_collected','total_weight','Created At'];

    }
    
    public function collection()
    {
        if($this->states == 'all')
        {
            $shipments = PRS::where('id','!=', null );
        }else {
            $shipments = PRS::where('status_id', $this->states);
        }


            $shipments = $shipments->select('code','date','vehicle_number','vendor_name','hire_amount','docket','client_id','receiver_name','boy_name','total_docket','amount_to_be_collected','total_weight','created_at');

        $shipments = $shipments->orderBy('id','DESC')->get();

        foreach($shipments as $shipment)
        {
            $shipment->status_id = $shipment->getStatus();
            if(Auth::user()->user_type != 'customer')
            {
                $shipment->client_id = $shipment->client->name;
            }
            $shipment->created_at     = $shipment->created_at->format('Y-m-d');
        }

        return $shipments;
    }
}