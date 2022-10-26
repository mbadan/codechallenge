<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Readcsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'velv:readcsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read and realtionship creation from csv';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {   
        $arrayTotalUsers = [];
        $row = 1;
        if (($handle = fopen("public/users.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
                $num = count($data);
                
                $row++;

                $emailId = '';
                $ageRange = '';
                $salaryBracket = '';
                $location = '';
                $contractType = '';
                $department = '';
                $seniority = '';

                for ($c=0; $c < $num; $c++) {
                    $userExplode = explode(';',$data[$c]);

                    $emailIds = file_get_contents("public/mapping/emails.json");
                    $jsonEmails = json_decode($emailIds);

                    foreach($jsonEmails as $e){
                        if($e->email == $userExplode[0]){
                            $emailId = $e->_id;
                        }
                    }

                    $filters = file_get_contents("public/mapping/filters.json");
                    $jsonFilters = json_decode($filters);

                    $ageRangeFilter = $jsonFilters[0];
                    $salaryBracketFilter = $jsonFilters[1];
                    $locationFilter = $jsonFilters[2];
                    $contractTypeFilter = $jsonFilters[3];
                    $departmentFilter = $jsonFilters[4];
                    $seniorityFilter = $jsonFilters[5];

                    foreach($ageRangeFilter->values as $a){
                        if($a->en == $userExplode[1]){
                            $ageRange = $a->_id;
                        }
                    }
                    
                    foreach($salaryBracketFilter->values as $s){
                        if($s->en == $userExplode[2]){
                            $salaryBracket = $s->_id;
                        }
                    }

                    foreach($locationFilter->values as $l){
                        if($l->en == $userExplode[3]){
                            $location = $l->_id;
                        }
                    }

                    foreach($contractTypeFilter->values as $co){
                        if($co->en == $userExplode[4]){
                            $contractType = $co->_id;
                        }
                    }

                    foreach($departmentFilter->values as $d){
                        if($d->en == $userExplode[5]){
                            $department = $d->_id;
                        }
                    }

                    foreach($seniorityFilter->values as $se){
                        if($se->en == $userExplode[6]){
                            $seniority = $se->_id;
                        }
                    }

                    $arrayUser = [
                        "_id" => $emailId,
                        "attributes" => [
                            $ageRange,
                            $salaryBracket,
                            $location,
                            $contractType,
                            $department,
                            $seniority
                        ]
                    ];
                    array_push($arrayTotalUsers,$arrayUser);

                }
                print_r(json_encode($arrayTotalUsers));
            }
            fclose($handle);
        }
    }
}
