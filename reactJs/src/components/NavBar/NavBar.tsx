import React from 'react';


import { useSelector } from 'react-redux';
import { StoreType } from "../../store/store";

import { NavLink } from 'react-router-dom';
import { BiMenuAltRight } from "react-icons/bi";

import logo from '../../images/icon.png'
import './NavBar.scss';

import Box from '@mui/material/Box';
import TextField from '@mui/material/TextField';


import InputAdornment from '@mui/material/InputAdornment';
import SearchIcon from '@mui/icons-material/Search';


const NavBar = ({ toggle }: { toggle: Function }) => {

  const is_auth = useSelector((state: StoreType) => state.auth.state)
  const is_register = useSelector((state: StoreType) => state?.register?.state)

  return (
    <nav className="nav">

      <div className="navBarContainer">
        <span className="navBarLogoContainer">
          <img src={logo} className="navBarLogo" alt='logo' />
        </span>
        <div className="mobileIcon" onClick={() => toggle()}>
          <BiMenuAltRight className="farBars" style={{ color: 'black' }} />
        </div>

        <ul className="navMenu">
          {(is_auth && is_register) &&
            <>
              <li className="navItem">
                <NavLink className="navLink" to="/Catalog">Catalog</NavLink>
              </li>
              <li className="navItem">
                <NavLink className="navLink" to="/Desks">Desk</NavLink>
              </li>
              <li className="navItem">
                <NavLink className="navLink" to="/Settings">Settings</NavLink>
              </li>
            </>
          }
        </ul>

        <Box
          component="form"
          sx={{
            '& .MuiTextField-root': { m: 1, width: '100%' },
          }}
          noValidate
          autoComplete="off"
          style={{ width: "40%" }}
        >
          {/* <TextField
            //error
            id="outlined-error"
            label="Recherche sur NiecePet"
            defaultValue=""
          /> */}

          {/* <TextField
            id="input-with-icon-textfield"
            label="TextField"
            InputProps={{
              startAdornment: (
                <InputAdornment position="start">
                  <AccountCircle />
                </InputAdornment>
              ),
            }}
            variant="standard"
          /> */}
          {/* <Box sx={{ display: 'flex', alignItems: 'flex-end' }}>
            <SearchIcon sx={{ color: 'action.active', mr: 1, my: 0.5 }} />
            <TextField id="input-with-sx" label="Recherche sur NiecePet" variant="standard" />
          </Box> */}
        </Box>

        <nav className="navBtn">
          {
            is_auth ?
              <NavLink className="navBtnLink" to="/logout">Logout</NavLink> :
              <>
                {/* <NavLink className="navBtnLink" to="/Login">Sign In</NavLink>
                <NavLink className="navBtnLink" to="/Register">Sign Up</NavLink> */}
              </>
          }
        </nav>
      </div >
    </nav >
  );
};

export default NavBar;
