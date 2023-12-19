import React from 'react';
import IconButton from '@mui/material/IconButton';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';

interface Props {
  title: string;
  onReset: () => void;
}

const HeaderWithCallbackComponent: React.FC<Props> = ({ title, onReset }) => {
  return (
    <div style={{ display: 'flex', alignItems: 'center' }}>
      <IconButton
        onClick={onReset}
        className='icon-back'
        style={{ width: '48px', height: '48px', marginRight: '24px' }}
      >
        <ArrowBackIcon />
      </IconButton>
      <div className="title titleCard">
        {title /* Les nutriments dont votre chat a besoin */}
      </div>
    </div>
  );
};

export default HeaderWithCallbackComponent;
