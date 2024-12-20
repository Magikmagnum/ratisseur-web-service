import { createSlice } from "@reduxjs/toolkit";

// data type we can recive
type ContactIterface = {
    id: number,
    email: string,
    phone: string
}

const contact = localStorage.getItem('contact');

// initiale state of slice reducer
const initialState: { state: ContactIterface | false } = {
    state: (contact !== null) && contact !== 'false' ? JSON.parse(contact) : false,
}

// slice reducer
export const contactSlice = createSlice({
    name: 'contact',
    initialState: initialState,
    reducers: {
        // path: 'contact/editContact'
        editContact: (state, action) => {
            localStorage.setItem('contact', JSON.stringify(action.payload))
            state.state = action.payload;
        }
    }
});

// export reducer action 
export const { editContact } = contactSlice.actions;


export type ContactType = {
    state: ContactIterface | false
}