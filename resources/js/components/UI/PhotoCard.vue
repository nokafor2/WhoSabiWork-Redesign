<template>
    <div class="card m-3 shadow" style="width: 200px;">
        <img :src=imagePath(photoId) class="card-img-top btn" height="174px" alt="" type="button"  data-bs-toggle="modal" data-bs-target="#exampleModal">
        <p class="card-text mb-1 px-2">Image caption</p>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1> -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img :src=imagePath(photoId) class="img-fluid" alt="">
                        <p class="card-text my-2 px-2 bg-dark text-light rounded">Image caption</p>

                        <div class="col-12 rounded bg-dark text-light p-2">
                            <form action="">
                                <!-- <textarea class="rounded col-12 mt-3" name="" id="" placeholder="Enter your comment" rows="3"></textarea> -->
                                <textarea class="rounded form-control my-3" name="" id="" placeholder="Enter your comment" rows="3"></textarea>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-danger">Submnit</button>
                                    <p class="pt-2">5 comment(s)</p>
                                </div>
                            </form>
                            <div class="col-12 border-bottom pt-2">
                                <div class="row justify-content-between align-content-middle">
                                    <div class="col-auto">
                                        <div class="d-flex mt-2 mb-1 align-middle">
                                            <img class="col-auto rounded-circle me-2 rounded-circle" style="height: 25px; width: 25px;" :src=imagePath(0)>
                                            <p class="col-auto mb-1">Nna-ayua Okafor</p>
                                        </div>
                                    </div>
                                    <p class="col-auto pt-2">December 18, 2024 at 5:18 PM</p>
                                </div>
                                <p class="col-12 bg-white text-body rounded">
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo modi vero laboriosam suscipit! Enim reiciendis sunt debitis, ipsa sit ipsam amet obcaecati! Assumenda harum ipsam natus iste explicabo eos nemo?
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['photoId'],
        data() {
            return {
                image1: 1,
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
            }
        },
        methods: {
            imagePath(index) {
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            }
        }
    }
</script>