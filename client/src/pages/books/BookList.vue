<template>
  <q-page class="q-py-md q-px-lg">
    <div
      class="row justify-between items-center q-gutter-x-xl q-gutter-y-md q-mb-md q-pt-sm"
    >
      <div class="col-md-6">
        <div class="row q-gutter-sm items-center">
          <q-icon name="mdi-book" size="lg" color="primary" />
          <div class="text-h4 text-weight-medium font-title">Libros</div>
        </div>
      </div>
      <div class="col-auto">
        <q-btn
          label="Nuevo libro"
          icon="mdi-plus-circle"
          color="secondary"
          text-color="white"
          @click="nuevoLibro()"
        />
      </div>
    </div>

    <!--FILTROS-->
    <q-card class="q-pa-md q-mb-lg">
      <div class="row justify-between content-end items-center q-col-gutter-sm">
        <div class="col-auto">
          <div class="row items-center">
            <div class="col-xs-12 col-lg-auto">
              <div class="row items-center q-gutter-md">
                <q-btn
                  @click="filterExpanded = !filterExpanded"
                  size="lg"
                  :icon="
                    filterExpanded
                      ? 'mdi-filter-variant-minus'
                      : 'mdi-filter-variant-plus'
                  "
                  flat
                  class="text-weight-semibold"
                  :color="filterExpanded ? 'grey-8' : 'primary'"
                />
                <div class="col-auto">
                  <q-input
                    v-model="filter.titulo"
                    outlined
                    dense
                    debounce="400"
                    label="Título"
                  />
                </div>
                <div class="col-auto">
                  <q-input
                    v-model="filter.autor"
                    outlined
                    dense
                    debounce="400"
                    label="Autor"
                  />
                </div>

                <div class="col-auto">
                  <q-input
                    v-model="filter.editorial"
                    outlined
                    dense
                    debounce="400"
                    label="Editorial"
                  />
                </div>

                <div class="col-auto">
                  <q-input
                    v-model="filter.cota_completa"
                    outlined
                    dense
                    debounce="400"
                    label="Cota"
                  />
                </div>

                <div class="col-auto">
                  <q-select
                    v-model="filter.cod_sala"
                    style="min-width: 140px"
                    map-options
                    dense
                    outlined
                    emit-value
                    :options="salas"
                    label="Sala"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-auto self-end">
          <q-btn
            flat
            size="lg"
            no-caps
            icon="mdi-eraser"
            color="negative"
            @click="clearFilters"
          />

          <q-btn
            flat
            size="lg"
            no-caps
            icon="mdi-reload"
            color="primary"
            class="q-ml-sm"
            @click="fetchLibros()"
          >
            <q-tooltip> Actualizar tabla </q-tooltip>
          </q-btn>
        </div>
      </div>
    </q-card>

    <q-table
      flat
      :dense="$q.screen.lt.lg"
      bordered
      v-model:pagination="pagination"
      :rows="libros"
      :columns="columns"
      :filter="filter"
      :loading="isloading"
      row-key="N"
      :rows-per-page-options="[]"
      @request="handleRequest"
    >
      <template #body-cell-actions="props">
        <q-td :props="props">
          <q-btn flat round icon="mdi-eye" color="positive">
            <q-tooltip>Ver</q-tooltip>
          </q-btn>
          <q-btn flat round icon="mdi-lead-pencil" color="accent">
            <q-tooltip>Editar</q-tooltip>
          </q-btn>
          <q-btn flat round icon="mdi-delete-variant" color="red">
            <q-tooltip>Eliminar</q-tooltip>
          </q-btn>
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useQuasar } from "quasar";

const $q = useQuasar();

const $router = useRouter();
//VALORES INICIALES
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
});

const filter = reactive({
  titulo: "",
  autor: "",
  editorial: "",
  cota_completa: "",
  cod_sala: "",
});

const salas = ref([]);
const libros = ref([]);
const filterExpanded = ref(false);
const isloading = ref(false);

// Q-Table columns
const columns = [
  {
    name: "N",
    label: "Número",
    field: "N",
    align: "left",
  },
  {
    name: "cota_completa",
    label: "Cota",
    field: (row) =>
      row.cota_completa && row.cota_completa.trim() != ""
        ? row.cota_completa
        : "--",
    align: "left",
  },
  {
    name: "titulo",
    label: "Título",
    field: "titulo",
    classes: "text-capitalize",
    align: "left",
  },
  {
    name: "autor",
    label: "Autor",
    field: "autor",
    classes: "text-capitalize",
    align: "left",
  },
  {
    name: "editorial",
    label: "Editorial",
    field: "editorial",
    classes: "text-capitalize",
    align: "left",
  },
  {
    name: "edicion",
    label: "Edición",
    field: "edicion",
    align: "left",
  },
  {
    name: "cod_sala",
    label: "Sala",
    field: "cod_sala",
    align: "left",
  },

  {
    name: "actions",
    label: "Acciones",
    align: "left",
  },
];

const fetchLibros = async (page = 1) => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };
    // API URL
    let url = process.env.API_URL + `libros.php?page=${page}`;

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

    isloading.value = true;
    const response = await fetch(url, requestOptions);
    const data = await response.json();

    libros.value = data.data ? data.data : [];

    //ACTUALIZO VALORES DE PAGINACIÓN
    pagination.value.rowsNumber = data.pagination?.total
      ? data.pagination.total
      : 0;

    pagination.value.page = data.pagination?.currentPage
      ? data.pagination.currentPage
      : 1;
    pagination.value.rowsPerPage = data.pagination?.perPage
      ? data.pagination.perPage
      : 10;
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "mdi-alert",
    });
  } finally {
    isloading.value = false;
  }
};

const handleRequest = (props) => {
  fetchLibros(props.pagination.page);
};

const fetchSalas = async () => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };

    const response = await fetch(
      process.env.API_URL + "salas.php",
      requestOptions
    );
    if (!response.ok) {
      throw new Error(response.statusText);
    }
    const data = await response.json();

    for (const sala of data.data) {
      salas.value.push({ label: sala.cod_sala, value: sala.cod_sala });
    }

    if (data.error) {
      throw new Error(data.error);
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

const nuevoLibro = () => {
  $router.push("/libros/nuevo");
};

const clearFilters = () => {
  filter.titulo = "";
  filter.autor = "";
  filter.editorial = "";
  filter.cutter = "";
  filter.cod_sala = "";
};

fetchSalas();
fetchLibros();
</script>
