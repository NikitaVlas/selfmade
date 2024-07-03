import {UsedSystemType} from "../../types/mainType.ts";

export type SetUsedSystemActionType = ReturnType<typeof SetUsedSystemAC>
export type AddUsedSystemActionType = ReturnType<typeof AddUsedSystemAC>
export type UpdateUsedSystemActionType = ReturnType<typeof UpdateUsedSystemAC>

type ActionType = SetUsedSystemActionType
    | AddUsedSystemActionType
    | UpdateUsedSystemActionType

export const initialState: UsedSystemType[] = []

export const usedSystemReducer = (usedSystems = initialState, action: ActionType) => {
    switch (action.type) {
        case 'SET-USEDSYSTEM':
            return action.payload.usedSystems.map(usedSystem => ({...usedSystem}))
        case 'ADD-USEDSYSTEM':
            return [...usedSystems, action.payload.usedSystem]
        case 'UPDATE-USEDSYSTEM':
            return usedSystems.map(usedSystem => {
                return {...usedSystem,
                    title: action.payload.usedSystem.title,
                    usedVersion: action.payload.usedSystem.usedVersion,
                    cookie: action.payload.usedSystem.cookie,
                    trackingsTools: action.payload.usedSystem.trackingsTools,
                    sslCertificate: action.payload.usedSystem.sslCertificate,
                    urlLocal: action.payload.usedSystem.urlLocal,
                    urlPreview: action.payload.usedSystem.urlPreview,
                    urlLive: action.payload.usedSystem.urlLive,
                    productManager: action.payload.usedSystem.productManager,
                    leadDev: action.payload.usedSystem.leadDev,
                }
            })
        default:
            return usedSystems
    }
}

export const SetUsedSystemAC = (usedSystems: Array<UsedSystemType>) => {
    return {
        type: 'SET-USEDSYSTEM',
        payload: {
            usedSystems
        }
    } as const
}

export const AddUsedSystemAC = (usedSystem: UsedSystemType) => {
    return {
        type: 'ADD-USEDSYSTEM',
        payload: {
            usedSystem
        }
    } as const
}

export const UpdateUsedSystemAC = (usedSystem: UsedSystemType) => {
    return {
        type: 'UPDATE-USEDSYSTEM',
        payload: {
            usedSystem
        }
    } as const
}

