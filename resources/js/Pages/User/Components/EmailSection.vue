<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSix">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Email:</dt>
                    <dd class="col-sm-9 mb-0">nna_okafor@yahoo.com</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix">
            <div class="accordion-body">
                <form class=""  action="{{ route('register') }}" method="POST">
                    <!-- @csrf -->
                    <div class="form-group col-12 mb-3">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required 
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
            
                        <!-- @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif -->
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateEmail" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearEmail" class="btn btn-danger col-auto">Clear</button>
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