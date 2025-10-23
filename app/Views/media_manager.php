<?=doctype();?>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CMS media manager</title>
<?=meta('viewport', 'width=device-width, initial-scale=1.0') .
link_tag('/favicon.ico', 'shortcut icon', 'image/png') .
link_tag('css/bootstrap.min.css');?>
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">CMS media manager</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    <div class="modal-body">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-4">tree</div>
          <div class="col-md-8">files / images</div>
        </div>
      </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">OK</button>
      </div>
    </div>
  </div>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
              Launch static backdrop modal
            </button>
        </div>
    </div>
</div>


<!-- common to all pages -->
<?=script_tag('js/jquery-3.7.1.min.js') .
script_tag('js/bootstrap.bundle.min.js');?>
</body>
</html>
