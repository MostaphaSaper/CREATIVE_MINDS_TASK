import Dashbord from "./components/Dashbord.vue";
import ListAppointments from './pages/appointments/ListAppointments.vue';
import ListUsers from './pages/users/ListUsers.vue';
import UpdateSettings from './pages/settings/UpdateSettings.vue';
import UpdateProfile from './pages/profile/UpdateProfile.vue';
export default[

    {
        path:'/admin/dashbord',
        name:'admin.dashbord',
        component: Dashbord,
    },
    {
        path:'/admin/appointments',
        name:'admin.appointments',
        component: ListAppointments,
    },
    {
        path:'/admin/users',
        name:'admin.users',
        component: ListUsers,
    },
    {
        path:'/admin/settings',
        name:'admin.settings',
        component: UpdateSettings,
    },
    {
        path:'/admin/profile',
        name:'admin.profile',
        component: UpdateProfile,
    },

]
