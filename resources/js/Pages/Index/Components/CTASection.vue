<template>
    <!-- CTA Section - Matches .tsx: bg-secondary with primary button and outline button -->
    <section id="cta" class="py-5 bg-secondary-orange">
        <div class="container py-5">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="display-4 fw-bold mb-4">
                        Ready to Get Started?
                    </h2>
                    <p class="lead mb-4" style="opacity: 0.9;">
                        Join thousands of satisfied customers connecting with skilled artisans across Nigeria. 
                        Your perfect artisan is just a click away.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a :href="route('users.create')" class="btn btn-primary-dark btn-lg px-5 py-3">
                            Register as Artisan
                        </a>
                        <a :href="route('artisans.index')" class="btn btn-outline-secondary-foreground btn-lg px-5 py-3">
                            Find an Artisan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'CTASection'
}
</script>
