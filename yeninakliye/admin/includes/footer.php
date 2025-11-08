        </main>
    </div>

    <script>
        // TinyMCE Editor başlatma
        if (document.querySelector('.tinymce-editor')) {
            tinymce.init({
                selector: '.tinymce-editor',
                height: 400,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                language: 'tr_TR'
            });
        }

        // Silme onayı
        function confirmDelete(message) {
            return confirm(message || 'Bu öğeyi silmek istediğinizden emin misiniz?');
        }
    </script>
</body>
</html>
