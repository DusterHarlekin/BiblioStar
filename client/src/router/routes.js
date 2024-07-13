const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    meta: { requiresAuth: true },
    children: [{ path: "", component: () => import("pages/IndexPage.vue") }],
  },
  {
    path: "/login",
    component: () => import("layouts/LoginLayout.vue"),
    meta: { requiresUnauth: true },
    children: [
      { path: "", component: () => import("pages/auth/UserLogin.vue") },
      {
        path: "invitado",
        component: () => import("pages/auth/GuestLogin.vue"),
      },
    ],
  },
  {
    path: "/libros",
    component: () => import("layouts/MainLayout.vue"),
    meta: { requiresAuth: true },
    children: [
      { path: "", component: () => import("pages/books/BookList.vue") },
    ],
  },

  {
    path: "/cotas",
    component: () => import("layouts/MainLayout.vue"),
    meta: { requiresAuth: true },
    children: [
      { path: "", component: () => import("pages/quotes/QuoteList.vue") },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
