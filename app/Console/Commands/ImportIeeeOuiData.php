<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\OrganisationallyUniqueIdentifier;
use Exception;
use Illuminate\Support\Facades\Storage;

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
        //update file with the latest data
        $disk = Storage::disk('public');
        $dataUrl = 'http://standards-oui.ieee.org/oui/oui.csv';

        $this->info('Updating IEEE OUI data file to its latest version...');
        try{
            $disk->put('Data/oui.csv', file_get_contents($dataUrl));
        }catch(Exception $ex){
            info($ex->getMessage());
            throw $ex;
        }

        $this->info('Storing the latest data into database...');
        $csv = Reader::createFromPath(storage_path('app/public/Data/oui.csv'), 'r');
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
