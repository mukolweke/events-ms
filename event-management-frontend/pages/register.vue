<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
        Create your Organization & Admin Account
      </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <form class="space-y-6" @submit.prevent="handleSubmit">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Organization Details
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              This information will be used to create your organization's presence.
            </p>
          </div>

          <div>
            <label for="organizationName" class="block text-sm font-medium text-gray-700 capitalize">
              {{ fieldLabels.organizationName }}
            </label>
            <div class="mt-1">
              <BaseInput
                id="organizationName"
                v-model="form.organizationName"
                type="text"
                placeholder="Enter organization name"
                :errors="errors.value.organizationName"
                size="md"
                @input="generateSlug"
              />
            </div>
          </div>

          <div>
            <label for="organizationDomain" class="block text-sm font-medium text-gray-700 capitalize">
              {{ fieldLabels.organizationDomain }}
            </label>
            <div class="mt-1">
              <BaseInput
                id="organizationDomain"
                v-model="form.organizationDomain"
                type="text"
                placeholder="organization-domain"
                :errors="errors.value.organizationDomain"
                size="md"
              />
              <p class="mt-1 text-xs text-gray-500">
                This will be part of your organization's URL (e.g., your-slug.domain.com or domain.com/your-slug). It's generated automatically from the organization name but can be adjusted if needed (though usually not recommended to change).
              </p>
            </div>
          </div>

          <hr class="my-8">

          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Admin Account Details
            </h3>
            <p class="mt-1 text-sm text-gray-500">
              This will be your primary admin account for the organization.
            </p>
          </div>

          <div>
            <label for="adminFirstName" class="block text-sm font-medium text-gray-700 capitalize">
              {{ fieldLabels.adminName }}
            </label>
            <div class="mt-1">
              <BaseInput
                id="adminFirstName"
                v-model="form.adminName"
                type="text"
                placeholder="Admin name"
                :errors="errors.value.adminName"
                size="md"
              />
            </div>
          </div>

          <div>
            <label for="adminEmail" class="block text-sm font-medium text-gray-700 capitalize">
              {{ fieldLabels.adminEmail }}
            </label>
            <div class="mt-1">
              <BaseInput
                id="adminEmail"
                v-model="form.adminEmail"
                type="email"
                autocomplete="email"
                placeholder="Admin email"
                :errors="errors.value.adminEmail"
                size="md"
              />
            </div>
          </div>

          <div>
            <label for="adminPassword" class="block text-sm font-medium text-gray-700 capitalize">
              {{ fieldLabels.adminPassword }}
            </label>
            <div class="mt-1">
              <BaseInput
                id="adminPassword"
                v-model="form.adminPassword"
                type="password"
                autocomplete="new-password"
                placeholder="********"
                :errors="errors.value.adminPassword"
                size="md"
                :class="{ 'border-red-300': errors.value.adminPassword }"
              />
            </div>
          </div>

          <div>
            <label for="adminPasswordConfirmation" class="block text-sm font-medium text-gray-700 capitalize">
              {{  fieldLabels.adminPasswordConfirmation }}
            </label>
            <div class="mt-1">
              <BaseInput
                id="adminPasswordConfirmation"
                v-model="form.adminPasswordConfirmation"
                type="password"
                autocomplete="new-password"
                placeholder="********"
                :errors="errors.value.adminPasswordConfirmation"
                size="md"
              />
            </div>
          </div>

          <div>
            <BaseButton
              :loading="loading"
              type="submit"
              variant="primary"
              size="lg"
              fullWidth
              :disabled="loading"
            >
              <span v-if="loading">Creating Account...</span>
              <span v-else>Create Organization and Admin Account</span>
            </BaseButton>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import { useValidation } from '../composables/useValidation';
import { useAuth } from '../composables/useAuth';
import { useError } from '../composables/useError';

import BaseInput from '~/components/common/BaseInput.vue';
import BaseButton from '~/components/common/BaseButton.vue';

