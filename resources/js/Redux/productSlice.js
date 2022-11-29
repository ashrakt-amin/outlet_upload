
import { createSlice} from '@reduxjs/toolkit'


const initialState = {
  products: [],
}

export const productSlice = createSlice({
  name: 'customersData',
  initialState,
  reducers: {
    saveProduct: (state,action) => {
      state = state.products.push(action.payload);
    },
  },
})

// Action creators are generated for each case reducer function
export const { saveProduct} = productSlice.actions

export default productSlice.reducer 