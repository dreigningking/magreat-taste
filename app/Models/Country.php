<?php

namespace App\Models;

use App\Models\City;
use App\Models\Plan;

use App\Models\State;
use App\Models\CountryPrice;
use App\Models\TaskTemplate;
use App\Models\CountryBanking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'phone_code',
        'currency',
        'currency_symbol',
        'is_active',
    ];

    protected $connection = 'sqlite_countries';
    protected $table = 'countries'; // adjust table name if different
    public $appends = ['status'];

    public function getRouteKeyName(){
        return 'iso2';
    }
    public function states(){
        return $this->hasMany(State::class);
    }
    public function cities(){
        return $this->hasManyThrough(City::class,State::class,'country_id','state_id');
    }

    public function user_locations()
    {
        return $this->setConnection('mysql')->hasMany(UserLocation::class);
    }

    public function users(){
        return $this->setConnection('mysql')->hasMany(User::class);
    } 

    /**
     * Get the settings for this country.
     */
    public function setting()
    {
        return $this->setConnection('mysql')->hasOne(CountrySetting::class);
    }

    // --- Custom computed properties for settings completeness ---
    public function hasBankingSettings()
    {
        $settings = $this->setting;
        if (!$settings) return false;

        if ($settings->bank_account_storage === 'off_premises') {
            return true;
        }

        return !empty($settings->account_length)
            && !empty($settings->bank_verification_method)
            && !empty($settings->banking_fields);
    }

    public function hasTransactionSettings()
    {
        $settings = $this->setting;
        if (!$settings) return false;
        return !empty($settings->transaction_charges)
            && !empty($settings->withdrawal_charges)
            && !empty($settings->gateway);
    }

    public function hasTaskSettings()
    {
        $settings = $this->setting;
        if (!$settings) return false;
        return !empty($settings->feature_rate)
            && !empty($settings->urgent_rate)
            && !empty($settings->admin_monitoring_cost)
            && !empty($settings->system_monitoring_cost)
            && !empty($settings->invitee_commission_percentage)
            && !empty($settings->referral_earnings_percentage);
    }

    public function hasPlanPrices()
    {
        $plans = Plan::where('is_active', true)->get();
        foreach ($plans as $plan) {
            $price = CountryPrice::where('country_id', $this->id)
                ->where('priceable_type', Plan::class)
                ->where('priceable_id', $plan->id)
                ->first();
            if (!$price || empty($price->amount)) {
                return false;
            }
        }
        return true;
    }

    public function hasTemplatePrices()
    {
        $templates = TaskTemplate::where('is_active', true)->get();
        foreach ($templates as $template) {
            $price = CountryPrice::where('country_id', $this->id)
                ->where('priceable_type', TaskTemplate::class)
                ->where('priceable_id', $template->id)
                ->first();
            if (!$price || empty($price->amount)) {
                return false;
            }
        }
        return true;
    }

    public function hasNotificationEmails()
    {
        $settings = $this->setting;
        if (!$settings) return false;
        return !empty($settings->notification_emails) && is_array($settings->notification_emails) && count(array_filter($settings->notification_emails));
    }

    public function hasVerificationSettings()
    {
        $settings = $this->setting;
        if (!$settings) return false;
        if (empty($settings->verification_fields) || empty($settings->verification_provider)) {
            return false;
        }
        return true;
    }

    public function getStatusAttribute(){
        // if($this->hasBankingSettings() && $this->hasTransactionSettings() && $this->hasTaskSettings() && $this->hasPlanPrices() && $this->hasTemplatePrices() && $this->hasNotificationEmails() && $this->hasVerificationSettings()){
        //     return true;
        // }
        if($this->hasBankingSettings() && $this->hasTransactionSettings() && $this->hasTaskSettings()  && $this->hasNotificationEmails() && $this->hasVerificationSettings()){
            return true;
        }
        return false;
    }
}
