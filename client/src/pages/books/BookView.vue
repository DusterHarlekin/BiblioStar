<template>
  <q-page class="q-px-lg q-py-md">
    <div class="row q-gutter-sm items-center q-mb-md">
      <div>
        <q-btn
          icon="mdi-chevron-left"
          flat
          color="primary"
          padding="none"
          to="/libros"
          size="lg"
          class="q-mr-sm"
        />
      </div>
      <q-icon name="mdi-book" size="md" color="grey-9" />
      <div class="text-h5 text-weight-medium font-title text-grey-9">
        Libro "{{ currentBook.titulo }}"
      </div>
    </div>

    <q-card class="q-pa-md q-ma-lg">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <q-list>
            <q-item>
              <q-item-section>
                <q-item-label caption>Cota completa</q-item-label>
                <q-item-label>{{ currentBook.cota_completa }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Código ISBN</q-item-label>
                <q-item-label>{{ currentBook.cod_isbn }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Cota</q-item-label>
                <q-item-label>{{ currentBook.cota }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Cutter</q-item-label>
                <q-item-label>{{ currentBook.cutter }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Título</q-item-label>
                <q-item-label>{{ currentBook.titulo }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Autor</q-item-label>
                <q-item-label>{{ currentBook.autor }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Editorial</q-item-label>
                <q-item-label>{{ currentBook.editorial }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Edición</q-item-label>
                <q-item-label>{{ currentBook.edicion }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Tomo</q-item-label>
                <q-item-label>{{ currentBook.tomo }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Año de publicación</q-item-label>
                <q-item-label>{{ currentBook.anio }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Idioma</q-item-label>
                <q-item-label>{{ currentBook.idioma }}</q-item-label>
              </q-item-section>
            </q-item>
          </q-list>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
          <q-list>
            <q-item>
              <q-item-section>
                <q-item-label caption>Sala</q-item-label>
                <q-item-label>{{ currentBook.cod_sala }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Pais</q-item-label>
                <q-item-label>{{ currentBook.pais }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Ciudad</q-item-label>
                <q-item-label>{{ currentBook.ciudad }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Número de páginas</q-item-label>
                <q-item-label>{{ currentBook.pag }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Fecha de ingreso</q-item-label>
                <q-item-label>{{ currentBook.fecha_ing }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Impresión</q-item-label>
                <q-item-label>{{ currentBook.impresion }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Costo</q-item-label>
                <q-item-label>{{ currentBook.costo }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Código de referencia</q-item-label>
                <q-item-label>{{ currentBook.cod_referencia }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Participante</q-item-label>
                <q-item-label>{{ currentBook.participante }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Descripción</q-item-label>
                <q-item-label>{{ currentBook.descripcion }}</q-item-label>
              </q-item-section>
            </q-item>

            <q-item>
              <q-item-section>
                <q-item-label caption>Observación</q-item-label>
                <q-item-label>{{ currentBook.observacion }}</q-item-label>
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

const bookId = $route.params.id;
const currentBook = ref({
  N: "",
  cota: "",
  cod_isbn: "",
  autor: "",
  titulo: "",
  pais: "",
  editorial: "",
  edicion: "",
  ciudad: "",
  anio: "",
  tomo: "",
  pag: "",
  descripcion: "",
  cod_sala: "",
  cod_referencia: "",
  costo: "",
  fecha_ing: "",
  idioma: "",
  participante: "",
  impresion: "",
  observacion: "",
  cutter: "",
  cota_completa: "",
});

// Fetch ship from backend
const fetchBook = async () => {
  const requestOptions = {
    method: "GET",
    headers: { "Content-Type": "application/json" },
  };

  // API URL
  const url =
    process.env.API_URL +
    `libros.php?N=${bookId}&session_user_name=${localStorage.getItem(
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

  currentBook.value = clearSpaces(data[0]);
};

const clearSpaces = (obj) => {
  for (const key in obj) {
    if (obj[key].trim() === "") {
      obj[key] = "--";
    }
  }
  return obj;
};

fetchBook();
</script>
