import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import StudentView from '../views/students/View.vue'
import StudentCreate from '../views/students/Create.vue'
import StudentEdit from '../views/students/Edit.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/students',
      name: 'students',
      component: StudentView
    },
    {
      path: '/students/create',
      name: 'StudentCreate',
      component: StudentCreate
    },
    {
      path: '/students/edit/:id',
      name: 'StudentEdit',
      component: StudentEdit
    },
  ]
})

export default router
