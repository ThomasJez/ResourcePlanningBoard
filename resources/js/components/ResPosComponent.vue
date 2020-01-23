<template>
    <div>
        <h4>sort {{resourceTerm}}</h4>
        <form @submit.prevent="updatePos" @reset.prevent="resetPos">
            <div class = "form-row"  v-for="resource in resources">
                <div class="col">
                    <label>{{ resource.name }}</label>
                </div>
                <div class="col">
                    <input type="text" :name="'respos' + resource.id" class="form-control" v-model="resource.pos">
                </div>
            </div>
            <servererror-component
                v-if="servererror"
                v-bind:errorsprop="errors"
            >
            </servererror-component>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="reset" class="btn btn-primary">Reset</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                resources: {},
                message: 'Edit',
                show: 1,
                resposurl: this.resposurlprop,
                resourceTerm: this.resourceTermprop,
                servererror: false,
                errors: {}
            }
        },
        created() {
            this.axios.get(this.resposurl).then((response) => {
                this.resources = response.data;
            });
        },
        props: ['resposurlprop', 'resourceTermprop'],
        methods: {
            updatePos: function() {
                let uri = this.resposurl;
                this.axios.post(uri, this.resources)
                    .then((response) => {
                        this.$emit('invalidategantt');
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        } else {
                            this.errors = [['An unexpected error occured']];
                        }
                        this.servererror = true;
                    })
            },

            resetPos: function() {
                this.$emit('invalidategantt');
            }
        }
    }
</script>
