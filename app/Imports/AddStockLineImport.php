<?php

namespace App\Imports;

use App\Models\AddStockLine;
use App\Models\Product;
use App\Models\System;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class AddStockLineImport implements ToModel, WithHeadingRow, WithValidation
{

    protected $transaction_id;

    /**
     * Constructor
     *
     * @param int $transaction_id
     * @return void
     */
    public function __construct($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $product = Product::leftjoin('variations', 'products.id', 'variations.product_id')
        ->where('variations.sub_sku', $row['product_code'])
        ->orWhere('products.sku', $row['product_code'])
        ->select(
            'products.id as product_id',
            'variations.id as variation_id',
            'purchase_price'
            )
            ->first();
            if(empty($product)){
                print_r($row['product_code']); die();

            }

        return new AddStockLine([
        'transaction_id' => $this->transaction_id,
        'product_id' => $product->product_id,
        'variation_id' => $product->variation_id,
        'quantity' => $row['quantity'],
        'sell_price' => $row['sell_price'],
        'final_cost' => $row['quantity'] * $row['purchase_price'],
        'purchase_price' => $row['purchase_price'],
        'sub_total' => $row['quantity'] * $row['purchase_price'],
        'batch_number' => $row['batch_number'],
        'manufacturing_date' => !empty($row['manufacturing_date']) ? $this->transformDate($row['manufacturing_date']): null,
        'expiry_date' => !empty($row['expiry_date']) ? $this->transformDate($row['expiry_date']) : null,
        'expiry_warning' => $row['expiry_warning'],
        'convert_status_expire' => $row['convert_status_expire'],
    ]);
    }

    public function rules(): array
    {
        return [
            'product_code' => 'required',
            'quantity' => 'required',
            'purchase_price' => 'required',
            'sell_price' => 'required',
            'batch_number' => 'required',
            'manufacturing_date' => 'required',
            'expiry_date' => 'required',
            'expiry_warning' => 'required',
            'convert_status_expire' => 'required',
        ];
    }

    public function uf_date($date, $time = false)
    {

        $date_format = 'm/d/Y';
        $mysql_format = 'Y-m-d';
        if ($time) {
            if (System::getProperty('time_format') == 12) {
                $date_format = $date_format . ' h:i A';
            } else {
                $date_format = $date_format . ' H:i';
            }
            $mysql_format = 'Y-m-d H:i:s';
        }
        return !empty($date_format) ? Carbon::createFromFormat($date_format, $date)->format($mysql_format) : null;
    }
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->toDateString();
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}
