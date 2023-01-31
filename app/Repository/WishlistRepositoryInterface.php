<?php
namespace App\Repository;

use App\Models\Wishlist;

interface WishlistRepositoryInterface
{
    /**
    * @param array $attributes
     */
    public function index(array $attributes);

   /**
    * @param array $attributes
    * @return Wishlist
    */
   public function toggleWishlist(array $attributes): Wishlist;

   /**
    * @param id $attributes
   * @return response
   */
   public function deleteAll(array $attributes): ?Wishlist;
}
