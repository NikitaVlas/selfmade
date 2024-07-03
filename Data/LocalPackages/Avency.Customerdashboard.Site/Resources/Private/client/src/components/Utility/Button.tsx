import React from 'react';

interface ButtonProps {
    variant?: keyof typeof ButtonVariant;
    children: React.ReactNode;
    className?: string;
    onClick?: () => void;
}

enum ButtonVariant {
    primary ='primary',
    secondary = 'secondary',
    disabled = 'disabled',
    primaryOutlined = 'primaryOutlined',
    secondaryOutlined = 'secondaryOutlined',
    disabledOutlined = 'disabledOutlined',
    primaryRound = 'primaryRound',
    secondaryRound = 'secondaryRound',
    disabledRound = 'disabledRound'
}

function Button({
    variant = 'primary',
    children,
    className = '',
    onClick
}: ButtonProps) {
    const baseStyles: string = 'my-1 mr-1 leading-[25px] transition duration-200 ease-in-out';

    let variantStyles: string = '';
    switch (variant) {
        case ButtonVariant.secondary:
            variantStyles = ' pt-[15px] pb-[11px] px-[25px] text-[18px] bg-secondary text-white rounded-md hover:bg-secondary-hover focus:outline-none';
            break;
        case ButtonVariant.disabled:
            variantStyles = ' pt-[15px] pb-[11px] px-[25px] text-[18px] bg-gray-200 text-white rounded-md focus:outline-none';
            break;
        case ButtonVariant.primaryOutlined:
            variantStyles = ' pt-[15px] pb-[11px] px-[25px] text-[18px] border-2 border-primary text-primary rounded-md hover:bg-primary hover:text-white focus:outline-none';
            break;
        case ButtonVariant.secondaryOutlined:
            variantStyles = ' pt-[15px] pb-[11px] px-[25px] text-[18px] border-2 border-gray-400 text-gray-400 rounded-md hover:bg-gray-400 hover:text-white focus:outline-none';
            break;
        case ButtonVariant.disabledOutlined:
            variantStyles = ' pt-[15px] pb-[11px] px-[25px] text-[18px] border-2 border-gray-200 text-gray-200 rounded-md focus:outline-none';
            break;
        case ButtonVariant.primaryRound:
            variantStyles = ' p-[12px] text-[21px] bg-primary text-white rounded-full hover:bg-primary-hover focus:outline-none';
            break;
        case ButtonVariant.secondaryRound:
            variantStyles = ' p-[12px] text-[21px] bg-secondary text-white rounded-full hover:bg-secondary-hover focus:outline-none';
            break;
        case ButtonVariant.disabledRound:
            variantStyles = ' p-[12px] text-[21px] bg-gray-200 text-white rounded-full focus:outline-none';
            break;
        default:
            // case ButtonVariant.primary:
            variantStyles = ' pt-[15px] pb-[11px] px-[25px] text-[18px] bg-primary text-white rounded-md hover:bg-primary-hover focus:outline-none';
            break;
    }

    return (
        <button
            className={`${baseStyles} ${variantStyles} ${className}`}
            onClick={() => onClick && onClick()}
        >
            {children}
        </button>
    );
}

export default Button;
