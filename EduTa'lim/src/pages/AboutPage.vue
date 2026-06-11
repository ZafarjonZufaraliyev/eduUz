<template>
  <div>
    <!-- Hero -->
    <section class="bg-gradient-to-br from-slate-900 to-primary-900 py-24 text-center">
      <div class="max-w-3xl mx-auto px-4">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-6">
          Biz haqimizda
        </h1>
        <p class="text-lg text-primary-200 leading-relaxed">
          KasibXunar — O'zbekistondagi eng zamonaviy va qulayliklar bilan to'ldirilgan online ta'lim platformasi.
          Bizning maqsadimiz — sifatli ta'limni hamma uchun qulay va arzon qilish.
        </p>
      </div>
    </section>

    <!-- Mission -->
    <section class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
          <div>
            <span class="text-xs font-bold text-primary-600 uppercase tracking-widest">Bizning maqsad</span>
            <h2 class="text-3xl font-extrabold text-gray-900 mt-3 mb-6">
              Ta'limni demokratlashtirish — hammaga teng imkoniyat
            </h2>
            <div class="space-y-4 text-gray-600 leading-relaxed">
              <p>2020-yilda tashkil etilgan KasibXunar platformasi bugun 50 000+ faol talabaga xizmat ko'rsatmoqda. Biz o'zbek tilidagi sifatli kasbiy ko'nikmalar ta'limini qulay va arzon qilishni maqsad qilganmiz.</p>
              <p>Platforma orqali siz sertifikat olib, karyerangizni yangi bosqichga ko'tarishingiz yoki o'z biznesingizni rivojlantirishingiz mumkin.</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-5">
            <div
              v-for="stat in stats"
              :key="stat.label"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center"
            >
              <p class="text-3xl font-extrabold text-primary-600">{{ stat.value }}</p>
              <p class="text-sm text-gray-500 mt-1">{{ stat.label }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Values -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-extrabold text-gray-900">Bizning qadriyatlar</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="val in values"
            :key="val.title"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
          >
            <div class="text-3xl mb-4">{{ val.emoji }}</div>
            <h3 class="font-semibold text-gray-900 mb-2">{{ val.title }}</h3>
            <p class="text-sm text-gray-500 leading-relaxed">{{ val.description }}</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
const stats = [
  { value: '50K+',  label: 'Faol talabalar' },
  { value: '500+',  label: 'Kurslar' },
  { value: '120+',  label: "O'qituvchilar" },
  { value: '2020',  label: 'Tashkil etilgan' },
]

const values = [
  { emoji: '🎯', title: 'Sifat', description: "Har bir kurs mutaxassislar tomonidan ko'rib chiqilib, sifat nazoratidan o'tkaziladi." },
  { emoji: '💡', title: 'Innovatsiya', description: "So'nggi texnologiyalar va zamonaviy o'qitish metodlari asosida kurslar yaratiladi." },
  { emoji: '🤝', title: 'Hamjamiyat', description: "Talabalar va o'qituvchilar o'rtasida faol muloqot va yordam muhiti yaratilgan." },
  { emoji: '🌍', title: 'Qulaylik', description: "Istalgan vaqt, istalgan joydan mobil yoki kompyuter orqali o'rganish imkoniyati." },
  { emoji: '🏆', title: 'Natija', description: "Kurslarnini tugatgan talabalar tan olingan sertifikatlar bilan taqdirlanadi." },
  { emoji: '💰', title: 'Arzonlik', description: "Sifatli ta'lim hamma uchun arzon va qulaylik bilan taqdim etiladi." },
]
</script>
