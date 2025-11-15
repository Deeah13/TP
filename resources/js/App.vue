<template>
  <router-view />
</template>

<script setup>
import { onMounted } from 'vue';
import { useAuth } from './stores/auth';

const { hydrate } = useAuth();

onMounted(() => {
  hydrate();
});
</script>

<style>
html,
body {
  margin: 0;
  padding: 0;
}
</style>
