<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Last Name:</dt>
                    <dd class="col-sm-9 mb-0">Okafor</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree">
            <div class="accordion-body">
                <form class=""  action="{{ route('register') }}" method="POST">
                    <!-- @csrf -->
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required 
                        class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}">
            
                        <!-- @if ($errors->has('last_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif -->
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateLastName" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearLastName" class="btn btn-danger col-auto">Clear</button>
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