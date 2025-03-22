<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">Gender:</dt>
                    <dd class="col-sm-9 mb-0">Male</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour">
            <div class="accordion-body">
                <form class=""  action="{{ route('register') }}" method="POST">
                    <!-- @csrf -->
                    <div class="form-group col-12 mb-3">
                        <select name="gender" id="gender" required
                        class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
            
                        <!-- @if ($errors->has('gender'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                        @endif -->
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateGender" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearGender" class="btn btn-danger col-auto">Clear</button>
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