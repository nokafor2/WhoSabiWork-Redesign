import { useForm, usePage } from '@inertiajs/vue3';

export default {
    data() {
        return {
            accountType: false,
            businessAccount: false,
            firstName: {
                val: '',
                isValid: true
            },
            lastName: {
                val: '',
                isValid: true
            },
            password: {
                val: '',
                isValid: true
            },
            confirmPassword: {
                val: '',
                isValid: true
            },
            gender: {
                val: '',
                isValid: true
            },
            username: {
                val: '',
                isValid: true
            },
            phoneNumber: {
                val: null,
                isValid: true
            },
            email: {
                val: '',
                isValid: true
            },
            addressLine1: {
                val: '',
                isValid: true
            },
            addressLine2: {
                val: '',
                isValid: true
            },
            addressLine3: {
                val: '',
                isValid: true
            },
            town: {
                val: '',
                isValid: true
            },
            state: {
                val: '',
                isValid: true
            },
            businessName: {
                val: '',
                isValid: true
            },
            businessCategories: {
                val: [],
                isValid: true
            },
            selectedArtisans: {
                val: [],
                isValid: true
            },
            selectedMobileSellers: {
                val: [],
                isValid: true
            },
            selectedTechnicalServices: {
                val: [],
                isValid: true
            },
            selectedSpareParts: {
                val: [],
                isValid: true
            },
            selectedVehicleCategories: {
                val: [],
                isValid: true
            },
            selectedCarBrands: {
                val: [],
                isValid: true
            },
            selectedBusBrands: {
                val: [],
                isValid: true
            },
            selectedTruckBrands: {
                val: [],
                isValid: true
            },
            selectedVehicleCategoriesSS: {
                val: [],
                isValid: true
            },
            selectedCarBrandsSS: {
                val: [],
                isValid: true
            },
            selectedBusBrandsSS: {
                val: [],
                isValid: true
            },
            selectedTruckBrandsSS: {
                val: [],
                isValid: true
            },
            artisanChoicesVisible: false,
            mobileSellerChoicesVisible: false,
            techServChoicesVisible: false,
            sparePartChoicesVisible: false,
            vehicleCategoriesVisible: false,
            carBrandsVisible: false,
            busBrandsVisible: false,
            truckBrandsVisible: false,
            vehicleCategoriesVisibleSS: false,
            carBrandsVisibleSS: false,
            busBrandsVisibleSS: false,
            truckBrandsVisibleSS: false,
            techServCat: 'techServ',
            sparePartCat: 'sparePart',
            formIsValid: true,
            formData: useForm({
                first_name: '',
                last_name: '',
                gender: '',
                username: '',
                password: '',
                password_confirmation: '',
                phone_number: '',
                email: '',
                address_line_1: '',
                address_line_2: '',
                address_line_3: '',
                state: '',
                town: '',
                business_name: '',
                businessCategories: '',
                selectedArtisans: '',
                selectedMobileSellers: '',
                selectedTechnicalServices: '',
                selectedSpareParts: '',
                selectedVehicleCategories: '',
                selectedCarBrands: '',
                selectedBusBrands: '',
                selectedTruckBrands: '',
                selectedVehicleCategoriesSS: '',
                selectedCarBrandsSS: '',
                selectedBusBrandsSS: '',
                selectedTruckBrandsSS: '',
            }),
            page: usePage(),
            dataToSend: {},
        }
    },
}