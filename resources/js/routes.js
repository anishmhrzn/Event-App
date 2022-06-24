import EventIndex from "./components/Event/Index";
import EventCreate from "./components/Event/Form";

export default [
    {
        path: '/',
        name: 'home',
        component: EventIndex
    },
    {
        path: '/event/create',
        name: 'eventCreate',
        component: EventCreate
    },
    {
        path: '/event/:id/edit',
        name: 'eventEdit',
        component: EventCreate
    }
]
