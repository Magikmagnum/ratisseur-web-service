import { createSlice } from "@reduxjs/toolkit";

type AuthInterface = {
    token: string,
    id: number
};

const auth = localStorage.getItem('user');

const initialState: { state: AuthInterface | false } = {
    state: (auth !== null && auth !== "false") ? JSON.parse(auth) : false,
}

export const authSlice = createSlice({
    name: 'auth',
    initialState: initialState,
    reducers: {
        toggleAuth: (state, action) => {
            localStorage.setItem('user', JSON.stringify(action.payload))
            state.state = action.payload;
        },
    }
});


export const { toggleAuth } = authSlice.actions;


export type AuthType = { state: AuthInterface }