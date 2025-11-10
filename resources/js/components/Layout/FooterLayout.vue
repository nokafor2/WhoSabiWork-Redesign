<template>
    <!-- Footer -->
    <footer class="bg-muted-custom py-5 border-top">
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex align-items-center justify-content-center bg-secondary-orange rounded-3 me-2" style="width: 40px; height: 40px;">
                            <span class="fw-bold fs-5 text-white">
                                <img src="../../../Images/WhoSabiWorkLogo.png" alt="Website Logo" class="img-fluid" style="height: 45px;">
                            </span>
                        </div>
                        <span class="fw-bold fs-5">WhoSabiWork</span>
                    </div>
                    <p class="text-muted-custom small">Connecting skilled artisans with customers across Nigeria.</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3">Services</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a :href="route('artisans.index')" class="text-muted-custom text-decoration-none hover-secondary small">
                                Artisans
                            </a>
                        </li>
                        <li class="mb-2">
                            <a :href="route('mobilemarketers.index')" class="text-muted-custom text-decoration-none hover-secondary small">
                                Mobile Market
                            </a>
                        </li>
                        <li class="mb-2">
                            <a :href="route('artisans.index')" class="text-muted-custom text-decoration-none hover-secondary small">
                                Mechanics
                            </a>
                        </li>
                        <li class="mb-2">
                            <a :href="route('mobilemarketers.index')" class="text-muted-custom text-decoration-none hover-secondary small">
                                Spare Parts
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3">Company</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#about" class="text-muted-custom text-decoration-none hover-secondary small">About Us</a>
                        </li>
                        <li class="mb-2">
                            <a :href="route('contactus.index')" class="text-muted-custom text-decoration-none hover-secondary small">
                                Contact
                            </a>
                        </li>
                        <li class="mb-2">
                            <a :href="route('home.photoFeeds')" class="text-muted-custom text-decoration-none hover-secondary small">
                                Photo Feed
                            </a>
                        </li>
                        <li class="mb-2">
                            <a :href="route('home.index')" class="text-muted-custom text-decoration-none hover-secondary small">
                                Home
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-bold mb-3">Legal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#privacy" class="text-muted-custom text-decoration-none hover-secondary small">Privacy Policy</a>
                        </li>
                        <li class="mb-2">
                            <a href="#terms" class="text-muted-custom text-decoration-none hover-secondary small">Terms of Service</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted-custom">
                <p class="mb-0 small">&copy; 2025 WhoSabiWork. All rights reserved.</p>
                <p class="mb-0 small">Powered by Ayua Global Technologies</p>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: 'FooterLayout'
}
</script>

