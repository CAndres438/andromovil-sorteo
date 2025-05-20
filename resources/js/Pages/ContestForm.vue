<template>
    <canvas id="confetti-canvas" class="fixed top-0 left-0 w-full h-full pointer-events-none z-50"></canvas>


    <section v-if="winner" class="w-full bg-blue-100 py-10 shadow-inner">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2
                class="text-3xl md:text-4xl font-bold text-green-600 flex items-center justify-center gap-2 mb-2 animate-pulse">
                🏆 Ganador del Sorteo
            </h2>
            <p class="text-xl md:text-2xl font-semibold text-gray-800">{{ winner.name }} {{ winner.lastname }}</p>
            <p class="text-md text-gray-600">{{ winner.city }}, {{ winner.department }}</p>
        </div>
    </section>

    <div class="min-h-screen bg-gradient-to-br from-blue-100 to-blue-300 flex items-center justify-center">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-2xl">
            <h1 class="text-3xl font-bold text-center text-blue-700 mb-6">¡Gana con Andromóvil!</h1>
            <p class="text-center text-gray-600 mb-6">Regístrate para participar en nuestro sorteo exclusivo en Bogotá
            </p>

            <form @submit.prevent="onSubmit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <input v-model="name" @blur="blurName" placeholder="Nombre" class="input" />
                    <p class="text-red-600 text-sm">{{ nameError }}</p>
                </div>
                <div>
                    <input v-model="lastname" @blur="blurLastname" placeholder="Apellido" class="input" />
                    <p class="text-red-600 text-sm">{{ lastnameError }}</p>
                </div>
                <div>
                    <input v-model="id_number" @blur="blurId" placeholder="Cédula" class="input" />
                    <p class="text-red-600 text-sm">{{ idError }}</p>
                </div>
                <div>
                    <input v-model="email" @blur="blurEmail" placeholder="Correo electrónico" class="input" />
                    <p class="text-red-600 text-sm">{{ emailError }}</p>
                </div>
                <div>
                    <input v-model="phone" @blur="blurPhone" placeholder="Celular" class="input" />
                    <p class="text-red-600 text-sm">{{ phoneError }}</p>
                </div>
                <div>
                    <select v-model="department" @blur="blurDept" class="input">
                        <option disabled value="">Departamento</option>
                        <option v-for="d in departments" :key="d.id" :value="d.departamento">{{ d.departamento }}
                        </option>
                    </select>
                    <p class="text-red-600 text-sm">{{ deptError }}</p>
                </div>
                <div>
                    <select v-model="city" @blur="blurCity" class="input">
                        <option disabled value="">Ciudad</option>
                        <option v-for="c in selectedCities" :key="c">{{ c }}</option>
                    </select>
                    <p class="text-red-600 text-sm">{{ cityError }}</p>
                </div>

                <label class="col-span-2 flex items-center space-x-2 text-sm">
                    <input type="checkbox" v-model="habeas_data" @blur="blurHabeas" class="accent-blue-600" />
                    <span>Acepto el tratamiento de mis datos personales</span>
                </label>
                <p class="col-span-2 text-red-600 text-sm">{{ habeasError }}</p>

                <button type="submit"
                    class="col-span-2 bg-blue-700 text-white font-bold py-2 rounded-xl hover:bg-blue-800 transition disabled:opacity-50"
                    :disabled="isSubmitting">
                    <span v-if="isSubmitting">Registrando...</span>
                    <span v-else>Registrarme</span>
                </button>
            </form>

            <p v-if="success" class="text-green-600 text-center mt-4 font-semibold">Registro exitoso 🎉</p>
            <p v-if="error" class="text-red-600 text-center mt-4 font-semibold">Ocurrió un error. Intenta de nuevo.</p>
        </div>
    </div>
</template>

<script>
import { useForm, useField } from 'vee-validate'
import * as yup from 'yup'
import { ref, watch, computed, onMounted } from 'vue'
import { registerParticipant } from '../services/api'
import departmentsJson from '../data/departments.json'
import axios from 'axios'
import confetti from 'canvas-confetti'

