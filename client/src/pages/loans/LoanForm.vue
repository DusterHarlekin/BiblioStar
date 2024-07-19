<template>
  <q-page class="q-px-lg q-py-sm">
    <div class="flex justify-between">
      <div class="row q-gutter-sm items-center q-mb-md q-pt-sm">
        <q-btn
          icon="mdi-chevron-left"
          flat
          color="primary"
          padding="none"
          to="/prestamos"
          size="lg"
          class="q-mr-sm"
        />
        <q-icon name="mdi-book" size="md" color="grey-9" />
        <div class="text-h5 text-weight-medium font-title text-grey-9">
          {{ isEditForm ? "Editar" : "Nuevo" }} Préstamo
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
          <div class="col-12">
            <q-card-section class="q-pb-none">
              <q-select
                v-model="record.cod_isbn"
                label="Código ISBN"
                class="required q-mt-md"
                use-input
                input-debounce="400"
                option-label="label"
                option-value="value"
                map-options
                outlined
                :options="bookOptions"
                @filter="bookFilterFn"
                @update:model-value="assignTitle($event)"
                :disable="isEditForm"
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
                v-model="record.cedula"
                label="Cédula del lector"
                class="required q-mt-md"
                use-input
                input-debounce="400"
                option-label="label"
                option-value="value"
                emit-value
                map-options
                outlined
                :options="readerOptions"
                @filter="readerFilterFn"
              >
                <template #no-option>
                  <q-item>
                    <q-item-section class="text-grey">
                      No se encontraron resultados
                    </q-item-section>
                  </q-item>
                </template>

                <template v-slot:after>
                  <q-btn
                    color="primary"
                    @click="createReader"
                    class="q-px-sm q-py-sm"
                  >
                    <q-icon name="mdi-plus" size="lg" />
                  </q-btn>
                </template>
              </q-select>
              <q-input
                v-model="record.observacion"
                label="Observaciones"
                class="required q-my-md"
                outlined
                type="textarea"
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
import ReaderForm from "components/readers/ReaderForm.vue";

import { reactive, ref, toRefs } from "vue";

import { useRouter, useRoute } from "vue-router";

const $q = useQuasar();
const $router = useRouter();
const $route = useRoute();

const isLoading = ref(false);
const bookOptions = ref([]);
const readerOptions = ref([]);

const isEditForm =
  $route.path.includes("editar") && $route.params.id ? true : false;

const state = reactive({
  record: {
    cod_isbn: "",
    titulo: "",
    cedula: "",
    observacion: "",

    session_user_name: localStorage.getItem("usuario"),
    session_user_role: localStorage.getItem("rol"),
  },
});

const readerFilterFn = (textInput, update) => {
  update(async () => {
    if (textInput.length > 0) {
      let data = await fetchEntries({ cedula: textInput }, "lectores");
      readerOptions.value = [];
      if (data.data) {
        for (const entry of data.data) {
          readerOptions.value.push({
            label: `${entry.cedula} - ${entry.nombre} ${entry.apellido}`,
            value: entry.cedula,
          });
        }
      }
    }
  });
};

const bookFilterFn = (textInput, update) => {
  update(async () => {
    if (textInput.length > 0) {
      let data = await fetchEntries({ cod_isbn: textInput }, "libros");
      bookOptions.value = [];
      if (data.data) {
        for (const entry of data.data) {
          bookOptions.value.push({
            label: `${entry.cod_isbn} - ${entry.titulo}`,
            value: entry.cod_isbn,
            titulo: entry.titulo,
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
        `prestamos.php?cod_isbn=${
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
      state.record.cod_isbn = {
        label: `${state.record.cod_isbn} - ${state.record.titulo}`,
        value: state.record.cod_isbn,
        titulo: state.record.titulo,
      };

      state.record.session_user_name = localStorage.getItem("usuario");
      state.record.session_user_role = localStorage.getItem("rol");
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

const assignTitle = async (event) => {
  state.record.titulo = event.titulo;
};
const submitForm = async () => {
  try {
    state.record.observacion = state.record.observacion
      ? state.record.observacion
      : "NINGUNA";

    state.record.cod_isbn = state.record.cod_isbn.value
      ? state.record.cod_isbn.value
      : state.record.cod_isbn;
    console.log(state.record.cod_isbn);

    const requestOptions = {
      method: isEditForm ? "PUT" : "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(state.record),
    };

    const response = await fetch(
      process.env.API_URL + "prestamos.php",
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
    $router.push({ path: "/prestamos" });
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "mdi-alert",
    });
  }
};
const createReader = async () => {
  $q.dialog({
    component: ReaderForm,
    componentProps: { creatingFromLoan: true, isEditForm: false },
  }).onOk(async (payload) => {
    await fetchEntries({ cedula: payload.cedula }, "lectores");
    readerOptions.value = [];
    readerOptions.value.push({
      label: `${payload.reader.cedula} - ${payload.reader.nombre} ${payload.reader.apellido}`,
      value: payload.reader.cedula,
    });
    state.record.cedula = payload.reader.cedula;
  });
};

syncChanges();
const { record } = toRefs(state);
</script>
