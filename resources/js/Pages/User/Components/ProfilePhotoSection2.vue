<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Profile Photo:</dt>
                    <dd class="col-sm-9 mb-0"></dd>
                </dl>
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne">
            <div class="accordion-body">
                <div class="d-sm-flex align-items-center">
                    <div class="">
                        <img class="col-auto rounded-circle me-2" style="height: 120px; width: 120px;" :src=imagePath(0)>
                    </div>   
                    <form class=""  action="{{ route('register') }}" method="POST">
                        <!-- @csrf -->
                        <div class="form-group col-12 mb-3">
                            <label for="formFile" class="form-label">Upload profile photo</label>
                            <input class="form-control" type="file" id="formFile">
                        </div>
                        <p>Allowed images: .jpg, .jpeg, .png, .gif</p>
                        <button type="submit" name="updatePhoto" class="btn btn-sm btn-danger col-auto">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: [],
        emits: [],
        data() {
            return {
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            }
        }
    }
</script>