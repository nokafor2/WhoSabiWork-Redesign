<template>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <dl class="row col-12 mb-0">
                    <dt class="col-sm-3">First Name:</dt>
                    <dd class="col-sm-9 mb-0">Nna-ayua</dd>
                </dl>
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
            <div class="accordion-body">
                <form class=""  action="{{ route('register') }}" method="POST">
                    <!-- @csrf -->
                    <div class="form-group col-12 mb-3">
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required 
                        class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}">
                    </div>
                    
                    <div class="row justify-content-between">
                        <button type="submit" name="updateFirstName" class="btn btn-danger col-auto">Update</button>
                        <button type="submit" name="clearFirstName" class="btn btn-danger col-auto">Clear</button>
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