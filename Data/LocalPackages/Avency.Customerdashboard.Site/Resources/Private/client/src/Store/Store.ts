import {legacy_createStore} from 'redux';
import rootReducer from './Reducers';
import {TypedUseSelectorHook, useSelector} from "react-redux";

const Store = legacy_createStore(rootReducer);

export type AppRootStateType = ReturnType<keyof typeof rootReducer>

export const useAppSelector: TypedUseSelectorHook<AppRootStateType> = useSelector

export default Store;
