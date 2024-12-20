import React from 'react'
import Stack from '@mui/material/Stack';
import CircularProgress from '@mui/material/CircularProgress';


function Loader() {
    return (
        <section className="formContainer">
            <div style={{ margin: "60px 0px", padding: "16px", display: "flex", flexDirection: "column", alignItems: "center", width: "120px", height: "120px", border: "1px solid #e9e9e9", borderRadius: "24px", boxShadow: "0px 4px 8px 0px rgb(0 0 0 / 20%)" }}>
                <div style={{ paddingBottom: 24, fontWeight: "bold" }}>Loading</div>
                <Stack sx={{ color: 'grey.500' }} spacing={2} direction="row">
                    <CircularProgress color="success" />
                </Stack>
            </div>
        </section>
    )
}

export default Loader

