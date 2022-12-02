import { configureStore } from '@reduxjs/toolkit'
import  cartProdcuts  from './cartProducts'
import countInCartSlice from './countInCartSlice'
import productSlice from './productSlice'



export const store = configureStore({
  reducer: {
    products: productSlice,
    numberInCart:countInCartSlice,
    numberInWishlist:countInCartSlice,
    productsInCart: cartProdcuts
  },
  devTools:false,
  
})
