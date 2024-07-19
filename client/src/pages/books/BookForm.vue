<template>
  <q-page class="q-px-lg q-py-sm">
    <div class="flex justify-between">
      <div class="row q-gutter-sm items-center q-mb-md q-pt-sm">
        <q-btn
          icon="mdi-chevron-left"
          flat
          color="primary"
          padding="none"
          to="/libros"
          size="lg"
          class="q-mr-sm"
        />
        <q-icon name="mdi-book" size="md" color="grey-9" />
        <div class="text-h5 text-weight-medium font-title text-grey-9">
          {{ isEditForm ? "Editar" : "Nuevo" }} Libro
        </div>
      </div>
      <div class="text-right font-title">
        <q-btn
          size="md"
          class="q-my-md"
          :loading="isLoading"
          color="primary"
          padding="sm md"
          icon="mdi-check-circle-outline"
          label="Guardar"
          @click="submitForm"
        />
      </div>
    </div>
    <q-form>
      <q-card>
        <div class="row">
          <div class="col-md-6 col-xs-12">
            <q-card-section class="q-pb-none">
              <div class="row q-py-none q-mb-none items-center">
                <div class="col-12 col-lg-9">
                  <q-select
                    v-if="!isNewQuote"
                    v-model="state.cota_n"
                    label="Cota completa"
                    class="required q-mt-md"
                    use-input
                    input-debounce="400"
                    option-label="label"
                    option-value="value"
                    emit-value
                    map-options
                    outlined
                    :options="quoteOptions"
                    @filter="quoteFilterFn"
                  >
                    <template #no-option>
                      <q-item>
                        <q-item-section class="text-grey">
                          No se encontraron resultados
                        </q-item-section>
                      </q-item>
                    </template>
                  </q-select>

                  <q-input
                    v-model="state.cota"
                    label="Cota"
                    class="required q-mt-md"
                    outlined
                    v-if="isNewQuote"
                  />
                </div>
                <div class="col-12 col-lg-3">
                  <q-toggle
                    class="text-subtitle2"
                    label="Nueva cota"
                    v-model="isNewQuote"
                  />
                </div>
              </div>

              <div v-if="isNewQuote">
                <q-input
                  v-model="state.cod_isbn"
                  label="Código ISBN"
                  class="required q-my-md"
                  outlined
                />
                <q-input
                  v-model="state.cutter"
                  label="Cutter"
                  class="required q-my-md"
                  outlined
                />

                <q-input
                  v-model="state.cota_completa"
                  label="Cota completa"
                  class="required q-my-md"
                  outlined
                />

                <q-input
                  v-model="state.volumen"
                  label="Volumen"
                  class="required q-my-md"
                  outlined
                />
                <q-input
                  v-model="state.ejemplar"
                  label="Ejemplares"
                  class="required q-my-md"
                  outlined
                />
              </div>

              <q-input
                v-model="record.titulo"
                label="Título"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.autor"
                label="Autor"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.editorial"
                label="Editorial"
                class="required q-my-md"
                outlined
              />
              <q-input
                v-model="record.edicion"
                label="Edición"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.descripcion"
                label="Descripción"
                class="required q-my-md"
                outlined
                type="textarea"
              />
            </q-card-section>
          </div>

          <div class="col-md-6 col-xs-12">
            <q-card-section class="q-pb-none">
              <q-select
                v-model="record.cod_sala"
                label="Sala"
                class="required q-mt-md"
                use-input
                input-debounce="400"
                option-label="label"
                option-value="value"
                emit-value
                map-options
                outlined
                :options="roomOptions"
                @filter="roomFilterFn"
              >
                <template #no-option>
                  <q-item>
                    <q-item-section class="text-grey">
                      No se encontraron resultados
                    </q-item-section>
                  </q-item>
                </template>
              </q-select>

              <q-input
                v-model="record.tomo"
                label="Tomo"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.anio"
                label="Año de publicación"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.pag"
                label="Número de páginas"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.cod_referencia"
                label="Código de referencia"
                class="required q-my-md"
                outlined
              />

              <q-input
                v-model="record.impresion"
                label="Tipo de impresión"
                class="required q-my-md"
                outlined
              />

              <!-- :options="itemOptions"
              @filter="itemFilterFn" -->

              <q-select
                v-model="record.idioma"
                label="Idioma"
                class="required q-mt-md"
                use-input
                input-debounce="400"
                option-label="label"
                option-value="value"
                emit-value
                map-options
                outlined
                :options="langOptions"
                @filter="langFilterFn"
              >
                <template #no-option>
                  <q-item>
                    <q-item-section class="text-grey">
                      No se encontraron resultados
                    </q-item-section>
                  </q-item>
                </template>
              </q-select>

              <!-- :options="itemOptions"
              @filter="itemFilterFn" -->

              <q-select
                v-model="record.pais"
                label="Pais"
                class="required q-mt-md"
                use-input
                input-debounce="400"
                option-label="label"
                option-value="value"
                emit-value
                map-options
                outlined
                :options="countryOptions"
                @filter="countryFilterFn"
                @input="clearCities"
              >
                <template #no-option>
                  <q-item>
                    <q-item-section class="text-grey">
                      No se encontraron resultados
                    </q-item-section>
                  </q-item>
                </template>
              </q-select>

              <!-- :options="itemOptions"
              @filter="itemFilterFn" -->

              <q-select
                v-model="record.ciudad"
                label="Ciudad"
                class="required q-mt-md"
                :disable="!record.pais"
                use-input
                input-debounce="400"
                option-label="label"
                option-value="value"
                emit-value
                map-options
                outlined
                :options="cityOptions"
                @filter="cityFilterFn"
              >
                <template #no-option>
                  <q-item>
                    <q-item-section class="text-grey">
                      No se encontraron resultados
                    </q-item-section>
                  </q-item>
                </template>
              </q-select>

              <q-input
                v-model="record.costo"
                label="Costo del libro"
                class="required q-my-md"
                outlined
              />

              <!-- :options="itemOptions"
              @filter="itemFilterFn" -->

              <q-input
                v-model="record.observacion"
                label="Observaciones"
                class="required q-my-md"
                type="textarea"
                outlined
              />
            </q-card-section>
          </div>
        </div>
      </q-card>
    </q-form>
  </q-page>
