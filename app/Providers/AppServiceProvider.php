<?php

namespace App\Providers;

use App\Repository\LoanRepoInterface;
use App\Repository\LoanRepository;
use App\Repository\UserRepoInterface;
use App\Repository\UserRepository;
use App\Services\Loan\Loan;
use App\Services\Loan\LoanInterface;
use App\Services\Auth\Auth;
use App\Services\Auth\AuthInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(UserRepoInterface::class, UserRepository::class);
        $this->app->bind(AuthInterface::class, Auth::class);
        $this->app->bind(LoanRepoInterface::class, LoanRepository::class);
        $this->app->bind(LoanInterface::class, Loan::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
