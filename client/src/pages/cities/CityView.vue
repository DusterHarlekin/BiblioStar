<template>
  <q-page class="q-px-lg q-py-md">
    <div class="row q-gutter-sm items-center q-mb-md">
      <div>
        <q-btn
          icon="mdi-chevron-left"
          flat
          color="primary"
          padding="none"
          to="/ciudades"
          size="lg"
          class="q-mr-sm"
        />
      </div>
      <q-icon name="mdi-home-city" size="md" color="grey-9" />
      <div class="text-h5 text-weight-medium font-title text-grey-9">
        Ciudad "{{ currentCity.ciudad }}"
      </div>
    </div>

    <q-card class="q-pa-md q-ma-lg">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <q-list>
            <q-item>
              <q-item-section>
                <q-item-label caption>Código de ciudad</q-item-label>
                <q-item-label>{{ currentCity.codigo_ciudad }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Ciudad</q-item-label>
                <q-item-label>{{ currentCity.ciudad }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>País</q-item-label>
                <q-item-label>{{ currentCity.codigo_pais }}</q-item-label>
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

const cityId = $route.params.id;
const currentCity = ref({
  codigo_pais: "",
  codigo_ciudad: "",
  ciudad: "",
});

// Fetch ship from backend
const fetchCity = async () => {
  const requestOptions = {
    method: "GET",
    headers: { "Content-Type": "application/json" },
  };

  // API URL
  const url =
    process.env.API_URL +
    `ciudades.php?codigo_ciudad=${cityId}&session_user_name=${localStorage.getItem(
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

  currentCity.value = clearSpaces(data[0]);
};

const clearSpaces = (obj) => {
  for (const key in obj) {
    if (obj[key].trim() === "") {
      obj[key] = "--";
    }
  }
  return obj;
};

fetchCity();
</script>
