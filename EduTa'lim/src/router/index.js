import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/store/auth'

const routes = [
  // ── Public (MainLayout) ──────────────────────────────────────
  {
    path: '/',
    component: () => import('@/layouts/MainLayout.vue'),
    children: [
      { path: '',          name: 'home',      component: () => import('@/pages/HomePage.vue'),        meta: { title: 'Bosh sahifa' } },
      { path: 'courses',   name: 'courses',   component: () => import('@/pages/CoursesPage.vue'),     meta: { title: 'Kurslar' } },
      { path: 'courses/:id', name: 'course-detail', component: () => import('@/pages/CourseDetailPage.vue'), meta: { title: 'Kurs' } },
      { path: 'teachers',  name: 'teachers',  component: () => import('@/pages/TeachersPage.vue'),    meta: { title: "O'qituvchilar" } },
    ],
  },

  // ── Auth ────────────────────────────────────────────────────
  { path: '/login',    name: 'login',    component: () => import('@/pages/LoginPage.vue'),    meta: { title: 'Kirish',             guest: true } },
  { path: '/register', name: 'register', component: () => import('@/pages/RegisterPage.vue'), meta: { title: "Ro'yxatdan o'tish", guest: true } },

  // ── User (protected) ────────────────────────────────────────
  {
    path: '/my',
    component: () => import('@/layouts/MainLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      { path: 'courses',   name: 'my-courses', component: () => import('@/pages/MyCoursesPage.vue'), meta: { title: 'Mening kurslarim' } },
      { path: 'dashboard', name: 'dashboard',  component: () => import('@/pages/DashboardPage.vue'), meta: { title: 'Dashboard' } },
      { path: 'profile',   name: 'profile',    component: () => import('@/pages/ProfilePage.vue'),   meta: { title: 'Profil' } },
    ],
  },

  // ── Admin ───────────────────────────────────────────────────
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    meta: { requiresAdmin: true },
    children: [
      { path: '',             name: 'admin',             component: () => import('@/pages/admin/AdminDashboard.vue'),   meta: { title: 'Admin' } },
      { path: 'enrollments',  name: 'admin-enrollments', component: () => import('@/pages/admin/AdminEnrollments.vue'), meta: { title: 'Yozilishlar' } },
      { path: 'users',        name: 'admin-users',        component: () => import('@/pages/admin/AdminUsers.vue'),       meta: { title: 'Foydalanuvchilar' } },
    ],
  },

  // ── 404 ─────────────────────────────────────────────────────
  { path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('@/pages/NotFoundPage.vue'), meta: { title: 'Sahifa topilmadi' } },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior: () => ({ top: 0 }),
})

router.beforeEach((to, _from, next) => {
  document.title = to.meta.title ? `${to.meta.title} | EduTa'lim` : "EduTa'lim"

  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.isAuthenticated) return next({ name: 'login' })
  if (to.meta.requiresAdmin && !auth.isAdmin)         return next({ name: 'home' })
  if (to.meta.guest && auth.isAuthenticated)           return next({ name: 'home' })

  next()
})

export default router
