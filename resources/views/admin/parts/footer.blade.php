<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var editors = document.querySelectorAll('.ckeditor');
        editors.forEach(function(el) {
            ClassicEditor
                .create(el)
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>
<style>
  .ck-editor__editable_inline {
      min-height: 200px;
  }
</style>
<!-- Đổi đường dẫn js tĩnh thành hàm asset() của Laravel -->
<script src="{{ asset('backend/js/admin.js') }}"></script>
