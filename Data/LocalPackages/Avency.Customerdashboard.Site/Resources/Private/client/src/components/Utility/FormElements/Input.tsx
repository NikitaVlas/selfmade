import React from 'react';

interface InputProps {
    title: string;
    value: any;
    onChange?: (value: any) => void;
    optionalText?: string;
    placeholder?: string;
    inputType?: string;
    inputProps?: any;
    required?: boolean;
    disabled?: boolean;
    classList?: string;
}

function Input({
                   title = '',
                   value,
                   onChange,
                   optionalText = '',
                   placeholder = '',
                   inputType = '',
                   inputProps = {},
                   required = false,
                   disabled = false,
                   classList = '',
               }: InputProps) {
    return (
        <div className="prompt-input-wrapper">
            <span>{title}</span>
            <input
                className={`${classList} w-full p-3 border border-2 border-gray rounded-lg outline-none focus:border-primary transition-all duration-100 ${
                    disabled ? 'opacity-50 cursor-not-allowed' : ''
                }`}
                type={inputType}
                value={value}
                onChange={(e) => onChange ? onChange(e.target.value) : null}
                required={required}
                placeholder={placeholder}
                disabled={disabled}
                {...inputProps}
            />
            {optionalText !== '' && <p className="pt-2">{optionalText}</p>}
        </div>
    );
}
export default Input;
