<?php

namespace App\Repository\Eloquent;

use App\Models\View;
use App\Repository\ViewRepositoryInterface;

class ViewRepository extends BaseRepository implements ViewRepositoryInterface
{
   /**
    * ViewRepository constructor.
    *
    * @param View $model
    */
   public function __construct(View $model)
   {
       parent::__construct($model);
   }
   
   /**
    * @param item_id $attributes
    * @return response
    */
    public function whatsAppClick($item_id)
    {
        if (!$this->getTokenName('user') && !$this->getTokenName('trader')) {
            $view = View::where(['item_id' => $item_id, 'client_id' => $this->getTokenId('client')])->first();
            if (!$this->getTokenName('client')) {
                $view = View::where(['item_id' => $item_id, 'client_id' => null])->first();
            }
            $view->whats_app_click = $view->whats_app_click + 1;
            return $view->update();
        }
    }
}
