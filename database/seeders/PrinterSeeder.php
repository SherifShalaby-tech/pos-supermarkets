<?php

namespace Database\Seeders;

use App\Models\Printer;
use Illuminate\Database\Seeder;

class PrinterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $printers = [
            ['id' => 1,'name' => 'printer1'],
            ['id' => 2,'name' => 'printer2'],
        ];

        foreach ($printers as $printer){
            $printer_create = Printer::create($printer);
        }
    }
}