</template>

<script setup>
import { date, useQuasar } from "quasar";

import { reactive, ref, toRefs } from "vue";

import { useRouter, useRoute } from "vue-router";

const $q = useQuasar();
const $router = useRouter();
const $route = useRoute();

const isLoading = ref(false);

const isNewQuote = ref(true);

const roomOptions = ref([]);
const quoteOptions = ref([]);
const langOptions = ref([]);
const countryOptions = ref([]);
const cityOptions = ref([]);
const isEditForm =
  $route.path.includes("editar") && $route.params.id ? true : false;

const state = reactive({
  record: {
    titulo: "",
    autor: "",
    editorial: "",
    edicion: "",
    cod_sala: "",
    tomo: "",
    anio: "",
    pag: "0",
    cod_referencia: "",
    costo: "0",
    impresion: "",

    isNewQuote: true,
    fecha_ing: date.formatDate(Date.now(), "DD/MM/YYYY"),

    session_user_name: localStorage.getItem("usuario"),
    session_user_role: localStorage.getItem("rol"),
  },
  cota: "",
  cod_isbn: "",
  cutter: "",
  volumen: "",
  ejemplar: "",
  cota_completa: "",

  cota_n: "",
});

const clearCities = () => {
  cityOptions.value = [];
  state.record.ciudad = "";
};

const roomFilterFn = (textInput, update) => {
  update(async () => {
    if (textInput.length > 0) {
      let data = await fetchEntries({ cod_sala: textInput }, "salas");
      roomOptions.value = [];
      if (data.data) {
        for (const entry of data.data) {
          roomOptions.value.push({
            label: entry.cod_sala,
            value: entry.cod_sala,
          });
        }
      }
    }
  });
};

const quoteFilterFn = (textInput, update) => {
  update(async () => {
    if (textInput.length > 0) {
      let data = await fetchEntries({ cota_completa: textInput }, "cotas");
      quoteOptions.value = [];
      if (data.data) {
        for (const entry of data.data) {
          quoteOptions.value.push({
            label: entry.cota_completa,
            value: entry.N,
          });
        }
      }
    }
  });
};

const langFilterFn = (textInput, update) => {
  update(async () => {
    if (textInput.length > 0) {
      let data = await fetchEntries({ cod_idioma: textInput }, "idiomas");
      langOptions.value = [];
      if (data.data) {
        for (const entry of data.data) {
          langOptions.value.push({
            label: entry.cod_idioma,
            value: entry.cod_idioma,
          });
        }
      }
    }
  });
};

const countryFilterFn = (textInput, update) => {
  update(async () => {
    if (textInput.length > 0) {
      let data = await fetchEntries({ codigo: textInput }, "paises");
      countryOptions.value = [];
      if (data.data) {
        for (const entry of data.data) {
          countryOptions.value.push({
            label: `${entry.codigo} - ${entry.pais}`,
            value: entry.codigo,
          });
        }
      }
    }
  });
};

