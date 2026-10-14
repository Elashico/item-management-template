<script setup>
import { ref, computed } from 'vue'
import Header from './components/Header.vue'
import Body from './components/Body.vue'

// Mock state (replace with API calls later)
const boxes = ref([
  { id: 1, name: 'Loading Box Alpha' },
  { id: 2, name: 'Loading Box Beta' },
  { id: 3, name: 'Loading Box Gamma' }
])

const selectedBoxId = ref(1)

const items = ref([
  { id: 101, boxId: 1, name: 'First Item', created_at: '2026-10-14 10:00', updated_at: '2026-10-14 10:00', deleted_at: null },
  { id: 102, boxId: 1, name: 'Second Item', created_at: '2026-10-14 10:05', updated_at: '2026-10-14 10:05', deleted_at: null },
  { id: 201, boxId: 2, name: 'Beta Item One', created_at: '2026-10-14 11:00', updated_at: '2026-10-14 11:00', deleted_at: null }
])

const selectedBox = computed(() => boxes.value.find(b => b.id === selectedBoxId.value))
const currentItems = computed(() => items.value.filter(i => i.boxId === selectedBoxId.value))

// Handlers
const handleSelectBox = (id) => {
  selectedBoxId.value = id
}

const handleAddBox = (name) => {
  const newBox = { id: Date.now(), name }
  boxes.value.push(newBox)
  selectedBoxId.value = newBox.id
}

const handleAddItem = (name) => {
  const now = new Date().toISOString().slice(0, 16).replace('T', ' ')
  items.value.push({
    id: Date.now(),
    boxId: selectedBoxId.value,
    name,
    created_at: now,
    updated_at: now,
    deleted_at: null
  })
}

const handleUpdateItem = ({ id, name }) => {
  const item = items.value.find(i => i.id === id)
  if (item) {
    item.name = name
    item.updated_at = new Date().toISOString().slice(0, 16).replace('T', ' ')
  }
}

const handleDeleteItem = (id) => {
  const item = items.value.find(i => i.id === id)
  if (item) {
    const now = new Date().toISOString().slice(0, 16).replace('T', ' ')
    item.deleted_at = now
    item.updated_at = now
  }
}

const handleRestoreItem = (id) => {
  const item = items.value.find(i => i.id === id)
  if (item) {
    item.deleted_at = null
    item.updated_at = new Date().toISOString().slice(0, 16).replace('T', ' ')
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
    />
  </div>
</template>