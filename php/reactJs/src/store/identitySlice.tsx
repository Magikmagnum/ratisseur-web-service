import { createSlice } from "@reduxjs/toolkit";

// data type we can recive
type IdentityIterface = {
    id: number,
    name: string,
    sex: boolean,
    birthday: string
}

const identity = localStorage.getItem('identity');

// initiale state of slice reducer
const initialState: { state: IdentityIterface | false } = {
    state: (identity !== null) && identity !== 'false' ? JSON.parse(identity) : false,
}

// slice reducer
export const identitySlice = createSlice({
    name: 'identity',
    initialState: initialState,
    reducers: {
        editIdentity: (state, action) => {
            localStorage.setItem('identity', JSON.stringify(action.payload))
            state.state = action.payload;
        }
    }
});

// export reducer action 
export const { editIdentity } = identitySlice.actions;


export type IdentityType = {
    state: IdentityIterface | false
}
