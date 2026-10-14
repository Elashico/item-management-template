<script setup>
import { ref } from 'vue'

const props = defineProps({
  selectedBox: Object,
  items: Array
})

const emit = defineEmits([
  'add-item',
  'update-item',
  'delete-item',
  'restore-item',
  'force-delete-item'
])

const newItemName = ref('')
const editingId = ref(null)
const editingName = ref('')

const handleAddItem = () => {
  if (!newItemName.value.trim() || !props.selectedBox) return
  emit('add-item', newItemName.value.trim())
  newItemName.value = ''
}

const startEdit = (item) => {
  editingId.value = item.id
  editingName.value = item.name
}

const saveEdit = (id) => {
  if (!editingName.value.trim()) return
  emit('update-item', { id, name: editingName.value.trim() })
  editingId.value = null
}

const handleForceDelete = (item) => {
  if (confirm(`Permanently delete item "${item.name}"? This action cannot be undone.`)) {
    emit('force-delete-item', item.id)
  }
}

// for date formatting
const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  }).format(date)
}
</script>

<template>
  <div class="w-2/3 flex flex-col h-full bg-white">
    <template v-if="selectedBox">
      <!-- Panel Header / Add Item Form -->
      <div class="p-4 border-b border-gray-200 bg-gray-50/50 shrink-0 flex items-center justify-between">
        <div>
          <h2 class="text-sm font-semibold text-gray-800">Items in "{{ selectedBox.name }}"</h2>
        </div>
        <div class="flex gap-2 w-72">
          <input 
            v-model="newItemName" 
            type="text" 
            placeholder="New item name..." 
            class="flex-1 text-sm border border-gray-300 rounded px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white"
            @keyup.enter="handleAddItem"
          />
          <button 
            @click="handleAddItem"
            class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium px-3 py-1.5 rounded transition-colors"
          >
            Add Item
          </button>
        </div>
      </div>

      <!-- Scrollable Items List -->
      <div class="flex-1 overflow-y-auto p-4 space-y-3">
        <div v-if="items.length === 0" class="text-center text-gray-400 text-sm py-8">
          No items found in this box. Add one above!
        </div>

        <div 
          v-for="item in items" 
          :key="item.id"
          :class="[
            'p-3.5 rounded-lg border flex items-center justify-between transition-all',
            item.deleted_at ? 'bg-gray-100 border-gray-200 opacity-60' : 'bg-white border-gray-200 shadow-xs'
          ]"
        >
          <!-- Item Details / Inline Edit -->
          <div class="flex-1 mr-4">
            <div v-if="editingId === item.id" class="flex gap-2 items-center">
              <input 
                v-model="editingName" 
                type="text" 
                class="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500 w-full"
                @keyup.enter="saveEdit(item.id)"
              />
              <button @click="saveEdit(item.id)" class="text-xs bg-green-600 text-white px-2.5 py-1 rounded">Save</button>
              <button @click="editingId = null" class="text-xs bg-gray-400 text-white px-2.5 py-1 rounded">Cancel</button>
            </div>
            
            <div v-else>
              <span :class="['font-medium text-sm', item.deleted_at ? 'line-through text-gray-500' : 'text-gray-800']">
                {{ item.name }}
              </span>
              <div class="text-xs text-gray-400 mt-1 flex gap-4">
                <span>Added: {{ formatDate(item.created_at) }}</span>
                
                <!-- Only shows if updated_at is not null -->
                <span v-if="item.updated_at">Updated: {{ formatDate(item.updated_at) }}</span>

                <span v-if="item.deleted_at" class="text-red-500">Deleted: {{ formatDate(item.deleted_at) }}</span>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center gap-2 shrink-0">
            <template v-if="!item.deleted_at">
              <button 
                @click="startEdit(item)" 
                class="text-xs text-gray-600 hover:text-blue-600 px-2 py-1 border border-gray-200 rounded bg-white hover:bg-gray-50"
              >
                Edit
              </button>
              <button 
                @click="$emit('delete-item', item.id)" 
                class="text-xs text-red-600 hover:text-red-700 px-2 py-1 border border-red-200 rounded bg-white hover:bg-red-50"
              >
                Delete
              </button>
            </template>
            <template v-else>
              <button 
                @click="$emit('restore-item', item.id)" 
                class="text-xs text-green-600 hover:text-green-700 px-2 py-1 border border-green-200 rounded bg-white hover:bg-green-50 font-medium"
              >
                Restore
              </button>
              <button 
                @click="handleForceDelete(item)" 
                class="text-xs bg-red-600 hover:bg-red-700 text-white px-2.5 py-1 rounded shadow-xs"
              >
                Delete Permanently
              </button>
            </template>
          </div>
        </div>
      </div>
    </template>

    <div v-else class="flex-1 flex items-center justify-center text-gray-400 text-sm">
      Select a box on the left to view items
    </div>
  </div>
</template>