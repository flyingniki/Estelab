const form1 = document.querySelector(".form-1");
const form2 = document.querySelector(".form-2");
const btn1 = document.querySelector(".btn-1");
const btn2 = document.querySelector(".btn-2");
const submit = document.querySelector(".btn-submit");

btn1.addEventListener("click", (e) => {
  e.preventDefault();
  form1.classList.add("hidden");
  form2.classList.remove("hidden");
});
btn2.addEventListener("click", (e) => {
  e.preventDefault();
  submit.style.display = "block";
});
