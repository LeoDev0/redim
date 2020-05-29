<?php

if (isset($_FILES['imagem']) && !empty($_FILES['imagem'])) {
  $arquivo = $_FILES['imagem'];
  $allowedFileTypes = ['image/png', 'image/jpeg', 'image/jpg'];
  if (in_array($arquivo['type'], $allowedFileTypes)) {
    if (isset($_POST['width']) && !empty($_POST['width'])) {

      $indexDoPonto = strrpos($arquivo['name'], '.');
      $extensao = substr($arquivo['name'], $indexDoPonto);
      $arquivoNome = 'resized' . $extensao;

      move_uploaded_file($arquivo['tmp_name'], 'assets/images/' . $arquivoNome);

      $largura = $_POST['width'];
      $altura = $_POST['height'];
    
      list($larguraOriginal, $alturaOriginal) = getimagesize('assets/images/' . $arquivoNome);

      $ratio = $larguraOriginal / $alturaOriginal;

      if ($largura / $altura > $ratio) {
        $largura = $altura * $ratio;
      } else {
        $altura = $largura / $ratio;
      }

      $imagemFinal = imagecreatetruecolor($largura, $altura);

      if ($arquivo['type'] === 'image/jpeg' || $arquivo['type'] === 'image/jpg') {
        $imagemOriginal = imagecreatefromjpeg('assets/images/' . $arquivoNome);

        imagecopyresampled($imagemFinal, $imagemOriginal,
        0, 0, 0, 0,
        $largura, $altura,
        $larguraOriginal, $alturaOriginal);

        ob_start();
        imagejpeg($imagemFinal, null, 100);
        $imagedata = ob_get_clean();
        echo '<center>';
          echo '<img class="resized-image" src="data:image/jpeg;base64,'.base64_encode($imagedata).'"/><br>';
          echo '<a class="btn-lg btn-warning download-btn" href="data:image/jpeg;base64,'.base64_encode($imagedata).'" download><img class="download-icon" src="assets/images/icons/download.png">Download</a>';
        echo '</center>';

      } else {
        $imagemOriginal = imagecreatefrompng('assets/images/' . $arquivoNome);

        imagecopyresampled($imagemFinal, $imagemOriginal,
        0, 0, 0, 0,
        $largura, $altura,
        $larguraOriginal, $alturaOriginal);

        ob_start();
        imagepng($imagemFinal, null);
        $imagedata = ob_get_clean();
        echo '<center>';
          echo '<img class="resized-image" src="data:image/png;base64,'.base64_encode($imagedata).'"/><br>';
          echo '<a class="btn-lg btn-warning download-btn" href="data:image/png;base64,'.base64_encode($imagedata).'" download><img class="download-icon" src="assets/images/icons/download.png">Download</a>';
        echo '</center>';
      }   
    }
  } else {
    echo '<script language="javascript">';
    echo 'alert("Selecione um arquivo JPG ou PNG.")';
    echo '</script>';
  }
}