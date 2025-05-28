<template>
    <div class="h-[80vh] bg-gray-50 flex flex-col items-center justify-center">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" @submit.prevent="handleSubmit">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1">
                            <base-input id="email" v-model="form.email" name="email" type="email" autocomplete="email"
                                placeholder="Enter you email" :error="errors?.value?.email?.[0]" size="md" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 uppercase">
                            Password
                        </label>
                        <div class="mt-1">
                            <base-input id="password" v-model="form.password" name="password" type="password"
                                placeholder="********" :error="errors?.value?.password?.[0]" size="md" />
                        </div>
                    </div>

                    <div>
                        <base-button :loading="loading" @click="handleSubmit" variant="primary" size="lg" type="button"
                            fullWidth>
                            Sign In
                        </base-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { LoginCredentials } from '~/types'
import { ref, onMounted, nextTick } from 'vue'
import { useAuth } from '../composables/useAuth'
import { useValidation } from '../composables/useValidation'
import { useRouter } from 'vue-router'
import { useError } from '../composables/useError'
import BaseButton from '~/components/common/BaseButton.vue'
import BaseInput from '~/components/common/BaseInput.vue'

const router = useRouter()
const { login, loading, isAuthenticated, user, getCurrentUser, initializeAuth } = useAuth()
const { validateForm, errors } = useValidation()
const { handleError } = useError()

// Redirect if already authenticated
onMounted(async () => {
  // Initialize auth state if token exists
  await initializeAuth()

  if (isAuthenticated.value) {
    if (user.value?.role === 'admin') {
      await navigateTo('/admin', { replace: true })
    } else {
      await navigateTo('/', { replace: true })
    }
  }
})

const form = ref<LoginCredentials & { rememberMe: boolean }>({
    email: '',
    password: '',
    rememberMe: false
})

const validationRules = {
    email: { required: true, email: true },
    password: { required: true, minLength: 8 }
}

const handleSubmit = async () => {
    if (!validateForm(validationRules, form.value)) {
        return
    }

    try {
        const response = await login({
            email: form.value.email,
            password: form.value.password
        })

        if (!response.success) {
            handleError(response.error)
            return
        }

        // Wait for the next tick to ensure user data is updated
        await nextTick()

        // Redirect based on user role
        if (user.value?.role === 'admin') {
            await navigateTo('/admin', { replace: true })
        } else {
            await navigateTo('/', { replace: true })
        }
    } catch (error) {
        handleError(error)
    }
}

// SEO
useHead({
    title: 'Login - Event Management System',
    meta: [
        {
            name: 'description',
            content: 'Sign in to your event management account'
        }
    ]
})
</script>
