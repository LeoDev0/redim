const EL_browse = document.getElementById("browse");
const EL_preview = document.getElementById("preview");
const widthInput = document.querySelector("input[name='width']");
const heightInput = document.querySelector("input[name='height']");
const percentageInput = document.getElementById("percentage");

const readImage = file => {
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

EL_browse.addEventListener("change", ev => {
  EL_preview.innerHTML = "";
  const files = ev.target.files;
  if (!files || !files[0]) return alert("Arquivo não suportado.");
  [...files].forEach(readImage);
});

// Atualização dinâmica dos valores de largura, altura e porcentagem
// baseados na proporção original da imagem
widthInput.onkeyup = () => {
  newHeightValue = widthInput.value / ratio;
  heightInput.value = newHeightValue.toFixed();
  percentageInput.value = ((widthInput.value * 100) / originalWidth).toFixed();
};

heightInput.onkeyup = () => {
  newWidthValue = heightInput.value * ratio;
  widthInput.value = newWidthValue.toFixed();
  percentageInput.value = ((widthInput.value * 100) / originalWidth).toFixed();
};

percentageInput.onkeyup = () => {
  widthInput.value = (originalWidth * (percentageInput.value / 100)).toFixed();
  newHeightValue = widthInput.value / ratio;
  heightInput.value = newHeightValue.toFixed();
};

// jquery usado para renderizar os tooltips do bootstrap
$(function() {
  $('[data-toggle="tooltip"]').tooltip();
});
