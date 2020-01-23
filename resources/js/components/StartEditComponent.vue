<template>
    <div>
        <h4>Start</h4>
        <form @submit.prevent="updateStart" @reset.prevent="resetStart">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Start:</label>
                        <input type="text" class="form-control" v-model="chartstart">
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
                chartstart: {},
                message: 'Edit',
                show: 1,
                starturl: this.starturlprop,
                servererror: false,
                errors: {}
            }
        },
        created() {
            this.axios.get(this.starturl).then((response) => {
                this.chartstart = response.data;
            });
        },
        props: ['starturlprop'],
        methods: {
            updateStart: function() {
                this.axios.put(this.starturl, [this.chartstart])
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

            resetStart: function() {
                this.axios.get(this.starturl).then((response) => {
                    this.chartstart = response.data;
                    this.$emit('invalidategantt');
                });
            }

        }
    }
</script>
