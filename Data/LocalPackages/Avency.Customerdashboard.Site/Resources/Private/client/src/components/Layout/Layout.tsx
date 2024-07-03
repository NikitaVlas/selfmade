import React from 'react';
import Navigation from "../Navigation/Navigation.tsx";

function Layout({children}) {
    return (
        <React.Fragment>
            <div className="flex">
                <Navigation/>
                {children}
            </div>
        </React.Fragment>

    );
}

export default Layout;
