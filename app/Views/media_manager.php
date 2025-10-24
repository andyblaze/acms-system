<?=doctype();?>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CMS media manager</title>
<?=meta('viewport', 'width=device-width, initial-scale=1.0') .
link_tag('/favicon.ico', 'shortcut icon', 'image/png') .
link_tag('css/bootstrap.min.css');?>
<style type="text/css">
#media-menu ul li {
    cursor:pointer;
    font-size:1.4rem;
}
.folder.selected > .folder-name {
  background-color: #cce5ff; /* light blue highlight */
  border-radius: 4px;
}
ul.folder-tree,
ul.subtree {
  list-style: none;
  margin-left: 1em;
  padding-left: 0.5em;
}

.folder-name {
  cursor: pointer;
}

.folder.closed > .folder-name::before {
  content: '📁';
  margin-right: 6px;
}

.folder.open > .folder-name::before {
  content: '📂';
  margin-right: 6px;
}

.subtree.hidden {
  display: none;
}
</style>
</head>
<body>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">CMS media manager</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    <div class="modal-body">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-4" id="media-menu"><?=$media_menu;?></div>
          <div class="col-md-8" id="file-list">files / images</div>
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
<script>
$("#media-menu ul li").on("click", function(e) {
  e.stopPropagation();
  const li = $(this).closest(".folder");
  li.toggleClass("open closed");
  li.children(".subtree").slideToggle();
  // Remove previous selection
  $('.folder.selected').removeClass('selected');
  // Highlight this one
  li.addClass('selected');
  // Show loading state
  $('#file-list').html('<p>Loading files...</p>');

  const folderUrl = "http://localhost:8080/home/files" + li.data("url");
  console.log(folderUrl);
  // Fetch file list
  $.ajax({
    url: folderUrl,
    method: 'GET',
    dataType: 'json',
    success: function(response) { 
      if (response.files && response.files.length) {
        const html = '<ul>' + response.files.map(f => `<li>${f}</li>`).join('') + '</ul>';
        $('#file-list').html(html);
      } else {
        $('#file-list').html('<p>No files in this folder.</p>');
      }
    },
    error: function() {
      $('#file-list').html('<p style="color:red;">Error loading files.</p>');
    }
  });
});
</script>
</body>
</html>
