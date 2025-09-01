import { createRouter, createWebHistory } from "vue-router";
import axios from "axios";

const routes = [
  {
    path: "/",
    component: () => import("@/pages/index.vue"),
  },
  {
    path: "/dashboard",
    component: () => import("@/pages/Dashboard.vue"),
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0, behavior: "smooth" };
  },
});

// Navigation guard for authentication
router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    try {
      const token = localStorage.getItem("authToken");
      await axios.get("/api/profile", {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      next();
    } catch {
      next({ path: "/" });
    }
  } else {
    next();
  }
});

export default router;
