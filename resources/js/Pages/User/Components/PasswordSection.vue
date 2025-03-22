<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingEight">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Password:</dt>
                    <dd class="col-sm-9 mb-0"></dd>
                </dl>
            </button>
        </h2>
        <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight">
            <div class="accordion-body">
                <form class=""  action="{{ route('register') }}" method="POST">
                    <!-- @csrf -->
                    <div class="form-group col-12 mb-3">
                        <input type="password" name="password" value="{{ old('password') }}" placeholder="Password" required 
                        class="form-control {{ $errors->has('phoneNumber') ? 'is-invalid' : '' }}">
                        <input type="password" name="password_confirmation" placeholder="Confirm password" required 
                        class="mt-2 form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">
    
                        <!-- @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif -->
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updatePassword" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearPassword" class="btn btn-danger col-auto">Clear</button>
                    </div>
                </form>   
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

            }
        },
        methods: {
            
        }
    }
</script>