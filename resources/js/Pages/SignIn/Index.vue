<template>
    <div class="mx-auto my-3 text-center display-5">
        Sign In
    </div>
    
    <div class="row justify-content-center">
        <div class="col-sm-6 col-md-5 py-3">
            <form @submit.prevent="submitForm">
                <div class="form-floating my-2">
                    <input type="text" class="form-control" id="username" placeholder="">
                    <label for="username">Username / E-mail / Phone number</label>
                </div>
                <div class="form-floating my-2">
                    <input type="password" class="form-control" id="password" placeholder="">
                    <label for="password">Password</label>
                </div>
                <button class="btn btn-sm bg-white">
                    Forgot password?
                </button>
                
                <div class="my-3">
                    <button class="btn btn-danger w-100" type="submit">Submit</button>
                </div>

                <div class="d-flex justify-content-center mb-3">
                    <p class="col-auto" style="width: 100%;
                    text-align: center;
                    border-bottom: 1px solid gray;
                    line-height: 0.1em;
                    margin: 20px 0 20px;">
                        <span class="bg-white px-3">Or Sign In Using</span>
                    </p>

                    <!-- <p class="col-auto w-100 text-center border-bottom border-dark lh-sm my-2">
                        <span class="bg-white px-3">Or Sign In Using</span>
                    </p> -->
                </div>

                <div class="row gx-2">
                    <div class="col-sm-6 mb-2 mb-sm-0">
                        <button class="col btn btn-primary text-light w-100"><i class="fa-brands fa-facebook pe-2"></i>Facebook</button>
                    </div>
                    <div class="col-sm-6">
                        <!-- <button class="col btn btn-light text-primary border-primary w-100"><i class="fa-brands fa-google pe-2"></i>Google</button> -->
                        <button class="col btn btn-light text-primary border-primary w-100 align-middle"><img class="pe-2" style="width:25px;" src="https://developers.google.com/identity/images/g-logo.png">Google</button>
                    </div>
                </div>

                <p class="small text-center pt-2">Don't have an account? <a class="text-decoration-none text-danger">Sign up here</a></p>
            </form>
        </div>
    </div>
</template>


<script>
    export default {
        data() {
            return {

            }
        },
        methods: {
            async submitForm() {

            }
        }
    }
</script>