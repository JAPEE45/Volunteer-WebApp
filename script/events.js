document.addEventListener("DOMContentLoaded", () => {
  const addEventBtn = document.getElementById("addEventBtn");
  const addEventModal = document.getElementById("addEventModal");
  const closeAddModal = document.getElementById("closeAddModal");
  const eventForm = document.getElementById("eventForm");

  const viewEventModal = document.getElementById("viewEventModal");
  const closeViewModal = document.getElementById("closeViewModal");
  const okBtn = document.getElementById("okBtn");

  const tableBody = document.querySelector("#eventTable tbody");

  // ---------- SIDE NAV TOGGLE ----------

  const toggleBtn = document.getElementById("menu-toggle");
  const sidebar = document.getElementById("sidebar");

  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
    
  });

  
  function loadEvents() {
    tableBody.innerHTML = "";
    let events = JSON.parse(localStorage.getItem("events")) || [];

    events.forEach((event, index) => {
      let row = document.createElement("tr");
      row.innerHTML = `
        <td class="event-name">${event.name}</td>
        <td>${event.location}</td>
        <td><button class="delete-btn" data-index="${index}">🗑</button></td>
      `;

      row.querySelector(".event-name").addEventListener("click", () => viewEvent(event));

      row.querySelector(".delete-btn").addEventListener("click", (e) => {
        e.stopPropagation(); 
        deleteEvent(index);
      });

      tableBody.appendChild(row);
    });
  }

  function deleteEvent(index) {
    let events = JSON.parse(localStorage.getItem("events")) || [];
    if (confirm("Are you sure you want to delete this event?")) {
      events.splice(index, 1);
      localStorage.setItem("events", JSON.stringify(events));
      loadEvents();
    }
  }

  addEventBtn.addEventListener("click", () => {
    addEventModal.classList.remove("hidden");
  });

  closeAddModal.addEventListener("click", () => {
    addEventModal.classList.add("hidden");
  });

  eventForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const newEvent = {
      name: document.getElementById("eventName").value,
      location: document.getElementById("eventLocation").value,
      dateTime: document.getElementById("eventDateTime").value
    };

    let events = JSON.parse(localStorage.getItem("events")) || [];
    events.push(newEvent);
    localStorage.setItem("events", JSON.stringify(events));

    eventForm.reset();
    addEventModal.classList.add("hidden");
    loadEvents();
  });

  function viewEvent(event) {
    document.getElementById("viewName").textContent = event.name;
    document.getElementById("viewLocation").textContent = event.location;
    document.getElementById("viewDateTime").textContent = new Date(event.dateTime).toLocaleString();
    viewEventModal.classList.remove("hidden");
  }

  closeViewModal.addEventListener("click", () => {
    viewEventModal.classList.add("hidden");
  });
  okBtn.addEventListener("click", () => {
    viewEventModal.classList.add("hidden");
  });

  loadEvents();
});
