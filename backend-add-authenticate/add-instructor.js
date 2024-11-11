// Reset form fields when modal is closed
document.getElementById("add_instructor").addEventListener("hidden.bs.modal", function () {
  // Reset the form fields
  document.getElementById("addUserForm").reset();

  // Clear the age field
  document.getElementById("age").value = "";

  // Reset specialization dropdown button text
  document.getElementById("specializationDropdownButton").textContent = "Select Specialization";

  // Uncheck all checkboxes in the specialization list
  document.querySelectorAll('#specialization-list input[type="checkbox"]').forEach((checkbox) => {
    checkbox.checked = false;
  });

  // Hide the "Create New" input container if it was open
  document.getElementById("addNewInputContainer").style.display = "none";
  document.getElementById("add-new-specialization").style.display = "block";
});

// Initialize datepicker and handle age calculation
$(document).ready(function () {
  // Initialize datepicker with minDate and maxDate
  $(".datetimepicker").datetimepicker({
    format: "YYYY-MM-DD",
    maxDate: new Date(), // Restrict future dates
    minDate: "1924-01-01", // Restrict dates before 1924
  });

  function calculateAge(birthdate) {
    const birthDate = new Date(birthdate);
    const today = new Date();

    let age = today.getFullYear() - birthDate.getFullYear();

    // Adjust if birthdate hasn't occurred this year yet
    if (
      today.getMonth() < birthDate.getMonth() ||
      (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())
    ) {
      age--;
    }

    return `${age} year${age > 1 ? "s" : ""} old`;
  }

  $(".datetimepicker").on("dp.change", function (e) {
    if (e.date) {
      const selectedDate = e.date.toDate();
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      const minDate = new Date("1924-01-01");

      if (selectedDate.getFullYear() === today.getFullYear()) {
        $("#dateWarning").text("Please select a valid date of birth.").show();
        $(this).data("DateTimePicker").clear();
        $("#age").val("");
        return;
      } else if (selectedDate > today || selectedDate < minDate) {
        $("#dateWarning").text("Please select a valid date of birth.").show();
        $(this).data("DateTimePicker").clear();
        $("#age").val("");
        return;
      } else {
        $("#dateWarning").hide();
      }

      const age = calculateAge(e.date.format("YYYY-MM-DD"));
      $("#age").val(age);
    } else {
      $("#age").val("");
    }
  });
});

// Phone number validation
const mobileInput = document.getElementById("mobile");
const mobileWarning = document.getElementById("mobileWarning");

mobileInput.addEventListener("input", () => {
  const philippineNumberPattern = /^9\d{9}$/;

  if (!philippineNumberPattern.test(mobileInput.value)) {
    mobileInput.classList.add("is-invalid");
    mobileWarning.style.display = "block";
  } else {
    mobileInput.classList.remove("is-invalid");
    mobileWarning.style.display = "none";
  }
});

// Ensure text values are captured from the dropdowns and stored in hidden inputs
document.getElementById("region").addEventListener("change", function () {
  document.getElementById("region-text").value = this.options[this.selectedIndex].text;
});

document.getElementById("province").addEventListener("change", function () {
  document.getElementById("province-text").value = this.options[this.selectedIndex].text;
});

document.getElementById("city").addEventListener("change", function () {
  document.getElementById("city-text").value = this.options[this.selectedIndex].text;
});

document.getElementById("barangay").addEventListener("change", function () {
  document.getElementById("barangay-text").value = this.options[this.selectedIndex].text;
});

document.addEventListener("DOMContentLoaded", function () {
  const params = new URLSearchParams(window.location.search);

  if (params.has("success") && params.get("success") === "added") {
    const successModal = new bootstrap.Modal(document.getElementById("successModal"));
    successModal.show();
  } else if (params.has("error")) {
    const errorMessage =
      params.get("error") === "empty_fields"
        ? "Please fill in all required fields."
        : "An error occurred while saving the instructor. Please try again.";

    document.querySelector("#errorModal .modal-body").textContent = errorMessage;
    const errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
    errorModal.show();
  }
});

function updateStatus(instructorId, newStatus) {
  // Create an XMLHttpRequest object
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "instructor-update-status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // Send the request with data
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Check if the response indicates success
      if (xhr.responseText.trim() === "Status updated successfully") {
        // Update the UI
        document.querySelector(`#status-text-${instructorId}`).textContent = newStatus;
        document.querySelector(`#status-${instructorId}`).className = `fa fa-dot-circle-o ${
          newStatus === "Active" ? "text-success" : "text-danger"
        }`;
      } else {
        alert("Failed to update status. Please try again.");
      }
    }
  };

  xhr.send("id=" + instructorId + "&status=" + newStatus);
}
