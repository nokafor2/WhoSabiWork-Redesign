<template>
    <form @submit.prevent="$event => form.post(route('photograph.store'))">
        <!-- <form @submit.prevent="$event => form.post('/posts')"> -->
    <!-- <form @submit.prevent="uploadPhotos"> -->
        <div id="app">
            <!-- <input type="text" class="caption"/> -->
            <file-pond
                name="image"
                ref="pond"
                class-name="my-pond"
                allow-multiple="true"
                accepted-file-types="image/jpeg, image/png"
                :server="{
                    url: '',
                    process: {
                        url: '/upload',
                        method: 'POST',
                        onload: handleFilePondLoad
                    },
                    revert: handleFilePondRevert,
                    headers: {
                        'X-CSRF-TOKEN': $page.props.csrf_token
                    },
                }"
                v-on:init="handleFilePondInit"
            />
        </div>

        <div class="row justify-content-center mt-3">
            <button type="submit" class="btn btn-danger w-25" :class="{ 'opacity-25' : form.processing }" :disabled="form.processing">Upload</button>
        </div>
    </form>
</template>

<script>
    // Import FilePond
    import vueFilePond, { setOptions } from 'vue-filepond';
    import { useForm, router } from '@inertiajs/vue3';

    // Import plugins
    import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js';
    import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js';
    import FilePondPluginImageTransform from 'filepond-plugin-image-transform';
    import FilePondPluginImageCrop from 'filepond-plugin-image-crop';
    import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
    import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
    import FilePondPluginImageResize from 'filepond-plugin-image-resize';
    import FilePondPluginImageValidateSize from 'filepond-plugin-image-validate-size';
    import FilePondPluginImageEdit from 'filepond-plugin-image-edit';

    // Import styles
    import 'filepond/dist/filepond.min.css';
    import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';
    import 'filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css';
 
    // Create FilePond component
    const FilePond = vueFilePond(
        FilePondPluginFileValidateType, 
        FilePondPluginImagePreview,
        FilePondPluginImageTransform,
        FilePondPluginImageCrop,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize, // absent
        FilePondPluginImageResize,
        FilePondPluginImageValidateSize, // absent
        FilePondPluginImageEdit,
    );

    // Set options before creating the components
    setOptions({
        labelIdle: 'Drag & Drop your picture or <span class="filepond--label-action">Browse</span>',
        imagePreviewHeight: 200,
        imageCropAspectRatio: '1:1',
        imageResizeTargetWidth: 300,
        imageResizeTargetHeight: 300,
        stylePanelLayout: 'null', // compact, circle
        styleLoadIndicatorPosition: 'center bottom',
        styleProgressIndicatorPosition: 'right bottom',
        styleButtonRemoveItemPosition: 'left bottom',
        styleButtonProcessItemPosition: 'right bottom',
        maxFiles: 10,
        allowReorder: true,
        fileSizeBase: 15000,
        credits: false
    });

    export default {
        name: 'app',
        data: function () {
            return { 
                form: useForm({
                    captions: [],
                    images: [],
                })
            };
        },
        methods: {
            handleFilePondInit: function () {
                console.log('FilePond has initialized');

                // example of instance method call on pond reference
                // this.$refs.pond.getFiles();
                // this.$refs.pond.insertAfter('<input type="text" />');
            },
            uploadPhotos(event) {
                this.form.post(route('photograph.store'), {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: (page) => {
                        console.log(page);
                        // this.$refs.pond.load();
                        // this.form.images = [];
                    },
                    onError: (errors) => {
                        console.log('Error: ', errors);
                    }
                });
            },
            handleFilePondLoad(response) {
                // push the response unique id gotten
                this.form.images.push(response);

                return response;
            },
            handleFilePondRevert(uniqueId, load, error) {
                // console.log(uniqueId);
                this.todelete = {uniqueId};
                // Remove the earlier created temporary file
                this.form.images = this.form.images.filter((image) => image !== uniqueId);
                // console.log(this.form.images);
                // router.delete("/revert/"+uniqueId);
                this.sendId = useForm(this.todelete);
                this.sendId.post(route('deleteimage'));
                

                // report an error
                // error('An error occured!');

                // Should call the load method when done
                load();
            }
        },
        components: {
            FilePond,
        },
    };
</script>