<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Redimensionador de imagens</title>
</head>
<body>

  <form method="post" enctype="multipart/form-data">
    <label for="imagem">Selecione a imagem:</label>
    <input id="browse" type="file" name="imagem" accept=".png, .jpg, .jpeg"><br><br>
    <div id="preview"></div>
    <input type="number" name="width" placeholder="Largura"><br><br>
    <input type="number" name="height" placeholder="Altura"><br><br>
    <button>Redimensionar</button>
  </form>

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
          echo '<img src="data:image/jpeg;base64,'.base64_encode($imagedata).'"/><br>';
          echo '<a href="data:image/jpeg;base64,'.base64_encode($imagedata).'" download>Download image</a>';

        } else {
          $imagemOriginal = imagecreatefrompng('assets/images/' . $arquivoNome);

          imagecopyresampled($imagemFinal, $imagemOriginal,
          0, 0, 0, 0,
          $largura, $altura,
          $larguraOriginal, $alturaOriginal);

          ob_start();
          imagepng($imagemFinal, null);
          $imagedata = ob_get_clean();
          echo '<img src="data:image/png;base64,'.base64_encode($imagedata).'"/><br>';
          echo '<a href="data:image/png;base64,'.base64_encode($imagedata).'" download>Download image</a>';
        }
        
      }

    } else {
      echo '<script language="javascript">';
      echo 'alert("Só são aceitos arquivos JPG e PNG")';
      echo '</script>';
    }
  }
  
  ?>
  
  <script src="assets/js/script.js"></script>
</body>
</html>