const winner = ref(null)

yup.setLocale({
    mixed: {
        required: 'Este campo es obligatorio',
        oneOf: 'Debes aceptar los términos',
    },
    string: {
        email: 'Debe ser un correo electrónico válido',
        matches: 'Formato inválido',
        min: 'Debe tener al menos ${min} caracteres',
        max: 'Debe tener como máximo ${max} caracteres',
    },
    number: {
        min: 'Debe ser mayor o igual a ${min}',
        max: 'Debe ser menor o igual a ${max}',
        integer: 'Debe ser un número entero',
        positive: 'Debe ser un número positivo',
    }
})


export const schema = yup.object({
    name: yup
        .string()
        .required()
        .min(3)
        .max(50)
        .matches(/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/, 'Solo se permiten letras'),
    lastname: yup
        .string()
        .required()
        .min(3)
        .max(50)
        .matches(/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/, 'Solo se permiten letras'),
    id_number: yup
        .string()
        .required()
        .matches(/^\d+$/, 'Solo se permiten números')
        .min(6)
        .max(15),
    email: yup
        .string()
        .required()
        .email(),
    phone: yup
        .string()
        .required()
        .matches(/^3\d{9}$/, 'Debe ser un número celular colombiano válido'),
    department: yup
        .string()
        .required(),
    city: yup
        .string()
        .required(),
    habeas_data: yup
        .boolean()
        .oneOf([true], 'Debes aceptar los términos'),
})
export default {
    setup() {
        const winner = ref(null)

        const fetchWinner = async () => {
            try {
                const res = await axios.get("/api/contest/winner")
                winner.value = res.data.winner
            } catch (err) { }
        }

        onMounted(() => fetchWinner())

        const departments = departmentsJson
        const selectedCities = ref([])

        const { handleSubmit, resetForm, isSubmitting } = useForm({
            validationSchema: schema
        })

        const { value: name, errorMessage: nameError, handleBlur: blurName } = useField('name')
        const { value: lastname, errorMessage: lastnameError, handleBlur: blurLastname } = useField('lastname')
        const { value: id_number, errorMessage: idError, handleBlur: blurId } = useField('id_number')
        const { value: email, errorMessage: emailError, handleBlur: blurEmail } = useField('email')
        const { value: phone, errorMessage: phoneError, handleBlur: blurPhone } = useField('phone')
        const { value: department, errorMessage: deptError, handleBlur: blurDept } = useField('department')
        const { value: city, errorMessage: cityError, handleBlur: blurCity } = useField('city')

        const {
            value: habeas_data,
            errorMessage: habeasError,
            handleBlur: blurHabeas
        } = useField('habeas_data', undefined, { validateOnValueUpdate: true })

        watch(department, (newVal) => {
            const match = departments.find(d => d.departamento === newVal)
            selectedCities.value = match ? match.ciudades : []
            city.value = ''
        })

        const success = ref(false)
        const error = ref(false)

        const onSubmit = handleSubmit(async (values) => {
            success.value = false
            error.value = false
            try {
                await registerParticipant(values)
                success.value = true
                resetForm()
            } catch {
                error.value = true
            }
        })

        watch(winner, (val) => {
            if (val) {
                console.log("🎉 Disparando confetti");
                const canvas = document.getElementById('confetti-canvas')
                if (canvas) {
                    const myConfetti = confetti.create(canvas, { resize: true, useWorker: true })
                    myConfetti({
                        particleCount: 150,
                        spread: 70,
                        origin: { y: 0.4 }
                    })
                }
            }
        })

        return {
            winner,
            name, lastname, id_number, email, phone, department, city, habeas_data,
            nameError, lastnameError, idError, emailError, phoneError, deptError, cityError, habeasError,
            blurName, blurLastname, blurId, blurEmail, blurPhone, blurDept, blurCity, blurHabeas,
            onSubmit, selectedCities, departments, success, error, isSubmitting
        }
    }
}



</script>


<style scoped>
.input {
    @apply border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full;
}
</style>
