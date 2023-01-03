const form = document.querySelector(".form");
const submit = document.querySelector(".btn");
const success = document.querySelector(".success");

document.addEventListener("DOMContentLoaded", function () {
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    e.stopPropagation();

    let formData = new FormData(form);
    let request = new XMLHttpRequest();

    request.open("POST", form.action);
    request.onreadystatechange = function () {
      if (request.readyState == 4 && request.status == 200) {
        form.classList.toggle("hidden");
        success.classList.toggle("hidden");
      }
    };
    request.send(formData);
  });
});
