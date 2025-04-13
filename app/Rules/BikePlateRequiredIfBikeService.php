<?php

namespace App\Rules;

use App\Models\Service;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BikePlateRequiredIfBikeService implements ValidationRule
{
    protected $serviceId;

    public function __construct($serviceId)
    {
        $this->serviceId = $serviceId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $service = Service::find($this->serviceId);
        $isBikeService = $service && stripos($service->service_name, 'xe máy') !== false;

        if ($isBikeService && empty($value)) {
            $fail('Biển số xe là bắt buộc cho dịch vụ này.');
        }
    }
}
