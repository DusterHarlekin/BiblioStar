<template>
  <q-page class="q-py-md q-px-lg">
    <div
      class="row justify-between items-center q-gutter-x-xl q-gutter-y-md q-mb-md q-pt-sm"
    >
      <div class="col">
        <div class="row q-gutter-sm items-center q-mb-sm">
          <q-icon name="mdi-printer" size="lg" color="primary" />
          <div class="text-h4 text-weight-medium font-title">Reportes</div>
        </div>
      </div>
    </div>

    <div class="row q-mb-lg content-end items-center q-col-gutter-xl">
      <div class="col-12 col-md-6 col-lg-4">
        <q-card class="col-12">
          <q-card-section class="bg-primary row items-center text-white">
            <q-icon
              name="mdi-invoice-text-clock-outline"
              size="md"
              color="white"
              class="q-mr-sm"
            />
            <div class="text-h6">Préstamos activos</div>
          </q-card-section>
          <q-card-section>
            <p>
              Reporte sobre los prestamos activos. Incluyen su fecha de salida y
              entrega
            </p>
          </q-card-section>
          <q-card-actions align="right">
            <q-btn
              label="Generar"
              color="secondary"
              text-color="white"
              to="/reportes/prestamos"
            />
          </q-card-actions>
        </q-card>
      </div>
    </div>
  </q-page>
</template>
