<script setup>
import BoxPanel from './BoxPanel.vue'
import ItemsPanel from './ItemsPanel.vue'

defineProps({
  boxes: Array,
  selectedBoxId: [Number, String],
  selectedBox: Object,
  currentItems: Array
})

defineEmits(['select-box', 'add-box', 'add-item', 'update-item', 'delete-item', 
'restore-item','update-box', 'delete-box', 'restore-box',
'force-delete-box','force-delete-item'
])

</script>

<template>
  <main class="flex-1 flex overflow-hidden">
    <BoxPanel 
      :boxes="boxes" 
      :selectedBoxId="selectedBoxId"
      @select-box="$emit('select-box', $event)"
      @add-box="$emit('add-box', $event)"
      @update-box="$emit('update-box', $event)"
      @delete-box="$emit('delete-box', $event)"
      @restore-box="$emit('restore-box', $event)"
      @force-delete-box="$emit('force-delete-box', $event)"
    />
    <ItemsPanel 
      :selectedBox="selectedBox"
      :items="currentItems"
      @add-item="$emit('add-item', $event)"
      @update-item="$emit('update-item', $event)"
      @delete-item="$emit('delete-item', $event)"
      @restore-item="$emit('restore-item', $event)"
      @force-delete-item="$emit('force-delete-item', $event)"
    />
  </main>
</template>