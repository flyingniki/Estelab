const form = document.querySelector(".decor");
const form1 = document.querySelector(".form-1");
const form2 = document.querySelector(".form-2");
const form3 = document.querySelector(".form-3");
const form4 = document.querySelector(".form-4");
const btnForward1 = document.querySelector(".btn-1-forward");
const btnForward2 = document.querySelector(".btn-2-forward");
const btnBack2 = document.querySelector(".btn-2-back");
const btnForward3 = document.querySelector(".btn-3-forward");
const btnBack3 = document.querySelector(".btn-3-back");
const btnBack4 = document.querySelector(".btn-4-back");
const selects = document.querySelectorAll("select");
const submit = document.querySelector(".btn-submit");

document.addEventListener("DOMContentLoaded", function () {
  btnForward1.addEventListener("click", (e) => {
    e.preventDefault();
    form1.classList.add("hidden");
    form2.classList.remove("hidden");
  });
  btnForward2.addEventListener("click", (e) => {
    e.preventDefault();
    form2.classList.add("hidden");
    form3.classList.remove("hidden");
  });
  btnForward3.addEventListener("click", (e) => {
    e.preventDefault();
    form3.classList.add("hidden");
    form4.classList.remove("hidden");
    submit.style.display = "block";
  });

  btnBack2.addEventListener("click", (e) => {
    e.preventDefault();
    form2.classList.add("hidden");
    form1.classList.remove("hidden");
  });
  btnBack3.addEventListener("click", (e) => {
    e.preventDefault();
    form3.classList.add("hidden");
    form2.classList.remove("hidden");
  });
  btnBack4.addEventListener("click", (e) => {
    e.preventDefault();
    form4.classList.add("hidden");
    form3.classList.remove("hidden");
  });

  selects.forEach((option) => {
    option.addEventListener("click", () => {
      if (
        option.value !== "Нет" &&
        option.nextElementSibling.tagName === "DIV"
      ) {
        option.nextElementSibling.classList.toggle("hidden");
      }
    });
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

  $(".js-example-basic-multiple").select2();
});
