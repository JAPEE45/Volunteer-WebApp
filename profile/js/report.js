document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("reportForm");
  const fileInput = document.getElementById("reportFile");
  const reportList = document.getElementById("reportList");
  const statusBox = document.getElementById("reportStatusBox");
  function loadReports() {
    reportList.innerHTML = "";
    const reports = JSON.parse(localStorage.getItem("uploadedReports")) || [];

    if (reports.length === 0) {
      statusBox.style.display = "none";
      return;
    }

    statusBox.style.display = "block";

    reports.forEach(report => {
      const li = document.createElement("li");
      li.innerHTML = `
        <span>📄 ${report.name} <small>(${report.time})</small></span>
        <span class="status">${report.status}</span>
      `;
      reportList.appendChild(li);
    });
  }

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const files = Array.from(fileInput.files);
    if (files.length === 0) {
      alert("⚠ Please select at least one file.");
      return;
    }

    let reports = JSON.parse(localStorage.getItem("uploadedReports")) || [];
    const timeNow = new Date().toLocaleString();

    files.forEach(file => {
      reports.push({
        name: file.name,
        status: "Sent",
        time: timeNow
      });
    });

    localStorage.setItem("uploadedReports", JSON.stringify(reports));

    alert(`✅ Successfully uploaded ${files.length} report(s)!`);

    form.reset();
    loadReports();
  });

  window.addEventListener("storage", loadReports);
  loadReports();
});
