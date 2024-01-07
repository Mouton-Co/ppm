<script src="https://cdn.tiny.cloud/1/{{ env('TINY_MCE_API_KEY') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: '#myeditorinstance',
    plugins: 'code table lists',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
    menubar: false,
    content_css: '{{ asset("css/tinymce.css") }}',
    placeholder: 'Email body...',
    height: 500,
    init_instance_callback : function(editor) {
      editor.setContent("{!! $value !!}");
    }
  });
</script>