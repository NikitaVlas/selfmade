import React from 'react';

interface CustomerFormTitleProps {
    title: string;
    classList?: string
}

function CustomerFormTitle({title, classList}: CustomerFormTitleProps) {
    return (
        <h6 className={classList}>
            {title}
        </h6>
    );
}

export default CustomerFormTitle;
