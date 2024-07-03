import {PersonType} from "../../types/mainType.ts";

export type SetPersonActionType = ReturnType<typeof SetPersonAC>

type ActionType = SetPersonActionType
export const initialState: PersonType[] = []

export const personReducer = (persons = initialState, action: ActionType) => {
    switch (action.type) {
        case 'SET-PERSON':
            return action.payload.persons.map(person => ({...person}))
        default:
            return persons
    }
}

export const SetPersonAC = (persons: Array<PersonType>) => {
    return {
        type: 'SET-PERSON',
        payload: {
            persons
        }
    } as const
}
