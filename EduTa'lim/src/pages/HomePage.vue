<template>
  <div class="overflow-x-hidden">

    <!-- ╔══════════════════════════════════════════════════════╗ -->
    <!-- ║                      HERO                           ║ -->
    <!-- ╚══════════════════════════════════════════════════════╝ -->
    <section class="relative min-h-screen flex items-center bg-gradient-to-br from-slate-950 via-[#0f172a] to-slate-900 overflow-hidden">

      <!-- Background grid pattern -->
      <div class="absolute inset-0 opacity-[0.03]"
        style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 48px 48px;">
      </div>

      <!-- Glow blobs -->
      <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-primary-600/20 rounded-full blur-[120px] pointer-events-none" />
      <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-indigo-600/15 rounded-full blur-[100px] pointer-events-none" />

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-0 w-full">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

          <!-- ── Left: Copy ── -->
          <div class="space-y-8">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full border border-primary-500/30 bg-primary-500/10 text-primary-300 text-sm font-medium">
              <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75" />
                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-400" />
              </span>
              O'zbekistoning №1 kasbiy ko'nikmalar platformasi
            </div>

            <!-- Headline -->
            <div class="space-y-3">
              <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                Kasb-hunar
              </h1>
              <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-primary-400 via-blue-400 to-indigo-400">
                o'rganing
              </h1>
            </div>

            <p class="text-lg sm:text-xl text-slate-400 leading-relaxed max-w-md">
              Professional murabbiylardan amaliy bilim oling. Kasbingizni rivojlantiring va karyerangizni yuksaltiring.
            </p>

            <!-- CTA buttons -->
            <div class="flex flex-col sm:flex-row items-start gap-4 pt-2">
              <RouterLink
                to="/courses"
                class="group inline-flex items-center gap-2.5 px-7 py-4 bg-primary-600 hover:bg-primary-500 text-white text-base font-bold rounded-2xl transition-all duration-200 shadow-lg shadow-primary-600/40 hover:shadow-primary-500/50 hover:-translate-y-0.5"
              >
                Kurslarni ko'rish
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
              </RouterLink>

              <button
                class="inline-flex items-center gap-3 px-7 py-4 text-white font-medium rounded-2xl border border-white/10 hover:border-white/20 bg-white/5 hover:bg-white/10 transition-all duration-200"
                @click="scrollToSection('courses')"
              >
                <span class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/10">
                  <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                  </svg>
                </span>
                Demo ko'rish
              </button>
            </div>

            <!-- Trust badges -->
            <div class="flex flex-wrap items-center gap-5 pt-2">
              <div v-for="trust in trustBadges" :key="trust.text" class="flex items-center gap-2 text-slate-400 text-sm">
                <span class="text-green-400">{{ trust.icon }}</span>
                {{ trust.text }}
              </div>
            </div>
          </div>

          <!-- ── Right: Illustration ── -->
          <div class="relative flex items-center justify-center">
            <!-- Main code editor illustration -->
            <div class="relative w-full max-w-lg">
              <!-- Editor window -->
              <div class="bg-[#1e2433] rounded-2xl shadow-2xl border border-white/5 overflow-hidden">
                <!-- Title bar -->
                <div class="flex items-center gap-2 px-4 py-3 bg-[#161b27] border-b border-white/5">
                  <span class="w-3 h-3 rounded-full bg-red-500/80" />
                  <span class="w-3 h-3 rounded-full bg-yellow-500/80" />
                  <span class="w-3 h-3 rounded-full bg-green-500/80" />
                  <span class="flex-1 text-center text-xs text-slate-500 font-mono">index.vue</span>
                </div>
                <!-- Code area -->
                <div class="p-5 font-mono text-sm leading-relaxed select-none">
                  <div class="flex gap-4">
                    <!-- Line numbers -->
                    <div class="text-slate-600 text-right space-y-1 text-xs pt-0.5 select-none">
                      <div v-for="n in 14" :key="n">{{ n }}</div>
                    </div>
                    <!-- Code -->
                    <div class="space-y-1 text-xs overflow-hidden">
                      <div><span class="text-pink-400">&lt;template&gt;</span></div>
                      <div class="pl-4"><span class="text-pink-400">&lt;div</span> <span class="text-blue-300">class</span><span class="text-white">=</span><span class="text-green-300">"hero"</span><span class="text-pink-400">&gt;</span></div>
                      <div class="pl-8"><span class="text-pink-400">&lt;h1&gt;</span><span class="text-white">{{ heroText }}</span><span class="animate-pulse text-primary-400">|</span><span class="text-pink-400">&lt;/h1&gt;</span></div>
                      <div class="pl-8"><span class="text-pink-400">&lt;button</span> <span class="text-blue-300">@click</span><span class="text-white">=</span><span class="text-green-300">"start"</span><span class="text-pink-400">&gt;</span></div>
                      <div class="pl-12"><span class="text-white">Boshlash</span></div>
                      <div class="pl-8"><span class="text-pink-400">&lt;/button&gt;</span></div>
                      <div class="pl-4"><span class="text-pink-400">&lt;/div&gt;</span></div>
                      <div><span class="text-pink-400">&lt;/template&gt;</span></div>
                      <div class="mt-1"><span class="text-pink-400">&lt;script</span> <span class="text-blue-300">setup</span><span class="text-pink-400">&gt;</span></div>
                      <div class="pl-4"><span class="text-blue-400">import</span> <span class="text-white">&#123; ref &#125;</span> <span class="text-blue-400">from</span> <span class="text-green-300">'vue'</span></div>
                      <div class="pl-4"><span class="text-blue-400">const</span> <span class="text-indigo-300">started</span> <span class="text-white">= ref(</span><span class="text-orange-400">false</span><span class="text-white">)</span></div>
                      <div class="pl-4"><span class="text-yellow-300">start</span><span class="text-white">() &#123;</span> <span class="text-indigo-300">started</span><span class="text-white">.value = </span><span class="text-orange-400">true</span> <span class="text-white">&#125;</span></div>
                      <div><span class="text-pink-400">&lt;/script&gt;</span></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Floating notification: new student -->
              <div class="absolute -top-5 -right-5 bg-white rounded-2xl shadow-xl px-4 py-3 flex items-center gap-3 animate-float">
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0 text-lg">🎉</div>
                <div>
                  <p class="text-xs font-semibold text-gray-900">Yangi talaba!</p>
                  <p class="text-[11px] text-gray-500">Sardor Vue kursini boshladi</p>
                </div>
              </div>

              <!-- Floating rating card -->
              <div class="absolute -bottom-5 -left-5 bg-white rounded-2xl shadow-xl px-4 py-3 flex items-center gap-3 animate-float-slow">
                <div class="w-9 h-9 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0 text-lg">⭐</div>
                <div>
                  <p class="text-xs font-semibold text-gray-900">4.9 / 5.0</p>
                  <p class="text-[11px] text-gray-500">3 200+ sharh asosida</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Scroll indicator -->
      <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-slate-500 animate-bounce">
        <span class="text-xs tracking-widest uppercase">Scroll</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </div>
    </section>

    <!-- ╔══════════════════════════════════════════════════════╗ -->
    <!-- ║                   STATISTIKA                        ║ -->
    <!-- ╚══════════════════════════════════════════════════════╝ -->
    <section ref="statsRef" class="py-16 bg-white border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
          <div
            v-for="(stat, i) in stats"
            :key="stat.label"
            class="text-center group"
          >
            <div :class="`w-14 h-14 rounded-2xl ${stat.bg} flex items-center justify-center text-2xl mx-auto mb-4 group-hover:scale-110 transition-transform duration-300`">
              {{ stat.icon }}
            </div>
            <p class="text-3xl sm:text-4xl font-black text-gray-900 tabular-nums">
              {{ counters[i] }}<span class="text-primary-500">{{ stat.suffix }}</span>
            </p>
            <p class="text-sm text-gray-500 mt-1 font-medium">{{ stat.label }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ╔══════════════════════════════════════════════════════╗ -->
    <!-- ║                  KATEGORIYALAR                      ║ -->
    <!-- ╚══════════════════════════════════════════════════════╝ -->
    <section class="py-24 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-14">
          <span class="inline-block px-4 py-1.5 bg-primary-50 text-primary-600 text-xs font-bold rounded-full uppercase tracking-widest mb-4">
            Yo'nalishlar
          </span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
            Qaysi yo'nalish siz uchun?
          </h2>
          <p class="text-gray-500 mt-3 max-w-xl mx-auto">
            Eng ommabop 4 ta yo'nalishdan birini tanlang va karyerangizni boshlang
          </p>
        </div>

        <!-- 4 categories -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <RouterLink
            v-for="cat in categories"
            :key="cat.slug"
            :to="`/categories/${cat.slug}`"
            class="group relative bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 p-8 overflow-hidden text-center"
          >
            <!-- Background accent -->
            <div :class="`absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ${cat.gradientBg}`" />

            <div class="relative">
              <!-- Icon -->
              <div :class="`w-16 h-16 rounded-2xl ${cat.iconBg} flex items-center justify-center text-3xl mx-auto mb-5 group-hover:scale-110 transition-transform duration-300 shadow-sm`">
                {{ cat.emoji }}
              </div>

              <h3 :class="`text-lg font-bold text-gray-900 group-hover:${cat.textColor} transition-colors`">
                {{ cat.name }}
              </h3>
              <p class="text-sm text-gray-500 mt-2 leading-relaxed">{{ cat.desc }}</p>

              <!-- Course count -->
              <div class="mt-5 inline-flex items-center gap-1.5 text-xs font-semibold text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                {{ cat.count }} ta kurs
              </div>

              <!-- Arrow -->
              <div :class="`absolute top-6 right-6 w-8 h-8 rounded-xl bg-gray-50 group-hover:${cat.arrowBg} flex items-center justify-center transition-all duration-300`">
                <svg :class="`w-4 h-4 text-gray-300 group-hover:${cat.arrowColor} transition-colors`" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
              </div>
            </div>
          </RouterLink>
        </div>

        <div class="text-center mt-10">
          <RouterLink
            to="/categories"
            class="inline-flex items-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors"
          >
            Barcha kategoriyalarni ko'rish
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- ╔══════════════════════════════════════════════════════╗ -->
    <!-- ║               POPULAR COURSES                       ║ -->
    <!-- ╚══════════════════════════════════════════════════════╝ -->
    <section id="courses" class="py-24 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header row -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
          <div>
            <span class="inline-block px-4 py-1.5 bg-orange-50 text-orange-600 text-xs font-bold rounded-full uppercase tracking-widest mb-4">
              🔥 Mashhur
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
              Eng ko'p tanlanganlar
            </h2>
          </div>
          <RouterLink
            to="/courses"
            class="self-start sm:self-auto inline-flex items-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors whitespace-nowrap"
          >
            Barchasini ko'rish
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </RouterLink>
        </div>

        <!-- Course cards -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <RouterLink
            v-for="course in courses"
            :key="course.id"
            :to="`/courses/${course.id}`"
            class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col"
          >
            <!-- Course thumbnail -->
            <div :class="`relative h-44 bg-gradient-to-br ${course.gradient} flex items-center justify-center overflow-hidden`">
              <img
                v-if="course.image"
                :src="course.image"
                :alt="course.title"
                class="h-24 w-24 object-contain drop-shadow-md group-hover:scale-110 transition-transform duration-300"
              />
              <span v-else class="text-6xl select-none drop-shadow-lg group-hover:scale-110 transition-transform duration-300">
                {{ course.emoji }}
              </span>
              <!-- Badge -->
              <span
                v-if="course.badge"
                :class="[
                  'absolute top-3 left-3 px-2.5 py-1 text-xs font-bold rounded-lg',
                  course.badge === 'Bestseller' ? 'bg-orange-500 text-white' :
                  course.badge === 'Yangi'      ? 'bg-emerald-500 text-white' :
                                                  'bg-primary-600 text-white'
                ]"
              >
                {{ course.badge }}
              </span>
              <!-- Hover overlay -->
              <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <span class="text-white text-sm font-semibold px-4 py-2 bg-white/20 backdrop-blur-sm rounded-lg border border-white/30">
                  Ko'rish →
                </span>
              </div>
            </div>

            <!-- Card body -->
            <div class="p-5 flex flex-col flex-1">
              <!-- Category tag -->
              <span class="text-xs font-medium text-primary-600 mb-2">{{ course.category }}</span>

              <!-- Title -->
              <h3 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2 group-hover:text-primary-700 transition-colors mb-2">
                {{ course.title }}
              </h3>

              <!-- Teacher -->
              <div class="flex items-center gap-2 mt-1">
                <div class="w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center text-xs flex-shrink-0">
                  {{ course.teacherInitial }}
                </div>
                <span class="text-xs text-gray-500 truncate">{{ course.teacher }}</span>
              </div>

              <!-- Divider -->
              <div class="my-3 border-t border-gray-50" />

              <!-- Meta row -->
              <div class="flex items-center gap-3 text-xs text-gray-400 mb-3">
                <!-- Rating -->
                <span class="flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                  <span class="font-semibold text-gray-600">{{ course.rating }}</span>
                </span>
                <span class="text-gray-200">·</span>
                <!-- Lessons -->
                <span class="flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                  </svg>
                  {{ course.lessons }} dars
                </span>
                <span class="text-gray-200">·</span>
                <span>{{ course.duration }}</span>
              </div>

              <!-- Price -->
              <div class="mt-auto flex items-center justify-between">
                <div>
                  <span class="text-lg font-black text-gray-900">
                    {{ formatPrice(course.price) }}
                  </span>
                  <span v-if="course.oldPrice" class="ml-2 text-xs text-gray-400 line-through">
                    {{ formatPrice(course.oldPrice) }}
                  </span>
                </div>
                <span v-if="course.oldPrice" class="text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded-lg">
                  -{{ Math.round((1 - course.price / course.oldPrice) * 100) }}%
                </span>
              </div>
            </div>
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- ╔══════════════════════════════════════════════════════╗ -->
    <!-- ║                  O'QITUVCHILAR                      ║ -->
    <!-- ╚══════════════════════════════════════════════════════╝ -->
    <section class="py-24 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-14">
          <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full uppercase tracking-widest mb-4">
            Murabbiylar
          </span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
            Professional o'qituvchilar
          </h2>
          <p class="text-gray-500 mt-3 max-w-xl mx-auto">
            Sanoat tajribasiga ega mutaxassislardan bevosita o'rganing
          </p>
        </div>

        <!-- Teacher cards -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="teacher in teachers"
            :key="teacher.id"
            class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-7 text-center relative overflow-hidden"
          >
            <!-- Subtle top accent line -->
            <div :class="`absolute top-0 left-0 right-0 h-1 ${teacher.accent} opacity-0 group-hover:opacity-100 transition-opacity duration-300`" />

            <!-- Avatar -->
            <div class="relative inline-block mb-4">
              <div :class="`w-20 h-20 rounded-2xl bg-gradient-to-br ${teacher.avatarGradient} flex items-center justify-center text-4xl mx-auto shadow-md`">
                {{ teacher.avatar }}
              </div>
              <!-- Online dot -->
              <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-400 border-2 border-white rounded-full shadow-sm" />
            </div>

            <!-- Info -->
            <h3 class="font-bold text-gray-900 group-hover:text-primary-700 transition-colors">
              {{ teacher.name }}
            </h3>
            <p class="text-xs font-semibold text-primary-500 mt-1 mb-3">{{ teacher.role }}</p>

            <!-- Bio -->
            <p class="text-xs text-gray-500 leading-relaxed line-clamp-3">
              {{ teacher.bio }}
            </p>

            <!-- Stats -->
            <div class="mt-5 pt-5 border-t border-gray-50 grid grid-cols-3 gap-2">
              <div v-for="s in teacher.stats" :key="s.label" class="text-center">
                <p class="text-sm font-bold text-gray-900">{{ s.value }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">{{ s.label }}</p>
              </div>
            </div>

            <!-- Rating stars -->
            <div class="flex justify-center gap-0.5 mt-4">
              <svg
                v-for="i in 5"
                :key="i"
                class="w-3.5 h-3.5"
                :class="i <= Math.floor(teacher.rating) ? 'text-amber-400' : 'text-gray-200'"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
              <span class="text-xs text-gray-400 ml-1">{{ teacher.rating }}</span>
            </div>
          </div>
        </div>

        <div class="text-center mt-10">
          <RouterLink
            to="/teachers"
            class="inline-flex items-center gap-2 px-6 py-3 border border-gray-200 text-sm font-semibold text-gray-700 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all"
          >
            Barcha o'qituvchilarni ko'rish
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- ╔══════════════════════════════════════════════════════╗ -->
    <!-- ║                  FOOTER CTA                         ║ -->
    <!-- ╚══════════════════════════════════════════════════════╝ -->
    <section class="py-24 bg-white">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-indigo-700 rounded-[2rem] p-10 sm:p-16 text-center overflow-hidden shadow-2xl shadow-primary-900/30">
          <!-- Decorations -->
          <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4 pointer-events-none" />
          <div class="absolute bottom-0 left-0 w-56 h-56 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none" />
          <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[200px] bg-white/[0.03] rounded-full rotate-12 pointer-events-none" />

          <div class="relative space-y-6">
            <!-- Emoji badge -->
            <div class="inline-flex items-center gap-2 px-5 py-2 bg-white/10 border border-white/20 rounded-full text-white font-medium backdrop-blur-sm">
              🎓 Bugunoq boshlang — 7 kun bepul
            </div>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight">
              O'qishni boshlang.<br>
              <span class="text-primary-200">Karyerangizni o'zgartiring.</span>
            </h2>

            <p class="text-primary-100 text-lg max-w-xl mx-auto leading-relaxed">
              50 000+ talaba allaqachon o'z maqsadlari sari qadam tashladi.
              Siz ham ularga qo'shiling!
            </p>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
              <RouterLink
                to="/register"
                class="group inline-flex items-center gap-2.5 px-8 py-4 bg-white text-primary-700 font-bold text-base rounded-2xl hover:bg-primary-50 transition-all shadow-xl shadow-black/20 hover:-translate-y-0.5"
              >
                Bepul ro'yxatdan o'tish
                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
              </RouterLink>
              <RouterLink
                to="/courses"
                class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 text-white font-semibold text-base rounded-2xl border border-white/20 hover:bg-white/20 transition-all backdrop-blur-sm"
              >
                Kurslarga qarang
              </RouterLink>
            </div>

            <!-- Social proof -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-4">
              <!-- Avatars -->
              <div class="flex items-center -space-x-2">
                <div
                  v-for="(av, i) in ['👩‍💻','👨‍💻','🧑‍💻','👩‍🔬','👨‍🔬']"
                  :key="i"
                  class="w-9 h-9 rounded-full bg-white/20 border-2 border-primary-600 flex items-center justify-center text-base"
                >{{ av }}</div>
              </div>
              <p class="text-primary-100 text-sm">
                <span class="font-bold text-white">+50 000</span> talaba platformamizda o'qimoqda
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { RouterLink } from 'vue-router'

import imgVue    from '@/assets/vue.png'
import imgPython from '@/assets/python.png'
import imgReact  from '@/assets/react.png'
import imgDocker from '@/assets/docker.png'

// ── Typing animation ──────────────────────────────────────────────────────────
const heroText = ref('')
const fullText = "Kasb-hunar o'rganing"
let charIndex = 0
let typingTimer = null

function typeText() {
  if (charIndex < fullText.length) {
    heroText.value += fullText[charIndex]
    charIndex++
    typingTimer = setTimeout(typeText, 80)
  }
}

// ── Counter animation ─────────────────────────────────────────────────────────
const statsRef = ref(null)
const counters = ref([0, 0, 0, 0])

const stats = [
  { target: 50000, suffix: '+', label: "O'quvchilar", icon: '👨‍🎓', bg: 'bg-blue-50' },
  { target: 50,    suffix: '+', label: 'Kurslar',      icon: '📚',   bg: 'bg-green-50' },
  { target: 30,    suffix: '+', label: "O'qituvchilar",icon: '🧑‍🏫',  bg: 'bg-orange-50' },
  { target: 49,    suffix: ' ★',label: "O'rtacha baho",icon: '⭐',   bg: 'bg-yellow-50' },
]

function animateCounters() {
  stats.forEach((stat, i) => {
    const duration = 1600
    const steps = 60
    const increment = stat.target / steps
    let current = 0
    let step = 0

    const timer = setInterval(() => {
      step++
      current = Math.min(Math.round(increment * step), stat.target)
      // Special handling for rating (49 → 4.9)
      counters.value[i] = i === 3 ? (current / 10).toFixed(1) : current.toLocaleString('uz-UZ')
      if (step >= steps) clearInterval(timer)
    }, duration / steps)
  })
}

let observer = null

onMounted(() => {
  // Typing
  setTimeout(typeText, 400)

  // Counter observer
  observer = new IntersectionObserver(
    (entries) => {
      if (entries[0].isIntersecting) {
        animateCounters()
        observer.disconnect()
      }
    },
    { threshold: 0.3 }
  )
  if (statsRef.value) observer.observe(statsRef.value)
})

onUnmounted(() => {
  clearTimeout(typingTimer)
  observer?.disconnect()
})

// ── Helpers ───────────────────────────────────────────────────────────────────
function scrollToSection(id) {
  document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' })
}

function formatPrice(price) {
  return new Intl.NumberFormat('uz-UZ').format(price) + " so'm"
}

// ── Data ──────────────────────────────────────────────────────────────────────
const trustBadges = [
  { icon: '✓', text: 'Sertifikat beriladi' },
  { icon: '✓', text: '7 kun bepul' },
  { icon: '✓', text: 'Istalgan qurilma' },
]

const categories = [
  {
    slug: 'kasib-xunar',
    name: 'Kasib Xunar',
    emoji: '🔧',
    desc: "Elektrik, santexnik, qurilish, tikuvchilik va boshqa amaliy kasblar",
    count: 32,
    iconBg: 'bg-amber-50',
    gradientBg: 'bg-gradient-to-br from-amber-50/80 to-transparent',
    textColor: 'text-amber-600',
    arrowBg: 'bg-amber-50',
    arrowColor: 'text-amber-500',
  },
  {
    slug: 'frontend',
    name: 'Frontend',
    emoji: '🌐',
    desc: 'HTML, CSS, JavaScript, Vue.js, React va zamonaviy web texnologiyalar',
    count: 48,
    iconBg: 'bg-blue-50',
    gradientBg: 'bg-gradient-to-br from-blue-50/80 to-transparent',
    textColor: 'text-blue-600',
    arrowBg: 'bg-blue-50',
    arrowColor: 'text-blue-500',
  },
  {
    slug: 'backend',
    name: 'Backend',
    emoji: '⚙️',
    desc: 'Node.js, Python, Django, FastAPI va server texnologiyalar',
    count: 36,
    iconBg: 'bg-slate-50',
    gradientBg: 'bg-gradient-to-br from-slate-50/80 to-transparent',
    textColor: 'text-slate-700',
    arrowBg: 'bg-slate-50',
    arrowColor: 'text-slate-500',
  },
  {
    slug: 'mobile',
    name: 'Mobile',
    emoji: '📱',
    desc: "Flutter, React Native, Swift — iOS va Android ilovalar yaratish",
    count: 24,
    iconBg: 'bg-green-50',
    gradientBg: 'bg-gradient-to-br from-green-50/80 to-transparent',
    textColor: 'text-green-600',
    arrowBg: 'bg-green-50',
    arrowColor: 'text-green-500',
  },
]

const courses = [
  {
    id: 1,
    category: 'Frontend',
    title: "Vue.js 3 — Boshlang'ichdan Professional",
    teacher: 'Abdulloh Karimov',
    teacherInitial: 'A',
    image: imgVue,
    gradient: 'from-emerald-50 to-green-100',
    badge: 'Bestseller',
    rating: 4.9,
    lessons: 64,
    duration: '32 soat',
    price: 299000,
    oldPrice: 499000,
  },
  {
    id: 2,
    category: 'Backend',
    title: "Python Full Course — Loyihali o'qitish",
    teacher: 'Jasur Toshmatov',
    teacherInitial: 'J',
    image: imgPython,
    gradient: 'from-yellow-50 to-amber-100',
    badge: 'Yangi',
    rating: 4.8,
    lessons: 80,
    duration: '42 soat',
    price: 249000,
    oldPrice: null,
  },
  {
    id: 3,
    category: 'Frontend',
    title: "React 18 + TypeScript — To'liq kurs",
    teacher: 'Dilnoza Yusupova',
    teacherInitial: 'D',
    image: imgReact,
    gradient: 'from-cyan-50 to-blue-100',
    badge: null,
    rating: 4.9,
    lessons: 72,
    duration: '38 soat',
    price: 349000,
    oldPrice: 599000,
  },
  {
    id: 4,
    category: 'DevOps',
    title: 'Docker & Kubernetes — Amaliyotda',
    teacher: 'Rustam Nazarov',
    teacherInitial: 'R',
    image: imgDocker,
    gradient: 'from-indigo-50 to-blue-100',
    badge: 'Top',
    rating: 4.8,
    lessons: 55,
    duration: '28 soat',
    price: 319000,
    oldPrice: 449000,
  },
]

const teachers = [
  {
    id: 1,
    name: 'Abdulloh Karimov',
    role: 'Senior Frontend Engineer',
    avatar: '👨‍💻',
    avatarGradient: 'from-blue-100 to-indigo-100',
    accent: 'bg-gradient-to-r from-blue-400 to-indigo-500',
    bio: "Google-da 3 yil tajriba. Vue.js va React ekotizimida chuqur bilim. 8 ta kurs, 3 200+ talaba.",
    rating: 4.9,
    stats: [
      { value: '8',    label: 'Kurslar' },
      { value: '3.2K', label: 'Talaba' },
      { value: '4.9',  label: 'Reyting' },
    ],
  },
  {
    id: 2,
    name: 'Dilnoza Yusupova',
    role: 'React & TypeScript Dev',
    avatar: '👩‍💻',
    avatarGradient: 'from-pink-100 to-purple-100',
    accent: 'bg-gradient-to-r from-pink-400 to-purple-500',
    bio: "5 yillik tajriba. TypeScript va modern React patternsida mutaxassis. 2 800+ talabasi bor.",
    rating: 4.9,
    stats: [
      { value: '5',    label: 'Kurslar' },
      { value: '2.8K', label: 'Talaba' },
      { value: '4.9',  label: 'Reyting' },
    ],
  },
  {
    id: 3,
    name: 'Jasur Toshmatov',
    role: 'Python & Data Science',
    avatar: '👨‍🔬',
    avatarGradient: 'from-amber-100 to-orange-100',
    accent: 'bg-gradient-to-r from-amber-400 to-orange-500',
    bio: "O'zbek Data Science hamjamiyatining asoschisi. 10 ta kurs, 4 100+ talaba.",
    rating: 4.8,
    stats: [
      { value: '10',   label: 'Kurslar' },
      { value: '4.1K', label: 'Talaba' },
      { value: '4.8',  label: 'Reyting' },
    ],
  },
  {
    id: 4,
    name: 'Rustam Nazarov',
    role: 'DevOps & Cloud Architect',
    avatar: '🧑‍🚀',
    avatarGradient: 'from-emerald-100 to-teal-100',
    accent: 'bg-gradient-to-r from-emerald-400 to-teal-500',
    bio: "AWS Certified Architect. Docker, Kubernetes va CI/CD bo'yicha amaliy kurslar. 1 500+ talaba.",
    rating: 4.8,
    stats: [
      { value: '7',    label: 'Kurslar' },
      { value: '1.5K', label: 'Talaba' },
      { value: '4.8',  label: 'Reyting' },
    ],
  },
]
</script>

<style scoped>
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50%       { transform: translateY(-8px); }
}
@keyframes float-slow {
  0%, 100% { transform: translateY(0px); }
  50%       { transform: translateY(-6px); }
}

.animate-float      { animation: float 3s ease-in-out infinite; }
.animate-float-slow { animation: float-slow 4s ease-in-out infinite 1s; }
</style>
