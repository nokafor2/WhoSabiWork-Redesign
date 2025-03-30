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
        isAlphabet(char) {
            return /^[a-zA-Z]$/.test(char);
        },
        capitalizeFirstLetter(str) {
            if (str !== null ) {
                return (this.isAlphabet(str.charAt(0))) ? str.charAt(0).toUpperCase() + str.slice(1) : str;
            } else {
                return str;
            }
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
        },
        newId(index, vehType) {
            var stringIndex = index.toString();
            return `${this.techOrSpare}_${vehType}_${stringIndex}`;
        },
        accordionTitle() {
            if (this.techOrSpare === 'technical_service') {
                return 'Technician';
            } else if (this.techOrSpare === 'spare_part') {
                return 'Spare Part';
            }
        }
    }
}