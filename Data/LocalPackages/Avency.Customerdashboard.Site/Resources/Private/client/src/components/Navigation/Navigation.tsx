import React from 'react';
import { NavLink } from 'react-router-dom'

function Navigation() {
    return (
        <div className="h-screen w-[300px] bg-gray text-white p-4">
            Customer Dashboard
            <ul className='mt-10'>
                <li className=''>
                    <NavLink
                        to={'/customerList'}
                        className=''
                    >
                        Kunden Liste
                    </NavLink>
                </li>
                <li>
                    <NavLink
                        to={'/troi'}
                    >
                        Troi Projekten
                    </NavLink>
                </li>
                <li>
                    <NavLink
                        to={'/systems'}
                    >
                        Verwendete Systeme
                    </NavLink>
                </li>
            </ul>
        </div>
    );
}

export default Navigation;
