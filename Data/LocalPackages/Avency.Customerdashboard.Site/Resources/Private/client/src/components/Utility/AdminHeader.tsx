import React from 'react';

interface AdminHeaderProps {
    headline?: string;
    children?: React.ReactNode;
}
function AdminHeader({headline, children}: AdminHeaderProps) {
    return (
        <div className="flex justify-between pt-10 pl-12 pr-12 pb-10 bg-gray-100">
            <h2
                className={`mt-0`}
            >
                {headline}
            </h2>
            <div className="">{children}</div>
        </div>
    );
};

export default AdminHeader;
