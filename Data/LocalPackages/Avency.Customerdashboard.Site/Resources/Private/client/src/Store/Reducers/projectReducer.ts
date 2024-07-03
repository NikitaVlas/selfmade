import {ProjectType} from "../../types/mainType.ts";

export type SetProjectActionType = ReturnType<typeof SetProjectAC>

type ActionType = SetProjectActionType

export const initialState: ProjectType[] = []

export const projectReducer = (projects = initialState, action: ActionType) => {
    switch (action.type) {
        case 'SET-PROJECT':
            return action.payload.projects.map(project => ({...project}))
        default:
            return projects
    }
}

export const SetProjectAC = (projects: Array<ProjectType>) => {
    return {
        type: 'SET-PROJECT',
        payload: {
            projects
        }
    } as const
}
