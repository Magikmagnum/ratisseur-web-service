import { createSlice } from "@reduxjs/toolkit";

type RegisterInterface = null | boolean;


const getLocalValue = () => {

    const register = localStorage.getItem('register');

    if (register === 'true') {
        return true;
    }

    if (register === 'false') {
        return false;
    }

    return null;

}

const initialState: { state: RegisterInterface } = {
    state: getLocalValue(),
}



export const registerSlice = createSlice({
    name: 'register',
    initialState: initialState,
    reducers: {
        // path: 'register/addRegister'
        toggleRegister: (state, action) => {
            action.payload === null ? localStorage.removeItem('register') : localStorage.setItem('register', action.payload.toString())
            state.state = action.payload;
        },
    }
});

export const { toggleRegister } = registerSlice.actions;


export type RegisterType = { state: RegisterInterface }

