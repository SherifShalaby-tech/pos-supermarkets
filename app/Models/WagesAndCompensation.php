<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class WagesAndCompensation extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function getPaymentTypes()
    {
        return [
            'salary' => __('lang.salary'),
            'paid_leave' => __('lang.paid_leave'),
            'paid_annual_leave' => __('lang.paid_annual_leave'),
            'commission' => __('lang.commission'),
            'annual_bonus' => __('lang.annual_bonus'),
            'annual_incentive' => __('lang.annual_incentive'),
            'recognition' =>  __('lang.recognition'),
            'other_reward' =>  __('lang.other_reward')
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class)->withDefault(['employee_name', '']);
    }
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
