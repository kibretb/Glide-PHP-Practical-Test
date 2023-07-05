<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\OrganisationallyUniqueIdentifier;

class ImportIeeeOuiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-ieee-oui-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command that imports the latest version of the IEEE OUI data into a database';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $csv = Reader::createFromPath(__DIR__.'/Data/oui.csv', 'r');
        $csv->setHeaderOffset(0); 

        $stmt = Statement::create();
        $records = $stmt->process($csv);
        
        $ouiRecords = [];
        $time       = now();
        foreach ($records as $record) {
            $ouiRecord = [
                'registry'              =>    $record['Registry'],
                'assignment'            =>    $record['Assignment'],
                'organization_name'     =>    $record['Organization Name'],
                'organization_address'  =>    $record['Organization Address'],
                'created_at'            =>    $time,
                'updated_at'             =>   $time
            ];
            array_push($ouiRecords,$ouiRecord);
        }

        OrganisationallyUniqueIdentifier::truncate();

        foreach(array_chunk($ouiRecords,1500) as $ouiData){
            OrganisationallyUniqueIdentifier::insert($ouiData);
        }

    }
}
