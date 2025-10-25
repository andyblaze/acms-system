<?=doctype();?>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?=$page_title;?></title>
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



<!-- content that changes per page - from DB -->

<div class="container">
    <div class="row">
        <div class="col">
            <header>
                <div>
                    <h1>Welcome to CodeIgniter <?= CodeIgniter\CodeIgniter::CI_VERSION ?></h1>
                    <h2>The small framework with powerful features</h2>
                </div>
            </header>
            <section class="cms-editable">
                <h1>About this page</h1>
                <p>The page you are looking at is being generated dynamically by CodeIgniter.</p>
                <p>If you would like to edit this page you will find it located at:</p>
                <pre><code>app/Views/welcome_message.php</code></pre>
                <p>The corresponding controller for this page can be found at:</p>
                <pre><code>app/Controllers/Home.php</code></pre>
            </section>
        </div>
    </div>
</div>

<!-- mostly common to all pages -->
<div class="container">
    <div class="row">
        <div class="col">
            <footer>
                <?=$footerMenu;?>
                <div> <!-- these bits are just for debugging / reporting -->
                    <p>Page rendered in {elapsed_time} seconds using {memory_usage} MB of memory.</p>
                    <p>Environment: <?= ENVIRONMENT ?></p>
                </div>
            </footer>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<script>
/*  const quill = new Quill(".cms-editable", {
  modules: {
    toolbar: [
      [{ header: [1, 2, false] }],
      ['bold', 'italic', 'underline'],
      ['image', 'code-block'],
    ],
  },
    theme: 'snow'
  });*/
</script>
<!-- common to all pages -->
<?=script_tag('js/jquery-3.7.1.min.js') .
script_tag('js/bootstrap.bundle.min.js');?>
</body>
</html>
