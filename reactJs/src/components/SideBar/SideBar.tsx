import React, { useEffect, useState } from 'react';


import { useSelector } from 'react-redux';
import { StoreType } from "../../store/store";

import { NavLink } from 'react-router-dom';
import './SideBar.scss';
import { BiChevronUp } from 'react-icons/bi';

import logo from '../../images/icon.png'




const SideBar = ({ isOpen, toggle }: { isOpen: boolean, toggle: Function }) => {

  const is_auth = useSelector((state: StoreType) => state?.auth?.state)
  const is_register = useSelector((state: StoreType) => state?.register?.state)

  const [sideBarContainerToggel, setSideBarContainerToggel] = useState({
    opacity: 0,
    top: 100
  });



  useEffect(() => {
    let opacity;
    let top;
    let zindex;

    isOpen ? opacity = 100 : opacity = 0;
    isOpen ? top = 0 : top = -100;
    isOpen ? zindex = 999 : top = -999;

    const tab = {
      opacity: opacity,
      top: top,
      zIndex: zindex
    };

    setSideBarContainerToggel(tab)

  }, [isOpen]);

  return (
    <>
      <nav className="sideBarContainer" style={sideBarContainerToggel}>
        <div className="icon">
          <BiChevronUp className="closeIcon" onClick={() => toggle()} />
        </div>
        <div className="sideBarWrapper">

          <div className="sideBarMenu">
            <img src={logo} className="navBarLogo" alt='logo' />
            {(is_auth && is_register) &&
              <>
                <NavLink className="sideBarLink" onClick={() => toggle()} to="/Catalog">Catalog</NavLink>
                <NavLink className="sideBarLink" onClick={() => toggle()} to="/Desks">Desk</NavLink>
                <NavLink className="sideBarLink" onClick={() => toggle()} to="/Settings">Settings</NavLink>
              </>
            }
          </div>
          <div className="sideBtnWrapper">
            {is_auth ?
              <NavLink className="navBtnLink" onClick={() => toggle()} to="/logout">Logout</NavLink> :
              <>
                <NavLink className="navBtnLink" onClick={() => toggle()} to="/login">Sign In</NavLink>
                <NavLink className="navBtnLink" onClick={() => toggle()} to="/register">Sign up</NavLink>
              </>
            }
          </div>
        </div>
      </nav>
    </>
  );
};

export default SideBar;
