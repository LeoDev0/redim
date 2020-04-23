const EL_browse = document.getElementById("browse");
const EL_preview = document.getElementById("preview");
const widthInput = document.querySelector("input[name='width']");
const heightInput = document.querySelector("input[name='height']");
const percentageInput = document.getElementById("percentage");

const readImage = (file) => {
  if (!/^image\/(png|jpe?g)$/.test(file.type))
    return EL_preview.insertAdjacentHTML(
      "beforeend",
      `Formato não suportado: ${file.type}: ${file.name}<br>`
    );

  const img = new Image();
  img.addEventListener("load", () => {
    // EL_preview.appendChild(img);
    EL_preview.insertAdjacentHTML(
      "beforeend",
      `<div>${file.name} ${img.width}×${img.height} ${file.type} ${Math.round(
        file.size / 1024
      )}KB<div>`
    );
    originalWidth = img.width;
    originalHeight = img.height;
    ratio = originalWidth / originalHeight;
    widthInput.value = originalWidth;
    heightInput.value = originalHeight;
    percentageInput.value = 100;
    window.URL.revokeObjectURL(img.src);
  });
  img.src = window.URL.createObjectURL(file);
};

EL_browse.addEventListener("change", (ev) => {
  EL_preview.innerHTML = "";
  const files = ev.target.files;
  if (!files || !files[0]) return alert("Arquivo não suportado.");
  [...files].forEach(readImage);
});

// Atualização dinâmica dos valores de largura, altura e porcentagem
// baseados na proporção original da imagem
function handleWidthChange() {
  newHeightValue = widthInput.value / ratio;
  heightInput.value = newHeightValue.toFixed();
  percentageInput.value = ((widthInput.value * 100) / originalWidth).toFixed();
}

function handleHeightChange() {
  newWidthValue = heightInput.value * ratio;
  widthInput.value = newWidthValue.toFixed();
  percentageInput.value = ((widthInput.value * 100) / originalWidth).toFixed();
}

function handlePercentageChange() {
  widthInput.value = (originalWidth * (percentageInput.value / 100)).toFixed();
  newHeightValue = widthInput.value / ratio;
  heightInput.value = newHeightValue.toFixed();
}

widthInput.addEventListener("keyup", handleWidthChange);
widthInput.addEventListener("change", handleWidthChange);

heightInput.addEventListener("keyup", handleHeightChange);
heightInput.addEventListener("change", handleHeightChange);

percentageInput.addEventListener("keyup", handlePercentageChange);
percentageInput.addEventListener("change", handlePercentageChange);

// Outra opção ao que foi feito acima seria colocar os eventos dentro
// de uma array e repetir eles dentro de um forEach
// ["click", "change"].forEach(event => {
//   widthInput.addEventListener(event, handleWidthChange);
// });

// jquery usado para renderizar os tooltips do bootstrap
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

// Loading animation
const bodyElem = document.querySelector("body");
const loaderContainerElem = document.getElementById("loader-container");
const containerElem = document.querySelector(".container");

bodyElem.onload = () => {
  loaderContainerElem.parentNode.removeChild(loaderContainerElem);
  containerElem.style.display = "block";
};
