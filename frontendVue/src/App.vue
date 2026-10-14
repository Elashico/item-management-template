<script setup>
import { ref, computed, onMounted } from 'vue'
import Header from './components/Header.vue'
import Body from './components/Body.vue'
import api from './services/api'

const boxes = ref([])
const items = ref([])
const selectedBoxId = ref(null)

// 1. GET /api/box returns boxes with nested items; split them into flat lists
const fetchData = async () => {
  try {
    const response = await api.get('/box')

    boxes.value = response.data.map(({ items, ...box }) => box)
    items.value = response.data.flatMap(box => box.items)

    // select the first active (non-deleted) box if nothing is selected
    if (!selectedBoxId.value) {
      selectedBoxId.value = boxes.value.find(b => !b.deleted_at)?.id ?? null
    }
  } catch (error) {
    console.error('Error fetching data from backend:', error)
  }
}

onMounted(() => {
  fetchData()
})

const selectedBox = computed(() => boxes.value.find(b => b.id === selectedBoxId.value))
const currentItems = computed(() => items.value.filter(i => i.box_id === selectedBoxId.value))

// 2. Handlers mapped to Laravel API endpoints
const handleSelectBox = (id) => {
  selectedBoxId.value = id
}

const handleDeleteBox = async (id) => {
  try {
    await api.delete(`/box/${id}`)
    await fetchData()

    if (selectedBoxId.value === id) {
      selectedBoxId.value = boxes.value.find(b => !b.deleted_at)?.id ?? null
    }
  } catch (error) {
    console.error('Error deleting box:', error)
  }
}

const handleRestoreBox = async (id) => {
  try {
    await api.patch(`/box/${id}/restore`)
    await fetchData()
  } catch (error) {
    console.error('Error restoring box:', error)
  }
}

const handleForceDeleteBox = async (id) => {
  try {
    await api.delete(`/box/${id}/force`)
    await fetchData()

    if (selectedBoxId.value === id) {
      selectedBoxId.value = boxes.value.find(b => !b.deleted_at)?.id ?? null
    }
  } catch (error) {
    console.error('Error permanently deleting box:', error)
  }
}

const handleForceDeleteItem = async (id) => {
  try {
    await api.delete(`/item/${id}/force`)
    await fetchData()
  } catch (error) {
    console.error('Error permanently deleting item:', error)
  }
}

const handleAddBox = async (name) => {
  try {
    const response = await api.post('/box', { name })
    boxes.value.push(response.data)
    selectedBoxId.value = response.data.id
  } catch (error) {
    console.error('Error adding box:', error)
  }
}

const handleAddItem = async (name) => {
  try {
    const response = await api.post('/item', {
      box_id: selectedBoxId.value,
      name
    })
    items.value.push(response.data)
  } catch (error) {
    console.error('Error adding item:', error)
  }
}

const handleUpdateItem = async ({ id, name }) => {
  try {
    const response = await api.patch(`/item/${id}`, { name })
    const index = items.value.findIndex(i => i.id === id)
    if (index !== -1) {
      items.value[index] = response.data
    }
  } catch (error) {
    console.error('Error updating item:', error)
  }
}

const handleDeleteItem = async (id) => {
  try {
    await api.delete(`/item/${id}`)
    const item = items.value.find(i => i.id === id)
    if (item) {
      item.deleted_at = new Date().toISOString().slice(0, 19).replace('T', ' ')
    }
  } catch (error) {
    console.error('Error deleting item:', error)
  }
}

const handleRestoreItem = async (id) => {
  try {
    const response = await api.patch(`/item/${id}/restore`)
    const index = items.value.findIndex(i => i.id === id)
    if (index !== -1) {
      items.value[index] = response.data
    }
  } catch (error) {
    console.error('Error restoring item:', error)
  }
}

const handleUpdateBox = async ({ id, name }) => {
  try {
    await api.patch(`/box/${id}`, { name })
    await fetchData()
  } catch (error) {
    console.error('Error updating box:', error)
  }
}

</script>

<template>
  <div class="h-screen w-screen flex flex-col bg-gray-100 overflow-hidden">
    <Header />
    <Body
      :boxes="boxes"
      :selectedBoxId="selectedBoxId"
      :selectedBox="selectedBox"
      :currentItems="currentItems"
      @select-box="handleSelectBox"
      @add-box="handleAddBox"
      @add-item="handleAddItem"
      @update-item="handleUpdateItem"
      @delete-item="handleDeleteItem"
      @restore-item="handleRestoreItem"
      @update-box="handleUpdateBox"
      @delete-box="handleDeleteBox"
      @restore-box="handleRestoreBox"
      @force-delete-box="handleForceDeleteBox"
      @force-delete-item="handleForceDeleteItem"
    />
  </div>
</template>