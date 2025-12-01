<template>
    <div>
        <h4>{{ruleTerm}}</h4>
        <form @submit.prevent="updateRule" @reset.prevent="resetRule">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" class="form-control" v-model="rule.name">
                        <label>Length:</label>
                        <input type="text" class="form-control" v-model="rule.duration">
                        <label>Color (RGB):</label>
                        <input type="text" class="form-control" v-model="rule.r">
                        <input type="text" class="form-control" v-model="rule.g">
                        <input type="text" class="form-control" v-model="rule.b">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Notes:</label>
                        <textarea class="form-control" v-model="rule.note" rows="5"></textarea>
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
                        <button type="submit" class="btn btn-primary mr-1">Update</button>
                        <button type="reset" class="btn btn-primary mr-1">Reset</button>
                        <button class="btn btn-danger mr-1" @click.prevent="onDelete(rule.id)">Delete</button>
                        <button class="btn btn-success mr-1" @click.prevent="onCreate">Create</button>
                        <button class="btn btn-success mr-1" @click.prevent="onPos">Position</button>
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
                rule: {},
                message: 'Edit',
                show: 1,
                ruleurl: this.ruleurlprop,
                ruleTerm: this.ruleTermprop,
                servererror: false,
                errors: {}
            }
        },
        created() {
            this.$axios.get(this.ruleurl).then((response) => {
                this.rule = response.data;
            });
        },
        props: ['ruleurlprop', 'ruleTermprop'],
        methods: {
            updateRule: function() {
                let uri = this.ruleurl;
                this.$axios.put(uri, this.rule)
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

            onDelete: function(id) {
                let uri = this.ruleurl;
                this.$axios.delete(uri).then((response) => {
                    this.$emit('invalidategantt');
                });
            },

            resetRule: function() {
                this.$axios.get(this.ruleurl).then((response) => {
                    this.resource = response.data;
                    this.$emit('invalidategantt');
                });
            },

            onCreate: function(id) {
                let uri = this.ruleurl.replace(/\/[0-9]+$/, '');
                this.$axios.post(uri, this.rule)
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
                let uri = this.ruleurl.replace(/rule\/[0-9]+$/, 'rule-position');
                this.$emit('ruleposcalled', uri);
            }
        }
    }
</script>
