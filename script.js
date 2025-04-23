document.getElementById("login-form").addEventListener("submit", function (e) {
    e.preventDefault();
  
    const clickedButton = document.activeElement;
    const action = clickedButton.value;
    document.getElementById("action").value = action;
  
    const form = e.target;
    const formData = new FormData(form);
  
    fetch("login.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        const messageBox = document.getElementById("message");
        if (data.success) {
          messageBox.textContent = data.message || "Success!";
          messageBox.className = "text-amber-700 mt-2";
  
          if (data.redirect) {
            // Only redirect if login success
            setTimeout(() => {
              window.location.href = data.redirect;
            }, 1000);
          } else {
            // For signup, just clear form and refresh (optional)
            form.reset();
          }
        } else {
          messageBox.textContent = data.message;
          messageBox.className = "text-amber-700 mt-2";
        }
      })
      .catch(() => {
        const messageBox = document.getElementById("message");
        messageBox.textContent = "Something went wrong. Try again.";
        messageBox.className = "text-amber-700 mt-2";
      });
  });
  