// Define the form data structure
interface OrganizationRegistrationData {
  organizationName: string;
  organizationDomain: string;
  adminName: string;
  adminEmail: string;
  adminPassword: string;
  adminPasswordConfirmation: string;
}

const router = useRouter();
const { register, loading, user } = useAuth();

const { validateForm, errors } = useValidation();
const { handleError } = useError();

const form = ref<OrganizationRegistrationData>({
  organizationName: '',
  organizationDomain: '',
  adminName: '',
  adminEmail: '',
  adminPassword: '',
  adminPasswordConfirmation: '',
});

// Slug generation (client-side, ensure server-side validation too)
const generateSlug = () => {
  form.value.organizationDomain = form.value.organizationName
    .toLowerCase()
    .trim()
    .replace(/\s+/g, '-')      // Replace spaces with -
    .replace(/[^\w-]+/g, '')   // Remove all non-word chars
    .replace(/--+/g, '-');     // Replace multiple - with single -
};

// Validation rules based on your useValidation composable
const validationRules = {
  organizationName: { required: true, minLength: 2 },
  organizationDomain: { required: true },
  adminName: { required: true, minLength: 2 },
  adminEmail: { required: true, email: true },
  adminPassword: { required: true, password: true },
  adminPasswordConfirmation: { required: true } // 'sameAs' check will be done manually
};

// Custom validation for slug pattern
const validateSlugPattern = (slug: string): boolean => {
  const slugRegex = /^[a-z0-9]+(?:-[a-z0-9]+)*$/;
  return slugRegex.test(slug);
};

// Field labels for error messages
const fieldLabels = {
  organizationName: 'Organization name',
  organizationDomain: 'Organization domain',
  adminName: 'Admin name',
  adminEmail: 'Admin email',
  adminPassword: 'Password',
  adminPasswordConfirmation: 'Confirm password'
};

const handleSubmit = async () => {
  // Clear previous custom errors if any (validateForm clears its own)
  if (errors.value.organizationDomain) delete errors.value.organizationDomain;
  if (errors.value.adminPasswordConfirmation) delete errors.value.adminPasswordConfirmation;

  const isStandardValid = validateForm(validationRules, form.value, fieldLabels);
  let customValid = true;

  // Manual validation for password confirmation
  if (form.value.adminPassword !== form.value.adminPasswordConfirmation) {
    if (!errors.value.adminPasswordConfirmation) errors.value.adminPasswordConfirmation = [];
    errors.value.adminPasswordConfirmation.push('Passwords do not match.');
    customValid = false;
  }

  // Manual validation for slug pattern
  if (form.value.organizationDomain && !validateSlugPattern(form.value.organizationDomain)) {
    if (!errors.value.organizationDomain) errors.value.organizationDomain = [];
    errors.value.organizationDomain.push(
      'Slug can only contain lowercase letters, numbers, and hyphens, and cannot start or end with a hyphen.'
    );
    customValid = false;
  }

  if (!isStandardValid || !customValid) {
    loading.value = false;
    return;
  }

  loading.value = true;

  try {
    const response = await register(form.value);

    if (!response.success) {
      handleError('Registration failed. Please try again.');
      return;
    }

    await nextTick();

    // Redirect based on user role
    if (user.value?.role === 'admin') {
      await router.push({ path: '/admin', replace: true });
    } else {
      await router.push({ path: '/', replace: true });
    }
  } catch (error: any) {
    console.error('Registration failed:', error);
    if (error.data && error.data.errors) {
      for (const field in error.data.errors) {
        errors.value[field] = error.data.errors[field];
      }
    } else {
      if (!errors.value.general) errors.value.general = [];
      errors.value.general.push('An unexpected error occurred. Please try again.');
    }
  } finally {
    loading.value = false;
  }
};

// SEO
useHead({
  title: 'Register Organization - Event Management System',
  meta: [
    {
      name: 'description',
      content: 'Create your organization and admin account for the event management system'
    }
  ]
});
</script>
