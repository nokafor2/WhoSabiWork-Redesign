<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFive">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Username:</dt>
                    <dd class="col-sm-9 mb-0">nokafor2</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive">
            <div class="accordion-body">
                <form class=""  action="{{ route('register') }}" method="POST">
                    <!-- @csrf -->
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" required 
                        class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}">
            
                        <!-- @if ($errors->has('username'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif -->
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateUsername" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearUsername" class="btn btn-danger col-auto">Clear</button>
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