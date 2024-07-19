import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable";

export default async function useLoansReport(loans, filters, dateType) {
  const doc = new jsPDF({
    format: "a4",
    orientation: "landscape",
    unit: "mm",
  });

  const centeredX = doc.internal.pageSize.width / 2;

  // BODY
  doc.setFontSize(15);
  doc.text("Reporte de préstamos", centeredX, 42, { align: "center" });
  doc.setFontSize(8);
  doc.text(`Cédula: ${filters.cedula}`, 10, 52, {
    align: "left",
    maxWidth: 85,
  });
  doc.text(`Código ISBN: ${filters.cod_isbn}`, 97, 52, {
    align: "left",
    maxWidth: 85,
  });
  doc.text(`Título: ${filters.titulo}`, 184, 52, {
    align: "left",
    maxWidth: 120,
  });

  // Define the table columns and rows
  const columns = [
    "Cédula",
    "Código ISBN",
    "Fecha de salida",
    "Fecha de entrega",
  ];
  const rows = [];
  for (const loan of loans) {
    rows.push([loan.cedula, loan.cod_isbn, loan.fecha_s, loan.fecha_e]);
  }

  const options = {
    startY: 60, // Vertical position to start the table (in mm)
  };
  autoTable(doc, {
    head: [columns],
    margin: { top: 40 },
    body: rows,
    ...options,
  });

  await buildHeader(doc, filters, dateType);

  // FOOTER
  let today = new Date().toLocaleString().replace("/", "-");

  doc.save(`reporte-prestamos${today}.pdf`);
}

const requestConfig = async () => {
  try {
    const requestOptions = {
      method: "GET",
      headers: { "Content-Type": "application/json" },
    };

    // API URL

    const url =
      process.env.API_URL +
      `config.php?session_user_name=${localStorage.getItem(
        "usuario"
      )}&session_user_role=${localStorage.getItem("rol")}`;
    // REQUEST

    const response = await fetch(url, requestOptions);
    if (!response.ok) {
      throw new Error(response.statusText);
    }
    const data = await response.json();
    return data[0];
  } catch (error) {
    console.log(error);
  }
};

const buildHeader = async (doc, filters, dateType) => {
  let pageCount = doc.getNumberOfPages();

  const pageHeight = doc.internal.pageSize.height;

  // Before adding new content

  doc.setFontSize(10);
  const logo = new Image();
  logo.src = "/src/assets/logo.png";

  for (let i = 0; i < pageCount; i++) {
    doc.setPage(i);
    doc.addImage(logo, "png", 10, 7, 30, 30);
    doc.text("RIF: ", 40, 23);
    doc.text("Dirección: ", 40, 28);

    const libInfo = await requestConfig();
    doc.text(libInfo.nombre_organizacion, 40, 18);
    doc.text(libInfo.RIF, 50, 23);
    doc.text(libInfo.direccion, 58, 28);
    doc.setFontSize(10);
    doc.text(
      `Fecha de reporte: ${new Date().toLocaleString()}`,
      doc.internal.pageSize.width - 10,
      18,
      { align: "right" }
    );

    if (dateType === "fecha_s") {
      if (filters.date.from && filters.date.to) {
        doc.text(
          `Fecha de salida (desde): ${filters.date.from}`,
          doc.internal.pageSize.width - 10,
          23,
          { align: "right" }
        );
        doc.text(
          `Fecha de salida (hasta): ${filters.date.to}`,
          doc.internal.pageSize.width - 10,
          28,
          { align: "right" }
        );
      } else {
        doc.text(
          `Fecha de salida: ${filters.date}`,
          doc.internal.pageSize.width - 10,
          23,
          { align: "right" }
        );
      }
    } else if (dateType === "fecha_e") {
      if (filters.date.from && filters.date.to) {
        doc.text(
          `Fecha de entrega (desde): ${filters.date.from}`,
          doc.internal.pageSize.width - 10,
          23,
          { align: "right" }
        );
        doc.text(
          `Fecha de entrega (hasta): ${filters.date.to}`,
          doc.internal.pageSize.width - 10,
          28,
          { align: "right" }
        );
      } else {
        doc.text(
          `Fecha de entrega: ${filters.date}`,
          doc.internal.pageSize.width - 10,
          23,
          { align: "right" }
        );
      }
    }

    doc.setFontSize(10);
    doc.text(
      10,
      pageHeight - 10,
      "Página " + String(pageCount - i) + " de " + String(pageCount)
    );
    doc.setFontSize(8);
    doc.text(
      "Reporte generado por BiblioStar",
      doc.internal.pageSize.width / 2,
      pageHeight - 10,
      { align: "center" }
    );
  }
};
