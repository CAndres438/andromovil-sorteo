<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-100 to-blue-300 flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-2xl text-center">
      <h1 class="text-3xl font-bold text-blue-700 mb-6">{{ winner ? "🏆 Ganador del Sorteo" : "🎲 Seleccionando Ganador del Sorteo" }}</h1>

      <div class="h-20 mb-6 flex items-center justify-center">

        <transition-group name="slide" tag="div">
          <p v-if="currentName" :key="currentName" class="text-2xl font-semibold text-blue-800 animate-fade-in">
            {{ currentName }}
          </p>
        </transition-group>
      </div>

      <button
        @click="startRolling"
        :disabled="loading || winner || notEnoughParticipants"
        class="bg-blue-700 text-white font-bold py-2 px-6 rounded-xl hover:bg-blue-800 transition flex items-center justify-center gap-3 disabled:opacity-50 w-full mb-6"
      >
        <svg v-if="loading" class="animate-spin h-5 w-5" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="white" stroke-width="4" />
          <path class="opacity-75" fill="white" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
        </svg>
        <span v-if="!loading">🎯 Seleccionar Ganador</span>
        <span v-else>Seleccionando...</span>
      </button>

      <div v-if="winner" class="mt-6 bg-blue-50 p-6 rounded-xl shadow-lg animate-fade-in">
        <h2 class="text-2xl font-bold text-green-600 mb-2">🎉 ¡Ganador!</h2>
        <p class="text-lg font-semibold">{{ winner.name }} {{ winner.lastname }}</p>
        <p class="text-sm text-gray-700">{{ winner.city }}, {{ winner.department }}</p>
      </div>

      <p v-if="notEnoughParticipants" class="text-red-600 mt-4">
        ⚠️ Se requieren al menos 5 participantes para realizar el sorteo.
      </p>
      <p v-if="error" class="mt-4 text-red-600">{{ error }}</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { ref, onMounted } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

axios.defaults.withCredentials = true

const ChooseWinner = {
  setup() {
    const winner = ref(null)
    const loading = ref(false)
    const error = ref(null)
    const currentName = ref('')
    const names = ref([])
    const notEnoughParticipants = ref(false)
    let intervalId = null

    const checkParticipantCount = async () => {
      try {
        const res = await axios.get('/web/contest/count')
        if (res.data.count < 5) {
          notEnoughParticipants.value = true
        }
      } catch (err) {
        error.value = 'Error al validar cantidad de participantes'
      }
    }

    const fetchExistingWinner = async () => {
      try {
        const res = await axios.get('/api/contest/winner')
        winner.value = res.data.winner
        currentName.value = `${winner.value.name} ${winner.value.lastname}`
      } catch (err) {
        if (err.response?.status !== 404) {
          error.value = 'Error al consultar el ganador existente'
        }
      }
    }

    const startRolling = async () => {
      loading.value = true
      error.value = null
      winner.value = null
      try {
        const res = await axios.get('/web/contest/clients')
        names.value = res.data.clients.map(c => `${c.name} ${c.lastname}`)
        let i = 0
        intervalId = setInterval(() => {
          currentName.value = names.value[i % names.value.length]
          i++
        }, 80)

        setTimeout(async () => {
          clearInterval(intervalId)
          const winnerRes = await axios.post('/web/contest/winner')
          winner.value = winnerRes.data.winner
          currentName.value = `${winner.value.name} ${winner.value.lastname}`
        }, 10000)
      } catch (err) {
        error.value = err.response?.data?.message || 'Error al elegir ganador'
      } finally {
        loading.value = false
      }
    }

    onMounted(() => {
      checkParticipantCount()
      fetchExistingWinner()
    })

    return {
      winner,
      loading,
      error,
      currentName,
      startRolling,
      notEnoughParticipants
    }
  }
}

ChooseWinner.layout = AuthenticatedLayout

export default ChooseWinner
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
