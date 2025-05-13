<template>
    <div v-if="subAptVisible" :id="subAptId(index)" class="card-body mb-2" >
        <div class="d-flex">
            <div class="row">
                <img :src="imagePath(index)" style="height: 7rem; width: 100%;" class="card-img-top" alt="...">
            </div>
            <div class="">
                <div class="d-block d-sm-flex">
                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                        <span v-for="(aptTime, index) in appointmentDetail.time" :key="index">
                            <i class="fa-regular fa-clock me-1"></i>
                            <span class="me-2">{{ aptTime.time }} {{ aptTime.period }}</span>
                        </span>
                    </p>
                </div>
                <div class="d-block d-sm-flex">
                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                        <i class="fa-solid fa-shop"></i>
                        {{ appointmentDetail.businessName }}
                    </p>
                </div>
                <div class="d-block d-sm-flex">
                    <p class="card-text col-xs-12 ps-0 ps-sm-2 mb-2">
                        <i class="fa-solid fa-phone"></i>
                        {{ appointmentDetail.phoneNumber }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row mt-3 ">
                <button :id="showAptId(index)" class="btn btn-white btn-sm" @click="showMainApt">Show</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['appointmentDetail', 'index'],
        // inject: ['subAptVisible'],
        emits: ['main-apt-visible', 'sub-apt-visible'],
        data() {
            return {
                adImages: ['photoSample', 'photoSample1', 'photoSample2', 'photoSample3', 'photoSample4', 'photoSample5', 'photoSample6', 'photoSample7', 'photoSample8', 'photoSample9', 'photoSample10', 'photoSample11', 'photoSample12', 'photoSample13', 'photoSample14', 'photoSample15', 'photoSample16', 'photoSample17', 'photoSample18', 'photoSample19', 'photoSample20'],
                // subAptVisible2: this.subAptVisible,
                // subAptVisible: this.$store.getters["appointment/getSubAptVisible"],
                subAptVisible: true,
            }
        },
        methods: {
            imagePath(index) {
                index = index + 1;
                const imageName = this.adImages[index];
                const imageUrl = new URL(`../../../../../Images/${imageName}.jpg`, import.meta.url).href;

                return imageUrl;
            },
            subAptId(index) {
                return "subApt"+index;
            },
            showAptId(index) {
                return "showBtn-subApt"+index;
            },
            showMainApt(event) {
                // var btnId = event.currentTarget.id;
                // // remove 'showBtn' from the id
                // var subDivId = btnId.replace("showBtn-", "");
                // var mainDivId = "main-"+subDivId;
                // this.toggleDiv(subDivId);
                // this.toggleDiv(mainDivId);

                // this.subAptVisible2 = false;
                // emit this
                this.$emit('main-apt-visible', true);
                this.$emit('sub-apt-visible', false);
                // console.log(this.$refs.mainApt.mainAptVisible);
                // this.mainAptVisible = true;
                // this.$store.dispatch("appointment/updateSubAptVisible", false);
                // this.$store.dispatch("appointment/updateMainAptVisible", true);
            },
        },
        // computed: {
        //     subAptVisible() {
        //         return this.$store.getters["appointment/getSubAptVisible"];
        //     }
        // }
    }
</script>