<template>
    <!-- the menu bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light" v-bind:style="{width: navWidth}">
        <a class="navbar-brand" href="#">Resource Planning Board</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" v-bind:class="{'active': actual_mode == 'Create/Move'}">
                    <a class="nav-link" href="#" v-on:click="modechange('Create/Move')">Create/Move</a>
                </li>
                <li class="nav-item" v-bind:class="{'active': actual_mode == 'Remove'}">
                    <a class="nav-link" href="#" v-on:click="modechange('Remove')">Remove</a>
                </li>
                <li class="nav-item"  v-bind:class="{'active': actual_mode == 'Grow/Shrink'}">
                    <a class="nav-link" href="#" v-on:click="modechange('Grow/Shrink')">Grow/Shrink</a>
                </li>
            </ul>
            <span class="navbar-text">The actual mode is:
                    <span
                        v-bind:class="[actual_mode == 'Remove' ? 'text-danger' : 'text-dark']">
                        {{actual_mode}}
                    </span>
                </span>
        </div>
    </nav>
</template>

<script>

    export default {
        data() {
            return {
                actual_mode: 'Create/Move'
            }
        },

        props: ['navWidth'],

        methods: {
            modechange: function (newMode) {
                this.actual_mode = newMode;
                this.$emit('modechanged', newMode);
            }
        }
    }
</script>
