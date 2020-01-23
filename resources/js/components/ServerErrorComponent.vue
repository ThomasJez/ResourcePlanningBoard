<template>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <div class="alert alert-danger">
                    <ul>
                        <li v-for="error in errors">
                            <div v-for="suberror in error">
                                {{ suberror }}
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                errors: this.errorsprop
            }
        },
        props: ['errorsprop'],
        watch: {
            errorsprop: function(val, oldVal) {
                this.errors = val;
            }
        },
    }
</script>
