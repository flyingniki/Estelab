const form = document.querySelector(".decor");
const selects = document.querySelectorAll(".js-example-basic-single");
const submit = document.querySelector(".btn-submit");

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
        document.querySelector(".success").classList.toggle("hidden");
      }
    };
    request.send(formData);
  });

  $(".js-example-basic-single").select2();
});
