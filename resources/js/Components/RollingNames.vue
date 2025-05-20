<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-100 to-blue-300 flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-2xl text-center">
      <h1 class="text-3xl font-bold text-blue-700 mb-6">🎯 Seleccionando ganador...</h1>

      <div class="overflow-hidden h-12 relative mb-6">
        <transition-group name="slide" tag="div">
          <p v-if="currentName" :key="currentName" class="text-2xl font-semibold text-blue-800 animate-fade-in">
            {{ currentName }}
          </p>
        </transition-group>
      </div>

      <div v-if="winner" class="mt-4 bg-blue-50 p-6 rounded-xl shadow-lg animate-fade-in">
        <h2 class="text-2xl font-bold text-green-600 mb-2">🏆 ¡Ganador!</h2>
        <p class="text-lg font-semibold">{{ winner.name }} {{ winner.lastname }}</p>
        <p class="text-sm text-gray-700">{{ winner.city }}, {{ winner.department }}</p>
      </div>

      <p v-if="error" class="text-red-600 mt-4">{{ error }}</p>
    </div>
  </div>
</template>

<script>
import { ref, nextTick, onMounted } from 'vue'
import axios from 'axios'

export default {
  setup() {
    const names = ref([])
    const currentName = ref('')
    const winner = ref(null)
    const error = ref(null)
    let intervalId = null

    const startRolling = async () => {
      try {
        const res = await axios.get('/web/contest/clients')
        names.value = res.data.clients.map(c => `${c.name} ${c.lastname}`)
        if (!names.value.length) throw new Error('No hay participantes')

        await nextTick()

        let i = 0
        intervalId = setInterval(() => {
          currentName.value = names.value[i % names.value.length]
          i++
        }, 70)

        setTimeout(async () => {
          clearInterval(intervalId)
          try {
            await new Promise(resolve => setTimeout(resolve, 300))
            const winnerRes = await axios.post('/web/contest/winner')
            winner.value = winnerRes.data.winner
            currentName.value = `${winner.value.name} ${winner.value.lastname}`
          } catch (err) {
            error.value = err.response?.data?.message || 'Error al elegir ganador'
          }
        }, 10000)
      } catch (err) {
        error.value = err.message || 'Error al cargar participantes'
      }
    }

    onMounted(() => {
      startRolling()
    })

    return {
      names,
      currentName,
      winner,
      error
    }
  }
}
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.2s ease-in-out;
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}
.slide-enter-from {
  transform: translateY(-20px);
  opacity: 0;
}
.slide-enter-to {
  transform: translateY(0);
  opacity: 1;
}
.slide-leave-to {
  transform: translateY(20px);
  opacity: 0;
}
</style>
