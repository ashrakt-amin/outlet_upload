import { createSlice} from '@reduxjs/toolkit'


const initialState = {
  productsInCart: 0,
  productsInWishlist: 0,
}

export const countInCartSlice = createSlice({
  name: 'countInCartSlice',
  initialState,
  reducers: {
    productsInCartNumber: (state,action) => {
      state.productsInCart = action.payload;
    },
    productsInWishlistNumber: (state,action) => {
      state.productsInWishlist = action.payload;
    },
  },
})

// Action creators are generated for each case reducer function
export const { productsInCartNumber,productsInWishlistNumber} = countInCartSlice.actions

export default countInCartSlice.reducer 