const cityFilterFn = (textInput, update) => {
  update(async () => {
    if (textInput.length > 0) {
      let data = await fetchEntries(
        { codigo_ciudad: textInput, codigo_pais: state.record.pais },
        "ciudades"
      );
      cityOptions.value = [];
      if (data.data) {
        for (const entry of data.data) {
          cityOptions.value.push({
            label: entry.codigo_ciudad,
            value: entry.codigo_ciudad,
          });
        }
      }
    }
  });
};

const fetchEntries = async (filter, file) => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };

    // API URL
    let url =
      process.env.API_URL +
      `${file}.php?page=1&session_user_name=${localStorage.getItem(
        "usuario"
      )}&session_user_role=${localStorage.getItem("rol")}`;

    let params = new URLSearchParams(filter);
    let keysForDel = [];
    params.forEach((value, key) => {
      if (value.trim() == "") {
        keysForDel.push(key);
      }
    });

    keysForDel.forEach((key) => {
      params.delete(key);
    });

    if (params.toString() != "") {
      url += `&${params.toString()}`;
    }

    const response = await fetch(url, requestOptions);
    if (!response.ok) {
      throw new Error(response.statusText);
    }
    const data = await response.json();

    if (data.error) {
      throw new Error(data.error);
    }
    return data;
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "mdi-alert",
    });
  }
};

const syncChanges = async () => {
  try {
    if (isEditForm) {
      const requestOptions = {
        method: "GET",
        headers: { "Content-Type": "application/json" },
      };

      // API URL
      const url =
        process.env.API_URL +
        `libros.php?N=${
          $route.params.id
        }&session_user_name=${localStorage.getItem(
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

      state.record = data[0];

      const urlCotas =
        process.env.API_URL +
        `cotas.php?cota_completa=${
          state.record.cota_completa
        }&session_user_name=${localStorage.getItem(
          "usuario"
        )}&session_user_role=${localStorage.getItem("rol")}`;

      const responseCotas = await fetch(urlCotas, requestOptions);
      if (!responseCotas.ok) {
        throw new Error(responseCotas.statusText);
      }
      if (state.record.cota_completa.trim() != "") {
        const dataCotas = await responseCotas.json();
        if (dataCotas.error) {
          throw new Error(dataCotas.error);
        }

        let foundQuote = dataCotas.data ? dataCotas.data[0] : null;

        if (foundQuote) {
          quoteOptions.value = [];
          quoteOptions.value.push({
            label: foundQuote.cota_completa,
            value: foundQuote.N,
          });

          state.cota_n = foundQuote.N;
        } else {
          state.cota_n = "";
        }
      }
      state.record.session_user_name = localStorage.getItem("usuario");
      state.record.session_user_role = localStorage.getItem("rol");
      isNewQuote.value = false;
    }
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "mdi-alert",
    });
  }
};

const submitForm = async () => {
  try {
    if (isNewQuote.value) {
      state.record.cota = state.cota;
      state.record.cod_isbn = state.cod_isbn;
      state.record.cutter = state.cutter;
      state.record.volumen = state.volumen;
      state.record.ejemplar = state.ejemplar;
      state.record.cota_completa = state.cota_completa;
    } else {
      delete state.record.cota;
      delete state.record.cod_isbn;
      delete state.record.cutter;
      delete state.record.volumen;
      delete state.record.ejemplar;
      delete state.record.cota_completa;

      state.record.cota_n = state.cota_n;
    }

    state.record.isNewQuote = isNewQuote.value;
    state.record.participante = "BPCPT";

    state.record.descripcion = state.record.descripcion
      ? state.record.descripcion
      : "NINGUNA";

    state.record.observacion = state.record.observacion
      ? state.record.observacion
      : "NINGUNA";

    const requestOptions = {
      method: isEditForm ? "PUT" : "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(state.record),
    };

    const response = await fetch(
      process.env.API_URL + "libros.php",
      requestOptions
    );
    if (!response.ok) {
      throw new Error(response.statusText);
    }
    const data = await response.json();
    if (data.error) {
      throw new Error(data.error);
    }
    $q.notify({
      color: "positive",
      position: "top",
      message: data.success,
      icon: "mdi-checkbox-marked-circle",
    });
    $router.push({ path: "/libros" });
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "mdi-alert",
    });
  }
};

syncChanges();
const { record } = toRefs(state);
</script>
