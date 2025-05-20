<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-100 to-blue-300 p-6">
        <div class="max-w-6xl mx-auto bg-white shadow-md rounded-xl p-6">
            <h1 class="text-2xl font-bold mb-4 text-blue-700">📋 Participantes del Concurso</h1>

            <div class="flex justify-end mb-4">
                <a href="/web/clients/export"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                    📥 Exportar Excel
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-200">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Apellido</th>
                            <th class="px-4 py-2 text-left">Cédula</th>
                            <th class="px-4 py-2 text-left">Correo</th>
                            <th class="px-4 py-2 text-left">Teléfono</th>
                            <th class="px-4 py-2 text-left">Ciudad</th>
                            <th class="px-4 py-2 text-left">Departamento</th>
                            <th class="px-4 py-2 text-left">Fecha</th>
                            <th class="px-4 py-2 text-left">Ganador</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="client in clients" :key="client.id" class="border-t text-sm">
                            <td class="px-4 py-2">{{ client.name }}</td>
                            <td class="px-4 py-2">{{ client.lastname }}</td>
                            <td class="px-4 py-2">{{ client.id_number }}</td>
                            <td class="px-4 py-2">{{ client.email }}</td>
                            <td class="px-4 py-2">{{ client.phone }}</td>
                            <td class="px-4 py-2">{{ client.city }}</td>
                            <td class="px-4 py-2">{{ client.department }}</td>
                            <td class="px-4 py-2">{{ client.created_at }}</td>
                            <td class="px-4 py-2 font-semibold">
                                <span v-if="client.is_winner" class="text-green-600">🏆</span>
                                <span v-else class="text-gray-400">—</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineOptions({ layout: AuthenticatedLayout })

const clients = ref([])


onMounted(async () => {
    try {
        const res = await axios.get('/web/contest/clients')
        clients.value = res.data.clients
    } catch (err) {
        console.error('Error cargando clientes', err)
    }
})
</script>
