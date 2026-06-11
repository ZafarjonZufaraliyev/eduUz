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
      { path: 'courses/:id/lessons/:lessonId', name: 'lesson', component: () => import('@/pages/LessonPage.vue'), meta: { title: 'Dars' } },
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
      { path: 'tasks',     name: 'my-tasks',   component: () => import('@/pages/MyTasksPage.vue'),   meta: { title: 'Topshiriqlarim' } },
    ],
  },

  // ── Teacher ─────────────────────────────────────────────────
  {
    path: '/teacher',
    component: () => import('@/layouts/TeacherLayout.vue'),
    meta: { requiresTeacher: true },
    children: [
      { path: '',                                  name: 'teacher',             component: () => import('@/pages/teacher/TeacherDashboard.vue'),    meta: { title: "O'qituvchi" } },
      { path: 'courses',                           name: 'teacher-courses',     component: () => import('@/pages/teacher/TeacherCoursesPage.vue'),  meta: { title: 'Kurslarim' } },
      { path: 'courses/:courseId/lessons',         name: 'teacher-lessons',     component: () => import('@/pages/teacher/TeacherLessonsPage.vue'),  meta: { title: 'Darslar' } },
      { path: 'tasks',                             name: 'teacher-tasks',       component: () => import('@/pages/teacher/TeacherTasksPage.vue'),    meta: { title: 'Topshiriqlar' } },
      { path: 'tasks/:taskId/submissions',         name: 'teacher-submissions', component: () => import('@/pages/teacher/TeacherSubmissionsPage.vue'),    meta: { title: 'Javoblar' } },
      { path: 'submissions',                       name: 'teacher-all-subs',    component: () => import('@/pages/teacher/TeacherAllSubmissionsPage.vue'), meta: { title: 'Barcha javoblar' } },
    ],
  },

  // ── Admin ───────────────────────────────────────────────────
  {
    path: '/admin',
    component: () => import('@/layouts/AdminLayout.vue'),
    meta: { requiresAdmin: true },
    children: [
      { path: '',                                  name: 'admin',              component: () => import('@/pages/admin/AdminDashboard.vue'),              meta: { title: 'Admin' } },
      { path: 'enrollments',                       name: 'admin-enrollments',  component: () => import('@/pages/admin/AdminEnrollments.vue'),            meta: { title: 'Yozilishlar' } },
      { path: 'users',                             name: 'admin-users',         component: () => import('@/pages/admin/AdminUsers.vue'),                  meta: { title: 'Foydalanuvchilar' } },
      { path: 'courses',                           name: 'admin-courses',       component: () => import('@/pages/teacher/TeacherCoursesPage.vue'),        meta: { title: 'Kurslar' } },
      { path: 'courses/:courseId/lessons',         name: 'admin-lessons',       component: () => import('@/pages/teacher/TeacherLessonsPage.vue'),        meta: { title: 'Darslar' } },
      { path: 'tasks',                             name: 'admin-tasks',         component: () => import('@/pages/teacher/TeacherTasksPage.vue'),          meta: { title: 'Topshiriqlar' } },
      { path: 'tasks/:taskId/submissions',         name: 'admin-submissions',      component: () => import('@/pages/teacher/TeacherSubmissionsPage.vue'),    meta: { title: 'Javoblar' } },
      { path: 'submissions',                       name: 'admin-all-subs',         component: () => import('@/pages/teacher/TeacherAllSubmissionsPage.vue'), meta: { title: 'Barcha javoblar' } },
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
  document.title = to.meta.title ? `${to.meta.title} | KasibXunar` : "KasibXunar"

  const auth = useAuthStore()

  if (to.meta.requiresAuth    && !auth.isAuthenticated)                    return next({ name: 'login' })
  if (to.meta.requiresAdmin   && !auth.isAdmin)                            return next({ name: 'home' })
  if (to.meta.requiresTeacher && !auth.isTeacher && !auth.isAdmin)         return next({ name: 'home' })
  if (to.meta.guest           && auth.isAuthenticated)                     return next({ name: 'home' })

  next()
})

export default router
