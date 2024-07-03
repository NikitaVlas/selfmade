import {CustomerResponseType, CustomerType} from "../../types/mainType.ts";

export type SetCardActionType = ReturnType<typeof SetCustomerAC>
export type UpdateCustomerActionType = ReturnType<typeof UpdateCustomerAC>

type ActionType = SetCardActionType
    | UpdateCustomerActionType

export const initialState: CustomerType[] = []

export const customerReducer = (customers = initialState, action: ActionType) => {
    switch (action.type) {
        case 'SET-CUSTOMER':
            return action.payload.customers && action.payload.customers.items ? action.payload.customers.items.map(customer => ({...customer})) : customers;
        case 'UPDATE-CUSTOMER':
            return customers.map(customer => {
                if (customer.identifier === action.payload.customer.identifier) {
                    return {...customer, priority: action.payload.customer.priority, productManagerDefault: action.payload.customer.productManagerDefault}
                }
                return customer;
            })
        default:
            return customers
    }
}

export const SetCustomerAC = (customers: CustomerResponseType) => {
    return {
        type: 'SET-CUSTOMER',
        payload: {
            customers
        }
    } as const
}

export const UpdateCustomerAC = (customer: CustomerType) => {
    return {
        type: 'UPDATE-CUSTOMER',
        payload: {
            customer
        }
    } as const
}
