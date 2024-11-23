document.addEventListener("DOMContentLoaded", function () {
    // Auto-hide success alert
    const successAlert = document.getElementById("successAlert");
    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.remove("show");
            successAlert.classList.add("fade");
            setTimeout(() => {
                successAlert.remove();
            }, 300);
        }, 5000);
    }
  
    // Confirm Archive Modal
    const archiveModal = document.getElementById("archive_member");
    if (archiveModal) {
        $('#archive_member').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const memberId = button.data("id");
            $("#memberIdToArchive").val(memberId);
        });
    }
  
    // Confirm Archive Functionality
    window.confirmArchiveMember = function () {
        const memberId = document.getElementById("memberIdToArchive").value;
  
        if (memberId) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "backend-add-authenticate/archive-member.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = xhr.responseText.trim();
                    if (response === "success") {
                        $("#archive_member").modal("hide");
                        setTimeout(() => {
                            $("#archiveSuccessModal").modal("show");
                        }, 500);
  
                        $("#archiveSuccessModal").on("hidden.bs.modal", function () {
                            location.reload();
                        });
                    } else {
                        console.error("Error archiving member:", response);
                    }
                }
            };
  
            xhr.send(`id=${memberId}`);
        }
    };
  
    // Update Status
    window.updateStatus = function (memberId, newStatus) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "backend-add-authenticate/member-update-status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText.trim() === "success") {
                    document.querySelector(`#status-text-${memberId}`).textContent = newStatus;
                    document.querySelector(`#status-${memberId}`).className =
                        `fa fa-dot-circle-o ${newStatus === "Active" ? "text-success" : "text-danger"}`;
  
                    const archiveLink = document.querySelector(`#archive-link-${memberId}`);
                    if (newStatus === "Inactive") {
                        archiveLink.classList.remove("disabled");
                        archiveLink.title = "";
                    } else {
                        archiveLink.classList.add("disabled");
                        archiveLink.title = "Only inactive members can be archived";
                    }
                } else {
                    console.error("Failed to update status.");
                }
            }
        };
  
        xhr.send(`id=${memberId}&status=${newStatus}`);
    };
  
    // Toggle Password Visibility
    const togglePassword = document.getElementById("toggleMemberPassword");
    if (togglePassword) {
        togglePassword.addEventListener("click", function () {
            const passwordInput = document.getElementById("memberPassword");
            const passwordIcon = document.getElementById("passwordIcon");
  
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            }
        });
    }
  
    // Toggle Additional Info Section
    window.toggleAdditionalInfoSection = function () {
        const additionalInfoSection = document.getElementById("additionalInfoSection");
        if (additionalInfoSection.style.maxHeight === "0px" || !additionalInfoSection.style.maxHeight) {
            additionalInfoSection.style.maxHeight = additionalInfoSection.scrollHeight + "px";
            additionalInfoSection.scrollIntoView({ behavior: "smooth" });
        } else {
            additionalInfoSection.style.maxHeight = "0px";
        }
    };
  
    // Send OTP
    const sendOtpBtn = document.querySelector("#sendOtpBtn");
    if (sendOtpBtn) {
        sendOtpBtn.addEventListener("click", function () {
            const emailInput = document.querySelector("#memberEmail");
            const email = emailInput.value.trim();
  
            if (!email) {
                showAlert("danger", "Please enter an email.");
                return;
            }
  
            fetch("backend-add-authenticate/check-email.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ email }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.exists) {
                        showAlert("danger", data.message);
                    } else {
                        sendOtp(email);
                    }
                })
                .catch(() => showAlert("danger", "An error occurred while checking the email."));
        });
    }
  
    function sendOtp(email) {
        fetch("PHPMailer-OTP/otp_backend.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ email, action: "send" }),
        })
            .then((response) => response.json())
            .then((data) => showAlert(data.status === "success" ? "success" : "danger", data.message))
            .catch(() => showAlert("danger", "An error occurred while sending OTP."));
    }
  
    function showAlert(type, message) {
        const alertContainer = document.getElementById("alert-container");
        alertContainer.innerHTML = `
            <div id="dynamicAlert" class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        setTimeout(() => {
            const dynamicAlert = document.getElementById("dynamicAlert");
            if (dynamicAlert) {
                dynamicAlert.classList.remove("show");
                dynamicAlert.classList.add("fade");
                setTimeout(() => {
                    dynamicAlert.remove();
                }, 300);
            }
        }, 5000);
    }
  
    // Verify OTP
    const verifyOtpBtn = document.querySelector("#verifyOtpBtn");
    if (verifyOtpBtn) {
        verifyOtpBtn.addEventListener("click", function () {
            const email = document.querySelector("#memberEmail").value.trim();
            const otp = document.querySelector("#otp").value.trim();
  
            if (!email || !otp) {
                showAlert("danger", "Please fill in both email and OTP fields.");
                return;
            }
  
            fetch("PHPMailer-OTP/otp_backend.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ email, otp, action: "verify" }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === "success") {
                        showAlert("success", "OTP verified successfully!");
                    } else {
                        showAlert("danger", data.message || "Invalid or expired OTP.");
                    }
                })
                .catch(() => showAlert("danger", "An error occurred while verifying OTP."));
        });
    }
  
    // Clean up URL Parameters
    const url = new URL(window.location.href);
    if (url.searchParams.has("success") || url.searchParams.has("error")) {
        url.searchParams.delete("success");
        url.searchParams.delete("error");
        history.replaceState(null, "", url);
    }
  });
  
  
  console.log("add-member.js is loaded and executing.");
  
  document.addEventListener("DOMContentLoaded", function () {
      const firstName = document.getElementById("memberFirstname");
      const lastName = document.getElementById("memberLastname");
      const email = document.getElementById("memberEmail");
      const otp = document.getElementById("otp");
  
      const validateField = (field, pattern) => {
          if (!pattern.test(field.value.trim())) {
              field.setCustomValidity(field.title);
          } else {
              field.setCustomValidity("");
          }
      };
  
      // Validate First Name
      firstName.addEventListener("input", () => validateField(firstName, /^[A-Za-z\s]+$/));
  
      // Validate Last Name
      lastName.addEventListener("input", () => validateField(lastName, /^[A-Za-z\s]+$/));
  
      // Email Validation
    //   email.addEventListener("input", () => {
    //       if (!email.validity.valid) {
    //           email.setCustomValidity("Please enter a valid email address.");
    //       } else {
    //           email.setCustomValidity("");
    //       }
    //   });
  
      // OTP Validation
      otp.addEventListener("input", () => {
          if (!otp.value.trim()) {
              otp.setCustomValidity("Please enter the OTP.");
          } else {
              otp.setCustomValidity("");
          }
      });
  
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