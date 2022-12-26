const form = document.querySelector(".decor");
const form1 = document.querySelector(".form-1");
const form2 = document.querySelector(".form-2");
const labels = document.querySelectorAll("label");
const btnForward = document.querySelector(".btn-forward");
const btnBack = document.querySelector(".btn-back");
const submit = document.querySelector(".btn-submit");

document.addEventListener("DOMContentLoaded", function () {
  btnForward.addEventListener("click", (e) => {
    e.preventDefault();
    form1.classList.add("hidden");
    form2.classList.remove("hidden");
    submit.classList.toggle("hidden");
  });

  btnBack.addEventListener("click", (e) => {
    e.preventDefault();
    form2.classList.add("hidden");
    form1.classList.remove("hidden");
    submit.classList.toggle("hidden");
  });

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
