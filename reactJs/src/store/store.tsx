import { configureStore } from "@reduxjs/toolkit";
import { authSlice, AuthType } from "./authSlice";
import { registerSlice, RegisterType } from "./registerSlice";
import { identitySlice, IdentityType } from "./identitySlice";
import { contactSlice, ContactType } from "./contactSlice";


export const store = configureStore({
    reducer: {
        auth: authSlice.reducer,
        register: registerSlice.reducer,
        identity: identitySlice.reducer,
        contact: contactSlice.reducer
    },
});


export type StoreType = {
    auth: AuthType,
    register: RegisterType,
    identity: IdentityType,
    contact: ContactType,

}