export default [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    // { path: '/developer', component: require('./components/Developer.vue').default },

    { path: '/users', component: require('./components/Users.vue').default },

    { path: '/expenses', component: require('./components/budget/Expenses.vue').default },
    { path: '/incomes', component: require('./components/budget/Incomes.vue').default },
    { path: '/categories', component: require('./components/budget/Categories.vue').default },

    // { path: '/product/tag', component: require('./components/product/Tag.vue').default },
    // { path: '/product/category', component: require('./components/product/Category.vue').default },

    { path: '*', component: require('./components/NotFound.vue').default }
];
