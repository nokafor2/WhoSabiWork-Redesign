<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSeven">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Phone number:</dt>
                    <dd class="col-sm-9 mb-0">0808 444 555</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven">
            <div class="accordion-body">
                <form class=""  action="{{ route('register') }}" method="POST">
                    <!-- @csrf -->
                    <div class="form-group col-12 mb-3">
                        <input type="tel" name="phoneNumber" value="{{ old('phoneNumber') }}" placeholder="Phone number" required 
                        class="form-control {{ $errors->has('phoneNumber') ? 'is-invalid' : '' }}">
            
                        <!-- @if ($errors->has('phoneNumber'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('phoneNumber') }}</strong>
                            </span>
                        @endif -->
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updatePhoneNumber" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearPhoneNumber" class="btn btn-danger col-auto">Clear</button>
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