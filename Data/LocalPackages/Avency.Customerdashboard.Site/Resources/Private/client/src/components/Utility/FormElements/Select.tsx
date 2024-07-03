import React from 'react';

interface selectProps {
    children: React.ReactNode;
    value: any;
    onChange: (value: any) => void;
    classList?: string;
    disabled?: boolean;
    required?: boolean;
    title?: string
}

const Select = ({
                    children,
                    value,
                    onChange,
                    classList,
                    disabled = false,
                    required = false,
                    title = "",
                }: selectProps) => {
    return (
        <>
            <span>{title}</span>
            <select
                disabled={disabled}
                required={required}
                className={`bg-white w-full p-3 border border-2 border-gray text-gray rounded-lg outline-none focus:border-primary transition-all duration-100 ${classList} ${
                    disabled ? 'opacity-50 cursor-not-allowed' : ''
                }`}
                value={value}
                onChange={(e) => onChange(e.target.value)}
            >
                {children}
            </select>
        </>
    );
};

export default Select;
