<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\ItemRepository;
use App\Repository\Eloquent\SizeRepository;
use App\Repository\Eloquent\UnitRepository;
use App\Repository\Eloquent\ViewRepository;
use App\Repository\ItemRepositoryInterface;
use App\Repository\SizeRepositoryInterface;
use App\Repository\UnitRepositoryInterface;
use App\Repository\ViewRepositoryInterface;
use App\Repository\ColorRepositoryInterface;
use App\Repository\Eloquent\ColorRepository;
use App\Repository\Eloquent\LevelRepository;
use App\Repository\LevelRepositoryInterface;
use App\Repository\Eloquent\TraderRepository;
use App\Repository\TraderRepositoryInterface;
use App\Repository\Eloquent\PrivacyRepository;
use App\Repository\Eloquent\ProjectRepository;
use App\Repository\PrivacyRepositoryInterface;
use App\Repository\ProjectRepositoryInterface;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\Eloquent\CategoryRepository;
use App\Repository\Eloquent\WishlistRepository;
use App\Repository\EloquentRepositoryInterface;
use App\Repository\WishlistRepositoryInterface;
use App\Repository\Eloquent\MainProjectRepository;
use App\Repository\MainProjectRepositoryInterface;
use App\Repository\AdvertisementRepositoryInterface;
use App\Repository\Eloquent\AdvertisementRepository;
use App\Repository\Eloquent\TermsAndConditionRepository;
use App\Repository\TermsAndConditionRepositoryInterface;

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
       $this->app->bind(MainProjectRepositoryInterface::class, MainProjectRepository::class);
       $this->app->bind(LevelRepositoryInterface::class, LevelRepository::class);
       $this->app->bind(UnitRepositoryInterface::class, UnitRepository::class);
       $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
       $this->app->bind(PrivacyRepositoryInterface::class, PrivacyRepository::class);
       $this->app->bind(TermsAndConditionRepositoryInterface::class, TermsAndConditionRepository::class);
       $this->app->bind(AdvertisementRepositoryInterface::class, AdvertisementRepository::class);
       $this->app->bind(WishlistRepositoryInterface::class, WishlistRepository::class);
       $this->app->bind(ViewRepositoryInterface::class, ViewRepository::class);
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
