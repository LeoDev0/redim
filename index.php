<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <title>RedIm: single page image resizer</title>
</head>
<body>

  <div class="container shadow">

    <!-- <h2 class="text-center">RedIm</h2>
    <h5 class="text-center">single page image resizer</h5> -->
    <div style="margin-bottom:20px;" class="d-flex justify-content-center">
      <img width="180" height="180" src="assets/images/logo.png" alt="logo">
    </div>

    <form method="post" enctype="multipart/form-data">
      <div class="form-group">

        <div class="mb-42 input-group shadow-sm">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupFileAddon01"><strong>Upload</strong></span>
          </div>
          <div class="custom-file">
            <input class="custom-file-input" id="browse" type="file" name="imagem" accept=".png, .jpg, .jpeg" aria-describedby="inputGroupFileAddon01">
            <label id="preview" class="custom-file-label" for="browse">Escolha a imagem</label>
          </div>
        </div>

        <!-- <div id="preview"></div><br> -->
        <br>
        <div class="row">

          <div class="col-lg-4">
            <div class="input-group shadow-sm">
              <div class="input-group-prepend">
                <span class="input-group-text"><strong>Largura:</strong></span>
              </div>
              <input class="form-control" type="number" name="width">
              <div class="input-group-append">
                <span class="input-group-text">px</span>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="input-group shadow-sm">
              <div class="input-group-prepend">
                <span class="input-group-text"><strong>Altura:</strong></span>
              </div>
              <input class="form-control" type="number" name="height">
              <div class="input-group-append">
                <span class="input-group-text">px</span>
              </div>
            </div>
          </div>

          <div class="col-lg-4 d-flex justify-content-center">
            <button class="btn-lg btn-primary btn-block shadow">Redimensionar</button>
          </div>

        </div>
        <br>

      </div>
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
            echo '<center>';
              echo '<img class="resized-image" src="data:image/jpeg;base64,'.base64_encode($imagedata).'"/><br>';
              echo '<a class="btn-lg btn-success download-btn" href="data:image/jpeg;base64,'.base64_encode($imagedata).'" download>Download image</a>';
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
              echo '<a class="btn-lg btn-success download-btn" href="data:image/png;base64,'.base64_encode($imagedata).'" download>Download image</a>';
            echo '</center>';
          }
          
        }

      } else {
        echo '<script language="javascript">';
        echo 'alert("Selecione um arquivo JPG ou PNG.")';
        echo '</script>';
      }
    }
    
    ?>

  </div>

  <script type="text/javascript" src="assets/js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/script.js"></script>
</body>
</html>