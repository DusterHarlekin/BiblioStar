<template>
  <q-page class="q-px-lg q-py-md">
    <div class="row q-gutter-sm items-center q-mb-md">
      <div>
        <q-btn
          icon="mdi-chevron-left"
          flat
          color="primary"
          padding="none"
          to="/lectores"
          size="lg"
          class="q-mr-sm"
        />
      </div>
      <q-icon name="mdi-account-box-outline" size="md" color="grey-9" />
      <div class="text-h5 text-weight-medium font-title text-grey-9">
        Lector "{{ currentReader.nombre }} {{ currentReader.apellido }}"
      </div>
    </div>

    <q-card class="q-pa-md q-ma-lg">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <q-list>
            <q-item>
              <q-item-section>
                <q-item-label caption>Cédula</q-item-label>
                <q-item-label>{{ currentReader.cedula }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Nombre</q-item-label>
                <q-item-label>{{ currentReader.nombre }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Apellido</q-item-label>
                <q-item-label>{{ currentReader.apellido }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Dirección</q-item-label>
                <q-item-label>{{ currentReader.direccion }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Teléfono</q-item-label>
                <q-item-label>{{ currentReader.telefono }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Correo</q-item-label>
                <q-item-label>{{ currentReader.correo }}</q-item-label>
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section>
                <q-item-label caption>Edad</q-item-label>
                <q-item-label>{{ currentReader.edad }}</q-item-label>
              </q-item-section>
            </q-item>
            <q-item>
              <q-item-section>
                <q-item-label caption>Sexo</q-item-label>
                <q-item-label>{{ currentReader.sexo }}</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </div>
      </div>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from "vue";

import { useRoute } from "vue-router";

const $route = useRoute();

const readerId = $route.params.id;
const currentReader = ref({
  N: "",
  cedula: "",
  nombre: "",
  apellido: "",
  direccion: "",
  telefono: "",
  correo: "",
  edad: "",
  sexo: "",
});

// Fetch ship from backend
const fetchReader = async () => {
  const requestOptions = {
    method: "GET",
    headers: { "Content-Type": "application/json" },
  };

  // API URL
  const url =
    process.env.API_URL +
    `lectores.php?N=${readerId}&session_user_name=${localStorage.getItem(
      "usuario"
    )}&session_user_role=${localStorage.getItem("rol")}`;

  const response = await fetch(url, requestOptions);

  if (!response.ok) {
    throw new Error(response.statusText);
  }
  const data = await response.json();

  if (data.error) {
    throw new Error(data.error);
  }

  currentReader.value = clearSpaces(data[0]);
};

const clearSpaces = (obj) => {
  for (const key in obj) {
    if (obj[key].trim() === "") {
      obj[key] = "--";
    }
  }
  return obj;
};

fetchReader();
</script>
