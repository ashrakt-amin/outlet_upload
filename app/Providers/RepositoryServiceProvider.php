<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\SizeRepository;
use App\Repository\SizeRepositoryInterface;
use App\Repository\ColorRepositoryInterface;
use App\Repository\Eloquent\ColorRepository;
use App\Repository\Eloquent\TraderRepository;
use App\Repository\TraderRepositoryInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\Eloquent\CategoryRepository;
use App\Repository\Eloquent\ProjectRepository;
use App\Repository\EloquentRepositoryInterface;
use App\Repository\ProjectRepositoryInterface;

/**
* Class RepositoryServiceProvider
* @package App\Providers
*/
class RepositoryServiceProvider extends ServiceProvider
{
   /**
    * Register services.
    *
    * @return void
    */
   public function register()
   {
       $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
       $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
       $this->app->bind(TraderRepositoryInterface::class, TraderRepository::class);
       $this->app->bind(ColorRepositoryInterface::class, ColorRepository::class);
       $this->app->bind(SizeRepositoryInterface::class, SizeRepository::class);
       $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
   }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
