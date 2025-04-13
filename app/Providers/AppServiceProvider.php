<?php

namespace App\Providers;

use App\Models\Contract;
use App\Models\Room;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use App\Models\Utility;
use App\Observers\ContractObserver;
use App\Observers\RoomObserver;
use App\Observers\StaffObserver;
use App\Observers\StudentObserver;
use App\Observers\UserObserver;
use App\Observers\UtilityObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Room::observe(RoomObserver::class);
        User::observe(UserObserver::class);
        Student::observe(StudentObserver::class);
        Staff::observe(StaffObserver::class);
        Utility::observe(UtilityObserver::class);
        Contract::observe(ContractObserver::class);
    }
}
