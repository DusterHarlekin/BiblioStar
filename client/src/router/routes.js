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
      { path: "nuevo", component: () => import("pages/books/BookForm.vue") },
      {
        path: "editar/:id",
        component: () => import("pages/books/BookForm.vue"),
      },
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
  {
    path: "/salas",
    component: () => import("layouts/MainLayout.vue"),
    meta: { requiresAuth: true },
    children: [
      { path: "", component: () => import("pages/rooms/RoomList.vue") },
    ],
  },
  {
    path: "/paises",
    component: () => import("layouts/MainLayout.vue"),
    meta: { requiresAuth: true },
    children: [
      { path: "", component: () => import("pages/countrys/CountryList.vue") },
    ],
  },
  {
    path: "/ciudades",
    component: () => import("layouts/MainLayout.vue"),
    meta: { requiresAuth: true },
    children: [
      { path: "", component: () => import("pages/cities/CityList.vue") },
    ],
  },
  {
    path: "/idiomas",
    component: () => import("layouts/MainLayout.vue"),
    meta: { requiresAuth: true },
    children: [
      { path: "", component: () => import("pages/languages/LanguageList.vue") },
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
