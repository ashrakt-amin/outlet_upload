import { createSlice,createAsyncThunk} from '@reduxjs/toolkit'
import axios from 'axios'



export const getCartProducts = createAsyncThunk('cart/getCartProducts',async (arg,thunkAPI)=>{
try {
    let getToken = JSON.parse(localStorage.getItem("clTk"));
    const res = await axios.get(
        `${process.env.MIX_APP_URL}/api/carts`,
        {
            headers: {
                Authorization: `Bearer ${getToken}`,
            },
        }
    );
    console.log(res);
    return res;
} catch (er) {
    console.log(er);   
}
})

const initialState = {
  cardProcuts: [],
}

export const cartProdcuts = createSlice({
  name: 'cart',
  initialState,
  extraReducers: {
    [getCartProducts.fulfilled]: (state,action)=> {
        // console.log(action);
        console.log(state);
    }
  },
})

// Action creators are generated for each case reducer function
// export const { productsInCartNumber,productsInWishlistNumber} = cartProdcuts.actions

export default cartProdcuts.reducer 