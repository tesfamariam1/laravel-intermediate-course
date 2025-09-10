<script setup>
import { ref } from "vue";
import axios from "axios";
import router from "../router";

const email = ref("");
const password = ref("");
const loading = ref(false);
const error = ref("");
const success = ref("");

const login = async () => {
  error.value = "";
  success.value = "";
  loading.value = true;
  try {
    const response = await axios.post("/api/login",
      {
        email: email.value,
        password: password.value,
      },
    );
    console.log(response.data.token, "response from login");

    localStorage.setItem("authToken", response.data.token);

    router.push("/dashboard");

    success.value = "Login successful!";
    // Optionally, store token or redirect here
  } catch (err) {
    error.value = err.response?.data?.message || "Login failed";
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <form
      @submit.prevent="login"
      class="bg-white p-8 rounded shadow-md w-full max-w-sm"
    >
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
      <div class="mb-4">
        <label class="block mb-1 font-medium">Email</label>
        <input
          v-model="email"
          type="email"
          required
          class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
        />
      </div>
      <div class="mb-6">
        <label class="block mb-1 font-medium">Password</label>
        <input
          v-model="password"
          type="password"
          required
          class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
        />
      </div>
      <button
        type="submit"
        :disabled="loading"
        class="w-full bg-blue-600 text-white py-2 rounded font-semibold hover:bg-blue-700 transition disabled:bg-blue-300"
      >
        {{ loading ? "Logging in..." : "Login" }}
      </button>

      <div v-if="error" class="text-red-600 mt-4 text-center">{{ error }}</div>
      <div v-if="success" class="text-green-600 mt-4 text-center">
        {{ success }}
      </div>
    </form>
  </div>
</template>
