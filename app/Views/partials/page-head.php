<?=doctype();?>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?=$page_title->render();?></title>
<?=meta('description', $meta_description) .
meta('viewport', 'width=device-width, initial-scale=1.0') .
link_tag('/favicon.ico', 'shortcut icon', 'image/png') .
link_tag('css/bootstrap.min.css');?>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenuNav" aria-controls="mainMenuNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainMenuNav">
      <?=$mainMenu;?>
    </div>
  </div>
</nav>
