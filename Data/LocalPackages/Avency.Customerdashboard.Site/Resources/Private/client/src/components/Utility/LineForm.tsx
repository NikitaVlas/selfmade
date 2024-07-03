import React from 'react';

interface LineFormProps {
    classList: string;
}

function LineForm({classList}: LineFormProps) {
    return (
        <div className={classList}>
        </div>
    );
}

export default LineForm;
