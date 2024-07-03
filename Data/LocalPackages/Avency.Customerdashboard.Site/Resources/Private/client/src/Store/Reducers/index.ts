import {combineReducers} from 'redux';
import {customerReducer} from "./customerReducer.ts";
import {personReducer} from "./personReducer.ts";
import {projectReducer} from "./projectReducer.ts";
import {usedSystemReducer} from "./usedSystemReducer.ts";

const rootReducer = combineReducers({
    customer: customerReducer,
    person: personReducer,
    project: projectReducer,
    usedSystem: usedSystemReducer,
});

export default rootReducer;
