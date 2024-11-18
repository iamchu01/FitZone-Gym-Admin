// Reset form fields when modal is closed
document.getElementById("add_member").addEventListener("hidden.bs.modal", function () {
  // Reset the form fields
  document.getElementById("addMemberForm").reset();

  // Clear the age field
  document.getElementById("memberAge").value = "";
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
        $("#memberDateWarning").text("Please select a valid date of birth.").show();
        $(this).data("DateTimePicker").clear();
        $("#memberAge").val("");
        return;
      } else if (selectedDate > today || selectedDate < minDate) {
        $("#memberDateWarning").text("Please select a valid date of birth.").show();
        $(this).data("DateTimePicker").clear();
        $("#memberAge").val("");
        return;
      } else {
        $("#memberDateWarning").hide();
      }

      const age = calculateAge(e.date.format("YYYY-MM-DD"));
      $("#memberAge").val(age);
    } else {
      $("#memberAge").val("");
    }
  });
});

// Phone number validation
const memberMobileInput = document.getElementById("memberMobile");
const memberMobileWarning = document.getElementById("memberMobileWarning");

memberMobileInput.addEventListener("input", () => {
  const philippineNumberPattern = /^9\d{9}$/;

  if (!philippineNumberPattern.test(memberMobileInput.value)) {
    memberMobileInput.classList.add("is-invalid");
    memberMobileWarning.style.display = "block";
  } else {
    memberMobileInput.classList.remove("is-invalid");
    memberMobileWarning.style.display = "none";
  }
});

// Ensure text values are captured from the dropdowns and stored in hidden inputs
document.getElementById("memberRegion").addEventListener("change", function () {
  document.getElementById("memberRegionText").value = this.options[this.selectedIndex].text;
});

document.getElementById("memberProvince").addEventListener("change", function () {
  document.getElementById("memberProvinceText").value = this.options[this.selectedIndex].text;
});

document.getElementById("memberCity").addEventListener("change", function () {
  document.getElementById("memberCityText").value = this.options[this.selectedIndex].text;
});

document.getElementById("memberBarangay").addEventListener("change", function () {
  document.getElementById("memberBarangayText").value = this.options[this.selectedIndex].text;
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
        : "An error occurred while saving the member. Please try again.";

    document.querySelector("#errorModal .modal-body").textContent = errorMessage;
    const errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
    errorModal.show();
  }
});

function updateStatus(memberId, newStatus) {
  // Create an XMLHttpRequest object
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "member-update-status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // Send the request with data
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Check if the response indicates success
      if (xhr.responseText.trim() === "Status updated successfully") {
        // Update the UI
        document.querySelector(`#status-text-${memberId}`).textContent = newStatus;
        document.querySelector(`#status-${memberId}`).className = `fa fa-dot-circle-o ${
          newStatus === "Active" ? "text-success" : "text-danger"
        }`;
      } else {
        alert("Failed to update status. Please try again.");
      }
    }
  };

  xhr.send("id=" + memberId + "&status=" + newStatus);
}

// Password Toggle


// Reset form fields when modal is closed
document.getElementById("add_member").addEventListener("hidden.bs.modal", function () {
  document.getElementById("addMemberForm").reset();
  document.getElementById("memberAge").value = "";
});

// Live search for members
document.getElementById("searchMemberInput").addEventListener("input", function () {
  const searchValue = this.value;
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "search-members.php?search=" + encodeURIComponent(searchValue), true);
  xhr.onload = function () {
    if (this.status === 200) {
      document.getElementById("membersTable").innerHTML = this.responseText;
    }
  };
  xhr.send();
});
