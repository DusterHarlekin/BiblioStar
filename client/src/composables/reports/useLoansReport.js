import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable";

export default async function useLoansReport(loans, filters, dateType) {
  const doc = new jsPDF({
    format: "a4",
    orientation: "landscape",
    unit: "mm",
  });

  const centeredX = doc.internal.pageSize.width / 2;

  console.log(filters.date);
  console.log(dateType);

  // BODY
  doc.setFontSize(15);
  doc.text("Reporte de préstamos", centeredX, 42, { align: "center" });
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
    startY: 150, // Vertical position to start the table (in mm)
  };
  autoTable(doc, {
    head: [columns],
    margin: { top: 40 },
    body: rows,
    ...options,
  });

  await buildHeader(doc);
  console.log(loans);

  // FOOTER

  doc.output("dataurlnewwindow", { filename: "two-by-four.pdf" });
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
    console.log(data[0]);
    return data[0];
  } catch (error) {
    console.log(error);
  }
};

const buildHeader = async (doc) => {
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
      10,
      pageHeight - 10,
      "Página " + String(pageCount - i) + " de " + String(pageCount)
    );
  }
};
