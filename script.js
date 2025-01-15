document.addEventListener("DOMContentLoaded", () => {
    const calendar = document.getElementById("calendar");
    const eventForm = document.getElementById("event-form");
    const eventDate = document.getElementById("event-date");
    const eventDescription = document.getElementById("event-description");
  
    // Generate the calendar
    function generateCalendar() {
      const daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();
  
      calendar.innerHTML = "";
      for (let i = 1; i <= daysInMonth; i++) {
        const dayCell = document.createElement("div");
        dayCell.textContent = i;
        dayCell.dataset.date = `${new Date().getFullYear()}-${String(new Date().getMonth() + 1).padStart(2, "0")}-${String(i).padStart(2, "0")}`;
        dayCell.addEventListener("click", () => alert(`Selected Date: ${dayCell.dataset.date}`));
        calendar.appendChild(dayCell);
      }
    }
  
    // Add event to a selected date
    eventForm.addEventListener("submit", (e) => {
      e.preventDefault();
  
      const selectedDate = eventDate.value;
      const description = eventDescription.value;
  
      if (!selectedDate || !description) return;
  
      alert(`Event added for ${selectedDate}: ${description}`);
      eventDate.value = "";
      eventDescription.value = "";
    });
  
    generateCalendar();
  });
  