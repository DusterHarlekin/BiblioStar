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
          to="/libros/nuevo"
          v-if="authStore.rol === 'admin' || authStore.rol === 'librarian'"
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
                    label="Cota completa"
                  />
                </div>

                <div class="col-auto">
                  <q-input
                    v-model="filter.cod_isbn"
                    outlined
                    dense
                    debounce="400"
                    label="Código ISBN"
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
              <div
                class="row items-center q-gutter-md q-mt-md"
                v-show="filterExpanded"
              >
                <q-input
                  v-model="filter.anio"
                  outlined
                  dense
                  debounce="400"
                  label="Año de publicación"
                />

                <q-item-label class="q-ml-lg">Fecha de ingreso:</q-item-label>
                <q-btn
                  color="primary"
                  flat
                  icon="mdi-calendar"
                  class="cursor-pointer q-pa-sm q-ml-xs"
                  rounded
                >
                  <q-popup-proxy
                    cover
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-date mask="DD/MM/YYYY" range v-model="filter.date">
                      <div class="row items-center justify-end">
                        <q-btn
                          v-close-popup
                          label="Close"
                          color="primary"
                          flat
                        />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-btn>
                <q-item-label caption class="q-ml-xs">
                  <span
                    caption
                    v-if="filter.date && filter.date.from"
                    class="text-weight-medium"
                    >Desde
                    {{
                      filter.date.from && filter.date.to
                        ? filter.date.from + " Hasta " + filter.date.to
                        : filter.date.from
                    }}
                  </span>

                  <span
                    caption
                    v-else-if="filter.date && !filter.date.from"
                    class="text-weight-medium"
                    >{{ filter.date }}</span
                  >

                  <span caption v-else class="text-weight-medium">Todas</span>
                </q-item-label>
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
          <q-btn
            flat
            round
            icon="mdi-eye"
            color="positive"
            :to="`/libros/libro/${props.row.N}`"
          >
            <q-tooltip>Ver</q-tooltip>
          </q-btn>
          <q-btn
            v-if="authStore.rol === 'admin' || authStore.rol === 'librarian'"
            flat
            round
            icon="mdi-lead-pencil"
            color="accent"
            :to="`/libros/editar/${props.row.N}`"
          >
            <q-tooltip>Editar</q-tooltip>
          </q-btn>
          <q-btn
            v-if="authStore.rol === 'admin' || authStore.rol === 'librarian'"
            flat
            round
            icon="mdi-delete-variant"
            color="red"
            @click="deleteBook(props.row)"
          >
            <q-tooltip>Eliminar</q-tooltip>
          </q-btn>
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useAuthStore } from "src/stores/auth/auth";
import { useQuasar } from "quasar";

const $q = useQuasar();
const authStore = useAuthStore();

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
  date: "",
  anio: "",
  cod_isbn: "",
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
    label: "Cota completa",
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

const deleteBook = (book) => {
  $q.dialog({
    title: "Eliminar libro",
    message: `¿Estás seguro de que deseas eliminar este libro? (${book.titulo})`,
    cancel: true,
    persistent: true,
  }).onOk(async () => {
    try {
      const requestOptions = {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          N: book.N,
          session_user_name: localStorage.getItem("usuario"),
          session_user_role: localStorage.getItem("rol"),
        }),
      };

      // API URL
      const url = process.env.API_URL + `libros.php`;

      const response = await fetch(url, requestOptions);

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
        message: "Libro eliminado correctamente",
        icon: "mdi-check",
      });

      fetchLibros();
    } catch (error) {
      $q.notify({
        color: "negative",
        position: "top",
        message: error.message,
        icon: "mdi-alert",
      });
    }
  });
};

const fetchLibros = async (page = 1) => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };
    // API URL
    let url =
      process.env.API_URL +
      `libros.php?page=${page}&session_user_name=${localStorage.getItem(
        "usuario"
      )}&session_user_role=${localStorage.getItem("rol")}`;

    let params = new URLSearchParams(filter);

    //CONTROL DE FECHA
    if (filter.date) {
      params.set("dateQuery", "fecha_ing");
      params.delete("titulo");
      params.delete("cod_isbn");
      params.delete("autor");
      params.delete("editorial");
      params.delete("cota_completa");
      params.delete("cod_sala");
      params.delete("anio");
      params.set("titulo", filter.titulo);
      params.set("cod_isbn", filter.cod_isbn);
      params.set("autor", filter.autor);
      params.set("editorial", filter.editorial);
      params.set("cota_completa", filter.cota_completa);
      params.set("cod_sala", filter.cod_sala);
      params.set("anio", filter.anio);
    } else {
      params.delete("date");
    }

    if (filter.date && filter.date.from && filter.date.to) {
      params.set("date_from", filter.date.from);
      params.set("date_to", filter.date.to);
      params.delete("date");
    }

    let keysForDel = [];
    params.forEach((value, key) => {
      if (value == null || value.trim() == "") {
        keysForDel.push(key);
      }
    });

    //ELIMINAR KEYS VACIOS
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
      process.env.API_URL +
        `salas.php?session_user_name=${localStorage.getItem(
          "usuario"
        )}&session_user_role=${localStorage.getItem("rol")}`,
      requestOptions
    );
    if (!response.ok) {
      throw new Error(response.statusText);
    }
    const data = await response.json();
    salas.value = [];
    salas.value.push({ label: "Todas", value: "" });
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

const clearFilters = () => {
  filter.titulo = "";
  filter.autor = "";
  filter.editorial = "";
  filter.cutter = "";
  filter.cod_sala = "";
  filter.anio = "";
  filter.date = null;
};

fetchSalas();
fetchLibros();
</script>
