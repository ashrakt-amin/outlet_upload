<?php
namespace App\Repository;

interface ViewRepositoryInterface {

    /**
     * @param item_id $attributes
     * @return response
     */
    public function whatsAppClick($item_id);
}
