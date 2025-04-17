@props(['initialContent', 'height' => '93vh'])
<div>
    @assets
        <script src="https://cdn.tiny.cloud/1/qlxj53rzt7lnf165jz1ytpx2qrxq53zt6us58fvh1tuuyho1/tinymce/7/tinymce.min.js"
            referrerpolicy="origin"></script>
    @endassets

    <div id="tinymceContainer" wire:ignore> </div>

    @script
        <script>
            const editorContent = document.createElement('textarea');
            document.getElementById('tinymceContainer').appendChild(editorContent);

            tinymce.init({
                target: editorContent,
                plugins: [
                    'advlist', 'anchor', 'autolink', 'codesample', 'fullscreen',
                    'image', 'lists', 'advlist', 'link',
                    'table',
                ],
                toolbar: 'undo redo | bold italic fontsize | alignleft aligncenter alignright alignjustify | bullist numlist | link image',
                fixed_toolbar_container: '#mytoolbar',
                indentation: '20pt',
                indent_use_margin: true,
                statusbar: false,
                menubar: false,
                height: @js($height),
                // content_style: "body { font-family: Quicksand; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; font-smooth: always; }",
                content_style: "@import url('https://fonts.googleapis.com/css2?family=Archivo:wght@700&family=Quicksand:wght@500&display=swap'); body { font-family: 'Quicksand', sans-serif; padding: 30px 60px; }",

                /* enable title field in the Image dialog*/
                image_title: true,
                /* enable automatic uploads of images represented by blob or data URIs*/
                automatic_uploads: true,
                /*
                  URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
                  images_upload_url: 'postAcceptor.php',
                  here we add custom filepicker only to Image dialog
                */
                file_picker_types: 'image',
                /* and here's our custom image picker*/
                file_picker_callback: (cb, value, meta) => {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.addEventListener('change', (e) => {
                        const file = e.target.files[0];

                        const reader = new FileReader();
                        reader.addEventListener('load', () => {
                            /*
                              Note: Now we need to register the blob in TinyMCEs image blob
                              registry. In the next release this part hopefully won't be
                              necessary, as we are looking to handle it internally.
                            */
                            const id = 'blobid' + (new Date()).getTime();
                            const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            const base64 = reader.result.split(',')[1];
                            const blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);

                            /* call the callback and populate the Title field with the file name */
                            cb(blobInfo.blobUri(), {
                                title: file.name
                            });
                        });
                        reader.readAsDataURL(file);
                    });

                    input.click();
                },
                image_caption: true,
                // content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
                setup: (editor) => {
                    // Set initial content from the Blade attribute
                    editor.on('init', () => {
                        editor.setContent(@js($initialContent));
                        console.log()
                    });

                    // Trigger dynamic method when the content is updated
                    editor.on('change', () => {
                        // console.log(editor.getContent());
                        $wire.$set(@js($attributes->whereStartsWith('wire:model')->first()),
                            editor.getContent());

                    });
                }
            });
        </script>
    @endscript

</div>
