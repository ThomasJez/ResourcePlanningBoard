<template>
    <div>
        <h4>sort {{ruleTerm}}</h4>
        <form @submit.prevent="updatePos" @reset.prevent="resetPos">
            <div class = "form-row"  v-for="rule in rules">
                <div class="col">
                    <label>{{ rule.name }}</label>
                </div>
                <div class="col">
                    <input type="text" :name="'rulepos' + rule.id" class="form-control" v-model="rule.pos">
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
                rules: {},
                message: 'Edit',
                show: 1,
                ruleposurl: this.ruleposurlprop,
                ruleTerm: this.ruleTermprop,
                servererror: false,
                errors: {}
            }
        },
        created() {
            this.$axios.get(this.ruleposurl).then((response) => {
                this.rules = response.data;
            });
        },
        props: ['ruleposurlprop', 'ruleTermprop'],
        methods: {
            updatePos: function() {
                let uri = this.ruleposurl;
                this.$axios.post(uri, this.rules)
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
