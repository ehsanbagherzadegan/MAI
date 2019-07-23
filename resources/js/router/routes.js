
const routes = [
    {
        path: '/',
        redirect: '/Main'
    },
	/* Begin Notify */
	{ path: '/Main',name:'Main', component: require('../components/layouts/Main.vue').default },
	{ path: '/test',name:'test', component: require('../components/Test.vue').default },
    /* End Notify */
    {
        path: '/login',
        redirect: '/login'
    },
];

export default routes;
