document.addEventListener("DOMContentLoaded", () => {
    // Department and doctor selection
    const departmentSelect = document.getElementById("department")
    const doctorSelect = document.getElementById("doctor_id")
  
    if (departmentSelect && doctorSelect) {
      departmentSelect.addEventListener("change", function () {
        const department = this.value
  
        // Clear current options
        doctorSelect.innerHTML = '<option value="">Select Doctor (Optional)</option>'
  
        if (department) {
          // Fetch doctors for the selected department
          fetch(`/contact/doctors/${department}`)
            .then((response) => response.json())
            .then((doctors) => {
              doctors.forEach((doctor) => {
                const option = document.createElement("option")
                option.value = doctor.id
                option.textContent = doctor.name
                doctorSelect.appendChild(option)
              })
            })
            .catch((error) => console.error("Error fetching doctors:", error))
        }
      })
    }
  
    // Date validation
    const dateInput = document.getElementById("appointment_date")
    if (dateInput) {
      // Set min date to today
      const today = new Date()
      const yyyy = today.getFullYear()
      const mm = String(today.getMonth() + 1).padStart(2, "0")
      const dd = String(today.getDate()).padStart(2, "0")
      const formattedDate = `${yyyy}-${mm}-${dd}`
  
      dateInput.setAttribute("min", formattedDate)
    }
  })
  
  