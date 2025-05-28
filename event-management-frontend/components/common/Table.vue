<template>
  <div class="overflow-x-auto">
    <table class="w-full divide-y divide-gray-200 border border-cyan-400 rounded-lg">
      <thead class="bg-cyan-600">
        <tr>
          <th
            v-for="(column, index) in columns"
            :key="index"
            scope="col"
            class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider"
          >
            {{ column.header }}
          </th>
          <th
            v-if="actions"
            scope="col"
            class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider"
          >
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-if="filteredData.length === 0">
          <td
            :colspan="columns.length + (actions ? 1 : 0)"
            class="px-6 py-4 text-center text-sm text-gray-500"
          >
            No data found
          </td>
        </tr>
        <tr
          v-for="(item, rowIndex) in filteredData"
          :key="rowIndex"
          :class="[(rowIndex % 2 === 0 ? 'bg-white' : 'bg-gray-50'), 'cursor-pointer']"
        >
          <td
            v-for="(column, colIndex) in columns"
            :key="colIndex"
            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
          >
            <slot
              :name="column.accessor"
              :item="item"
              :value="item[column.accessor]"
            >
              {{ column.render ? column.render(item) : item[column.accessor] }}
            </slot>
          </td>
          <td
            v-if="actions"
            class="w-1/4 px-6 py-4 whitespace-nowrap text-sm text-gray-900"
          >
            <div class="flex space-x-2">
              <button
                v-for="(action, actionIndex) in actions"
                :key="actionIndex"
                @click="action.onClick(item)"
                :class="[
                  'inline-flex items-center px-4 py-2 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2',
                  action.className
                ]"
              >
                {{ action.label }}
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Column<T> {
  header: string
  accessor: keyof T
  render?: (item: T) => string | number
}

interface Action<T> {
  label: string
  onClick: (item: T) => void
  className?: string
}

interface Props<T> {
  columns: Column<T>[]
  data: T[]
  searchTerm?: string
  searchFields?: (keyof T)[]
  actions?: Action<T>[]
}

const props = withDefaults(defineProps<Props<any>>(), {
  searchTerm: '',
  searchFields: () => [],
  actions: undefined
})

const filteredData = computed(() => {
  if (!props.searchTerm || !props.searchFields?.length) return props.data

  return props.data.filter((item) =>
    props.searchFields!.some((field) => {
      const value = item[field]
      if (value === null || value === undefined) return false
      return String(value)
        .toLowerCase()
        .includes(props.searchTerm.toLowerCase())
    })
  )
})
</script>

<style scoped>
.btn-icon {
  /* @apply px-3 py-1 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2; */
}
</style>
