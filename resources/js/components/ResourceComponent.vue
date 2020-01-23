<template>
    <div>
        <h4>{{resourceTerm}}</h4>
        <form @submit.prevent="updateRes" @reset.prevent="resetRes">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" class="form-control" v-model="resource.name">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Notes:</label>
                        <textarea class="form-control" v-model="resource.note" rows="5"></textarea>
                    </div>
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
                        <button class="btn btn-danger" @click.prevent="onDelete(resource.id)">Delete</button>
                        <button class="btn btn-success" @click.prevent="onCreate">Create</button>
                        <button class="btn btn-success" @click.prevent="onPos">Position</button>
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
                resource: {},
                message: 'Edit',
                show: 1,
                resurl: this.resurlprop,
                resourceTerm: this.resourceTermprop,
                servererror: false,
                errors: {}
            }
        },
        created() {
            this.axios.get(this.resurl).then((response) => {
                this.resource = response.data;
            });
        },
        props: ['resurlprop', 'resourceTermprop'],
        methods: {
            updateRes: function () {
                var uri = this.resurl;
                this.axios.put(uri, this.resource)
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

            resetRes: function() {
                this.axios.get(this.resurl).then((response) => {
                    this.resource = response.data;
                    this.$emit('invalidategantt');
                });
            },

            onDelete: function(id) {
                this.axios.delete(this.resurl).then((response) => {
                    this.$emit('invalidategantt');
                });
            },

            onCreate: function() {
                var uri = this.resurl.replace(/\/[0-9]+$/, '');
                this.axios.post(uri, this.resource)
                    .then((response) => {
                        this.$emit('invalidategantt');
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                            this.servererror = true;
                        }
                    })
            },

            onPos: function() {
                var uri = this.resurl.replace(/resource\/[0-9]+$/, 'resource-position');
                this.$emit('resposcalled', uri);
            }
        }
    }
</script>
