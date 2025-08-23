// Initialize EmailJS with your public key
(function() {
  emailjs.init("4sI5RHWBBWPn0HZFo");
})();

const form = document.getElementById("contact-form");
const successMessage = document.getElementById("success-message");
const errorMessage = document.getElementById("error-message");

form.addEventListener("submit", function(e) {
  e.preventDefault();

  emailjs.sendForm("luckymilktea", "template_d4quo6g", this)
    .then(function() {
      successMessage.style.display = "block";
      errorMessage.style.display = "none";
      form.reset();
    }, function(error) {
      successMessage.style.display = "none";
      errorMessage.style.display = "block";
      console.error("Error sending email:", error);
    });
});
