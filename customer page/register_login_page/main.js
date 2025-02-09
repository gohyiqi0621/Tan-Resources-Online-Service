function sendOTP() {
    let email = document.getElementById("email").value;
    let errorMessage = document.getElementById("error-message");
    let successMessage = document.getElementById("success-message");

    if (!email) {
        errorMessage.style.display = "block";
        successMessage.style.display = "none";
        return;
    } else {
        errorMessage.style.display = "none";
    }

    fetch("backend/otp/send_otp.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "email=" + encodeURIComponent(document.getElementById("email").value)
    })
    .then(response => response.text()) // Change from .json() to .text()
    .then(data => {
        console.log("Raw response:", data); // Check if it's returning JSON or an error page
        try {
            let json = JSON.parse(data);
            console.log("Parsed JSON:", json);
            if (json.status === "success") {
                document.getElementById("success-message").style.display = "block";
            } else {
                document.getElementById("error-message").innerText = json.message;
                document.getElementById("error-message").style.display = "block";
            }
        } catch (e) {
            console.error("JSON Parse Error:", e);
        }
    })
    .catch(error => console.error("Fetch error:", error));
}