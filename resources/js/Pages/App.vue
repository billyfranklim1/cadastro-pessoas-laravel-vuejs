<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>
<template>
    <v-app-bar :elevation="2">
    </v-app-bar>
</template>
<style></style>
