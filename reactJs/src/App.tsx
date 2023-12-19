import React, { useState } from 'react';
import Router from './Router/Router';

import './App.scss';
import Footer from './components/Footer/Footer';
import NavBar from './components/NavBar/NavBar';
import SideBar from './components/SideBar/SideBar';
import { Provider } from "react-redux";
import { store } from "./store/store"
import { ThemeProvider } from '@mui/material';
import { createTheme, responsiveFontSizes } from '@mui/material/styles';

import { QueryClient, QueryClientProvider } from 'react-query';


import { theme } from "./helpers/theme"


// Create a client
const queryClient = new QueryClient()


let themes = createTheme(theme);
themes = responsiveFontSizes(themes);


function App() {

  const [sideBarIsOpen, setSideBarIsOpen] = useState(false);

  const toggleSideBar = () => {
    setSideBarIsOpen(sideBarIsOpen => !sideBarIsOpen)
  }

  return (
    <QueryClientProvider client={queryClient}>
      <Provider store={store}>
        <ThemeProvider theme={themes}>
          <div className="App">

            {/* <SideBar isOpen={sideBarIsOpen} toggle={toggleSideBar} /> */}
            {/* <NavBar toggle={toggleSideBar} /> */}

            <Router />
            {/* <Footer /> */}
          </div>
        </ThemeProvider>
      </Provider>
    </QueryClientProvider>
  );
}

export default App;





















export const themeOptions = createTheme({
  palette: {
    primary: {
      main: '#000000',
    },
    secondary: {
      main: '#00a3b4',
    },
    error: {
      main: '#ff2727',
    },
    background: {
      paper: '#efefef',
      default: '#ffffff',
    },
  },
  typography: {
    fontFamily: 'Outfit',
  },
  spacing: 16,
  shape: {
    borderRadius: 18,
  },
  transitions: {
    easing: {
      // This is the most common easing curve.
      easeInOut: 'cubic-bezier(0.4, 0, 0.2, 1)',
      // Objects enter the screen at full velocity from off-screen and
      // slowly decelerate to a resting point.
      easeOut: 'cubic-bezier(0.0, 0, 0.2, 1)',
      // Objects leave the screen at full velocity. They do not decelerate when off-screen.
      easeIn: 'cubic-bezier(0.4, 0, 1, 1)',
      // The sharp curve is used by objects that may return to the screen at any time.
      sharp: 'cubic-bezier(0.4, 0, 0.6, 1)',
    },
  },
});