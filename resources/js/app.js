// Basic JavaScript functionality
document.addEventListener("DOMContentLoaded", () => {
  console.log("MediCare application loaded")

  // Initialize any interactive elements
  const dropdowns = document.querySelectorAll(".dropdown-toggle")
  if (dropdowns) {
    dropdowns.forEach((dropdown) => {
      dropdown.addEventListener("click", function (e) {
        e.preventDefault()
        const menu = this.nextElementSibling
        if (menu) {
          menu.classList.toggle("show")
        }
      })
    })
  }

  // Close dropdowns when clicking outside
  document.addEventListener("click", (e) => {
    const dropdowns = document.querySelectorAll(".dropdown-menu.show")
    dropdowns.forEach((dropdown) => {
      if (!dropdown.previousElementSibling.contains(e.target)) {
        dropdown.classList.remove("show")
      }
    })
  })

  // Time slot selection for appointments
  const timeSlots = document.querySelectorAll(".time-slot")
  if (timeSlots) {
    timeSlots.forEach((slot) => {
      slot.addEventListener("click", function () {
        timeSlots.forEach((s) => s.classList.remove("selected"))
        this.classList.add("selected")
        document.getElementById("time_slot").value = this.dataset.time
      })
    })
  }
})

