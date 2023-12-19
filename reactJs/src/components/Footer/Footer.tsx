import * as React from 'react';
import './Footer.scss';
import { NavLink } from 'react-router-dom';




interface IFooterProps {
}

const Footer: React.FunctionComponent<IFooterProps> = (props) => {


  const FooterLink = ({ children }: { children: string }) => {
    return (
      <>
        <div className="footerLinkItems">
          <h1 className="footerLinkTitle">{children}</h1>
          <NavLink className="footerLink" to="/">Permanence les</NavLink>
          <NavLink className="footerLink" to="/">Permanence les</NavLink>
          <NavLink className="footerLink" to="/">Permanence les</NavLink>
          <NavLink className="footerLink" to="/">Permanence les</NavLink>
        </div>
      </>
    )
  }

  const FooterCard = ({ img }: { img: string }) => {
    return (
      <>
        <div className="footerCardItems">
          <img className="footerCardImg" src={img} alt="coucou" />
        </div>
      </>
    )
  }

  //src / components / Footer / Footer.tsx
  return (
    <footer>
      <div className="footerCardRounded">
        <h3>Nos partenair</h3>

        <div className="footerCardContainer">
          {/* <FooterCard img={imgp1} />
          <FooterCard img={imgp2} />
          <FooterCard img={imgp4} />
          <FooterCard img={imgp5} />
          <FooterCard img={imgp7} /> */}
        </div>

      </div>
      <div className="footerContainer">
        <div className="footerWrapper">
          <div className="footerLinkContainer">
            <div className="footerLinkWrapper">
              <FooterLink >About Us</FooterLink>
              <FooterLink >Contact Us</FooterLink>
              <FooterLink >Videos</FooterLink>
              <FooterLink >Sociale Media</FooterLink>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};


export default Footer;

