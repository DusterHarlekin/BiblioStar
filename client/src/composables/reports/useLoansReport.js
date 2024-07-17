import { jsPDF } from "jspdf";

export default function useLoansReport() {
  const doc = new jsPDF({
    orientation: "landscape",
    format: "a4",
  });

  doc.text("Hello world!", 1, 10);
  doc.output("dataurlnewwindow", { filename: "two-by-four.pdf" });
}
