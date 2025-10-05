document.addEventListener("DOMContentLoaded", () => {
  const addEventBtn = document.getElementById("addEventBtn");
  const addEventModal = document.getElementById("addEventModal");
  const closeAddModal = document.getElementById("closeAddModal");
  const eventForm = document.getElementById("eventForm");

  const viewEventModal = document.getElementById("viewEventModal");
  const closeViewModal = document.getElementById("closeViewModal");
  const okBtn = document.getElementById("okBtn");

  const tableBody = document.querySelector("#eventTable tbody");

  //--------- SIDEBAR ----------
  const toggleBtn = document.getElementById("menu-toggle");
  const sidebar = document.getElementById("sidebar");

  toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
  });

  //--------- LOAD EVENTS ----------
  async function loadEvents() {
    tableBody.innerHTML = "";
    const ev = await fetch("./utility/getEvent.php");
    const events = await ev.json();
    console.log(events)
    events.forEach((event, index) => {
      let row = document.createElement("tr");
      row.innerHTML = `
        <td class="event-name">${event.eventName}</td>
        <td>${event.location}</td>
        <td><button class="delete-btn" data-index="${event.id}">🗑</button></td>
      `;

      row.querySelector(".event-name").addEventListener("click", () => viewEvent(event));

      row.querySelector(".delete-btn").addEventListener("click", (e) => {
        e.stopPropagation(); 
        deleteEvent(index);
      });

      tableBody.appendChild(row);
    });
  }

  //--------- DELETE EVENT ----------
  function deleteEvent(index) {
    let events = JSON.parse(localStorage.getItem("events")) || [];
    if (confirm("Are you sure you want to delete this event?")) {
      events.splice(index, 1);
      localStorage.setItem("events", JSON.stringify(events));
      loadEvents();
    }
  }

  //--------- OPEN ADD EVENT MODAL ----------
  addEventBtn.addEventListener("click", () => {
    addEventModal.classList.remove("hidden");
  });

  //--------- CLOSE ADD EVENT MODAL ----------
  closeAddModal.addEventListener("click", () => {
    addEventModal.classList.add("hidden");
  });

  //--------- SAVE NEW EVENT ----------
  eventForm.addEventListener("submit", async(e) => {
    e.preventDefault();

    const newEvent = {
      name: document.getElementById("eventName").value,
      location: document.getElementById("eventLocation").value,
      dateTime: document.getElementById("eventDateTime").value,
      latitude: document.getElementById("latitude").value,
      longitude: document.getElementById("longitude").value
    };
    const a = await fetch("./utility/addEvent.php",{
      method:"POST",
      headers:{'Content-Type':'application/json'},
      body:JSON.stringify(newEvent)
    }) 
    const j = await a.json();
    console.log(j)
    let events = JSON.parse(localStorage.getItem("events")) || [];
    events.push(newEvent);
    localStorage.setItem("events", JSON.stringify(events));

    eventForm.reset();
    addEventModal.classList.add("hidden");
    loadEvents();
  });

  //--------- VIEW EVENT ----------
  function viewEvent(event) {
    document.getElementById("viewName").textContent = event.eventName;
    document.getElementById("viewLocation").textContent = event.location;
    document.getElementById("viewDateTime").textContent = new Date(event.date).toLocaleString();
    viewEventModal.classList.remove("hidden");
  }

  //--------- CLOSE VIEW EVENT MODAL ----------
  closeViewModal.addEventListener("click", () => {
    viewEventModal.classList.add("hidden");
  });
  okBtn.addEventListener("click", () => {
    viewEventModal.classList.add("hidden");
  });

  //--------- INITIAL LOAD ----------
  loadEvents();
});
