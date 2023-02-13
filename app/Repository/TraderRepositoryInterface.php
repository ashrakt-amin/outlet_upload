<?php
namespace App\Repository;
use App\Models\Trader;

interface TraderRepositoryInterface {

   /**
    * @param array $attributes
    * @return Trader
    */
   public function create(array $attributes): Trader;

   /**
    * @param $id
    * @return Trader
    */
    public function find($id): ?Trader;

    /**
     * Display the trader for him resource..
     *
     * @param  \App\Models\Trader  $trader
     * @return \Illuminate\Http\Response
     */
    public function trader();

   /**
    * @param id $attributes
    * @return Trader
    */
    public function edit($id, array $attributes);
    /**
    * @param $id
    * @return Trader
    */
    public function forgettingPassword(array $attributes);
 }
