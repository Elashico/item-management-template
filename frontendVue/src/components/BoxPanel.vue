<script setup>
import { ref } from 'vue'

defineProps({
  boxes: Array,
  selectedBoxId: [Number, String]
})

const emit = defineEmits([
  'select-box',
  'add-box',
  'update-box',
  'delete-box',
  'restore-box',
  'force-delete-box'
])

const newBoxName = ref('')
const editingId = ref(null)
const editName = ref('')

// auto-focus the input when edit mode opens
const vFocus = { mounted: (el) => el.focus() }

const handleAdd = () => {
  if (!newBoxName.value.trim()) return
  emit('add-box', newBoxName.value.trim())
  newBoxName.value = ''
}

const startEdit = (box) => {
  editingId.value = box.id
  editName.value = box.name
}

const cancelEdit = () => {
  editingId.value = null
  editName.value = ''
}

const saveEdit = (box) => {
  const name = editName.value.trim()
  if (name && name !== box.name) {
    emit('update-box', { id: box.id, name })
  }
  cancelEdit()
}

const handleDelete = (box) => {
  if (confirm(`Delete box "${box.name}"?`)) {
    emit('delete-box', box.id)
  }
}

const handleForceDelete = (box) => {
  if (confirm(`Permanently delete box "${box.name}"? This action cannot be undone.`)) {
    emit('force-delete-box', box.id)
  }
}

const formatDate = (value) => (value ? new Date(value).toLocaleString() : '')
</script>

<template>
  <div class="w-1/3 border-r border-gray-200 flex flex-col h-full bg-gray-50">
    <!-- Panel Header / Add Box Form -->
    <div class="p-4 border-b border-gray-200 bg-white shrink-0">
      <h2 class="text-sm font-semibold text-gray-700 mb-2">Collections / Boxes</h2>
      <div class="flex gap-2">
        <input
          v-model="newBoxName"
          type="text"
          placeholder="New box name..."
          class="flex-1 text-sm border border-gray-300 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500"
          @keyup.enter="handleAdd"
        />
        <button
          @click="handleAdd"
          class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-3 py-1.5 rounded transition-colors"
        >
          Add
        </button>
      </div>
    </div>

    <!-- Scrollable Boxes List -->
    <div class="flex-1 overflow-y-auto p-4 space-y-2">
      <div
        v-for="box in boxes"
        :key="box.id"
        @click="!box.deleted_at && $emit('select-box', box.id)"
        :class="[
          'p-3 rounded-lg border transition-all',
          box.deleted_at
            ? 'bg-gray-100 border-gray-200 opacity-70 cursor-default'
            : selectedBoxId === box.id
              ? 'bg-blue-50 border-blue-300 shadow-sm cursor-pointer'
              : 'bg-white border-gray-200 hover:border-gray-300 cursor-pointer'
        ]"
      >
        <!-- Edit mode -->
        <div v-if="editingId === box.id" class="flex gap-2" @click.stop>
          <input
            v-model="editName"
            v-focus
            type="text"
            class="flex-1 text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500"
            @keyup.enter="saveEdit(box)"
            @keyup.esc="cancelEdit"
          />
          <button
            @click="saveEdit(box)"
            class="text-xs bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded"
          >
            Save
          </button>
          <button
            @click="cancelEdit"
            class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 py-1 rounded"
          >
            Cancel
          </button>
        </div>

        <!-- Normal mode -->
        <div v-else class="flex items-start justify-between gap-2">
          <div class="min-w-0">
            <div
              :class="[
                'font-medium text-sm truncate',
                box.deleted_at ? 'text-gray-500 line-through' : 'text-gray-800'
              ]"
            >
              {{ box.name }}
            </div>
            <div class="text-xs text-gray-400 mt-1">ID: {{ box.id }}</div>
            <div class="text-xs text-gray-400">Created: {{ formatDate(box.created_at) }}</div>
            <div v-if="box.updated_at" class="text-xs text-blue-500">
              Updated: {{ formatDate(box.updated_at) }}
            </div>
            <div v-if="box.deleted_at" class="text-xs text-red-500">
              Deleted: {{ formatDate(box.deleted_at) }}
            </div>
          </div>

          <div class="flex gap-1 shrink-0" @click.stop>
            <template v-if="box.deleted_at">
              <button
                @click="$emit('restore-box', box.id)"
                class="text-xs bg-green-100 hover:bg-green-200 text-green-700 px-2 py-1 rounded"
              >
                Restore
              </button>
              <button
                @click="handleForceDelete(box)"
                class="text-xs bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded"
              >
                Delete Permanently
              </button>
            </template>
            <template v-else>
              <button
                @click="startEdit(box)"
                class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded"
              >
                Edit
              </button>
              <button
                @click="handleDelete(box)"
                class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-2 py-1 rounded"
              >
                Delete
              </button>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>