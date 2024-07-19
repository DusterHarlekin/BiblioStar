<template>
  <q-dialog
    ref="dialogRef"
    medium
    full-height
    maximized
    position="right"
    @hide="onDialogHide"
  >
    <q-card class="q-pa-md" style="width: 500px; max-width: 100vw">
      <q-card-section>
        <div class="text-h6">{{ isEditForm ? "Editar" : "Nuevo" }} Lector</div>
      </q-card-section>

      <q-form @submit="submitForm">
        <q-card-section>
          <q-input
            :disable="isEditForm"
            v-model="record.cedula"
            label="Cédula"
            class="required"
          />

          <q-input v-model="record.nombre" label="Nombre" class="required" />

          <q-input
            v-model="record.apellido"
            label="Apellido"
            class="required"
          />

          <q-input v-model="record.edad" label="Edad" class="required" />

          <q-input v-model="record.sexo" label="sexo" class="required" />

          <q-input
            v-model="record.telefono"
            label="Teléfono"
            class="required"
          />

          <q-input v-model="record.correo" label="Correo" class="required" />

          <q-input
            v-model="record.direccion"
            label="Dirección"
            class="required"
            type="textarea"
          />
        </q-card-section>

        <q-card-actions>
          <q-btn
            :loading="isLoading"
            type="submit"
            color="primary"
            label="Guardar"
            padding="xs xl"
          />
          <q-btn flat label="Cancelar" @click="onCancelClick" />
        </q-card-actions>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { useDialogPluginComponent, useQuasar } from "quasar";
import { reactive, toRefs } from "vue";

defineEmits([...useDialogPluginComponent.emits]);

const $q = useQuasar();
const { dialogRef, onDialogHide, onDialogOK, onDialogCancel } =
  useDialogPluginComponent();

const props = defineProps({
  isEditForm: {
    type: Boolean,
    default: false,
  },
  creatingFromLoan: {
    type: Boolean,
    default: false,
  },
  cedula: {
    type: String,
    default: null,
  },
});

const state = reactive({
  record: {
    cedula: "",
    nombre: "",
    apellido: "",
    edad: "",
    sexo: "",
    telefono: "",
    correo: "",
    direccion: "",

    session_user_name: localStorage.getItem("usuario"),
    session_user_role: localStorage.getItem("rol"),
  },
});

/******* Sync record for editing ********/

const syncChanges = async () => {
  try {
    if (props.isEditForm) {
      const requestOptions = {
        method: "GET",
        headers: { "Content-Type": "application/json" },
      };

      // API URL
      const url =
        process.env.API_URL +
        `lectores.php?cedula=${
          props.cedula
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
      state.record = data.data[0];

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

/**** Submit form ****/

const submitForm = async () => {
  try {
    const requestOptions = {
      method: props.isEditForm ? "PUT" : "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(state.record),
    };

    const response = await fetch(
      process.env.API_URL + "lectores.php",
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

    onDialogOK({
      reader: state.record,
    });
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: error.message,
      icon: "mdi-alert",
    });
  }
};

const onCancelClick = () => onDialogCancel();

if (props.isEditForm) {
  syncChanges();
}

const { record, isLoading } = toRefs(state);
</script>
