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

  //--------- AUTO RECALL CHECK ----------
  // Check for ended events and recall volunteers on page load
  async function checkAndRecallVolunteers() {
    try {
      const res = await fetch('./utility/recallVolunteers.php');
      const result = await res.json();
      if (result.recalled_count > 0) {
        console.log(`Auto-recalled ${result.recalled_count} volunteers from completed events`);
      }
    } catch (error) {
      console.error('Error checking volunteer recalls:', error);
    }
  }
  
  // Run recall check on page load
  checkAndRecallVolunteers();

  //--------- LOAD EVENTS ----------
  async function loadEvents() {
    tableBody.innerHTML = "";
    const ev = await fetch("./utility/getEvent.php");
    const events = await ev.json();
    console.log(events)
    events.forEach((event, index) => {
      // Determine status badge color
      let statusBadge = '';
      const status = event.status || 'upcoming';
      if (status === 'active') {
        statusBadge = '<span style="background: #10b981; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; margin-left: 8px;">Active</span>';
      } else if (status === 'completed') {
        statusBadge = '<span style="background: #6b7280; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; margin-left: 8px;">Completed</span>';
      } else {
        statusBadge = '<span style="background: #f59e0b; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; margin-left: 8px;">Upcoming</span>';
      }
      
      let row = document.createElement("tr");
      row.innerHTML = `
        <td class="event-name">${event.eventName}${statusBadge}</td>
        <td>${event.location}</td>
        <td><button class="delete-btn" data-index="${event.id}">🗑</button></td>
      `;

      row.querySelector(".event-name").addEventListener("click", () => viewEvent(event));

      row.querySelector(".delete-btn").addEventListener("click", (e) => {
        e.stopPropagation(); 
        deleteEvent(event.id);
      });

      tableBody.appendChild(row);
    });
  }

  //--------- DELETE EVENT ----------
  async function deleteEvent(index) {
    if (confirm("Are you sure you want to delete this event?")) {
      const res = await fetch(`./utility/deleteEvent.php?eventId=${index}`)
      const j = await res.json();
      console.log(j)
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
      longitude: document.getElementById("longitude").value,
      duration: parseInt(document.getElementById("eventDuration").value) || 8
    };
    const a = await fetch("./utility/addEvent.php",{
      method:"POST",
      headers:{'Content-Type':'application/json'},
      body:JSON.stringify(newEvent)
    }) 
    const j = await a.json();
    console.log(j)
    
    if (j.success) {
      alert(`Event created successfully!\nEnd Date: ${new Date(j.end_date).toLocaleString()}`);
    }

    eventForm.reset();
    document.getElementById("eventDuration").value = "8"; // Reset to default
    addEventModal.classList.add("hidden");
    loadEvents();
  });

  //--------- VIEW EVENT ----------
  function viewEvent(event) {
    document.getElementById("viewName").textContent = event.eventName;
    document.getElementById("viewLocation").textContent = event.location;
    document.getElementById("viewDateTime").textContent = new Date(event.date).toLocaleString();
    
    // Display duration
    const duration = event.duration || 8;
    document.getElementById("viewDuration").textContent = duration;
    
    // Display end date
    const endDate = event.end_date ? new Date(event.end_date).toLocaleString() : 'Not calculated';
    document.getElementById("viewEndDate").textContent = endDate;
    
    // Display status with color
    const status = event.status || 'upcoming';
    const statusEl = document.getElementById("viewStatus");
    statusEl.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    statusEl.style.fontWeight = 'bold';
    if (status === 'active') {
      statusEl.style.color = '#10b981';
    } else if (status === 'completed') {
      statusEl.style.color = '#6b7280';
    } else {
      statusEl.style.color = '#f59e0b';
    }
    
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
