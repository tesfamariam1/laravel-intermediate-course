<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import router from "../router";

const user = ref(null);
const loading = ref(true);
const error = ref("");
const logout = () => {
  const token = localStorage.getItem("authToken");

  axios.post("/api/logout", {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });

  router.push("/");
};
onMounted(async () => {
  const token = localStorage.getItem("authToken");
  try {
    const response = await axios.get("/api/profile", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    user.value = response.data;
  } catch (err) {
    error.value = "Failed to fetch user info";
  } finally {
    loading.value = false;
  }
});
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md text-center">
      <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
      <div v-if="loading" class="text-gray-500">Loading...</div>
      <div v-else-if="error" class="text-red-600">{{ error }}</div>
      <div v-else-if="user">
        <p class="mb-2">
          <span class="font-semibold">Name:</span> {{ user.name }}
        </p>
        <p><span class="font-semibold">Email:</span> {{ user.email }}</p>
      </div>
      <button
        class="my-2 text-white bg-blue-400 p-2 rounded-lg"
        @click.prevent="logout"
      >
        logout
      </button>
    </div>
  </div>
</template>
