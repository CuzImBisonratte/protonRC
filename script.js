body = document.body;
login_button = document.getElementById("login_button");

function openLogin() {

    // Add  class "body_nav_opened" to body
    body.classList.add("body_nav_opened");

    // Let the login button be invisible
    login_button.style.display = "none";

}

function closeLogin() {

    // Remove class "body_nav_opened" from body
    body.classList.remove("body_nav_opened");

    // Run 1 second later
    setTimeout(function() {

        // Let the login button be visible
        login_button.style.display = "block";

    }, 500);
}

// Add event listener escape button
document.addEventListener("keydown", function(event) {

    // if button escape is pressed
    if (event.keyCode == 27) {

        // Run function closeLogin
        closeLogin();
    }
});