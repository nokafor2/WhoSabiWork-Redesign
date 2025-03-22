<template>
    <div class="carousel-inner">
        <div v-for="image in slideShowImages2" :key="image" class="carousel-item active">
            <img :src="imagePath(image)" class="d-block w-100" alt="...">
        </div>
    </div>
</template>

<script setup>
    // const slideShowImages = ['slideShowImageB.jpg', 'slideShowImageD.jpg', 'slideShowImageF.jpg', 'slideShowImageG.jpg', 'slideShowImageH.jpg', 'slideShowImageI.jpg', 'slideShowImageJ.jpg', 'slideShowImageK.jpg', 'slideShowImageL.jpg', 'slideShowImageM.jpg', 'slideShowImageN.jpg'];

    // function imagePath(imageName) {
    //     return "../../../../homePageImages/" + imageName;
    // }
</script>

<script>
export default {
    data() {
        return {
            slideShowImages: ['slideShowImageB.jpg', 'slideShowImageD.jpg', 'slideShowImageF.jpg', 'slideShowImageG.jpg', 'slideShowImageH.jpg', 'slideShowImageI.jpg', 'slideShowImageJ.jpg', 'slideShowImageK.jpg', 'slideShowImageL.jpg', 'slideShowImageM.jpg', 'slideShowImageN.jpg'],
            slideShowImages2: ['slideShowImageB', 'slideShowImageD', 'slideShowImageF', 'slideShowImageG', 'slideShowImageH', 'slideShowImageI', 'slideShowImageJ', 'slideShowImageK', 'slideShowImageL', 'slideShowImageM', 'slideShowImageN']
        };
    },
    methods: {
        imagePath(imageName) {
            const imageUrl = new URL(`../../../../homePageImages/${imageName}.jpg`, import.meta.url).href;

            return imageUrl;
        },
        getImage(fileName) {
            try {
                const modules = import.meta.glob('@/assets/images/**/*.{png,svg}', { eager: true, import: 'default' })
                const moduleKeys = Object.keys(modules)
                const fileSrc = moduleKeys.find(key => key.endsWith(fileName))

                return fileSrc ? modules[fileSrc] : ''
            } catch (err) {
                console.log(err)
            }
        }
    }
}
</script>