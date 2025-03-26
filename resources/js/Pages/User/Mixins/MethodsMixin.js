export default {
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
        },
        capitalizeFirstLetter(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        explodeArray(categories, seperator) {
            var val = '';
            for (let [key, category] of Object.entries(categories)) {
                val += category;
                if ((Number(key) + 1) < categories.length) {
                    val += seperator;
                }
            }
            return val;
        }
    }
}