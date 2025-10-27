<div class="container">
    <div class="row">
        <div class="col">
            <footer>
                <?=$footMenu;?>
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