<template>
    <!-- the master component which contains respectively starts the other stuff -->
    <div class="container">
        <div class="row">
            <!-- the master data editors which open on demand -->
            <div v-bind:class="{'col-xl-6': startedit_show}">
                <startedit-component
                    v-if="startedit_show"
                    @invalidategantt="onInvalidate"
                    v-bind:starturlprop="starturl"
                >
                </startedit-component>
            </div>
            <div v-bind:class="{'col-xl-6': resedit_show}">
                <resource-component
                    v-if="resedit_show"
                    @resposcalled="onResPosCalled"
                    @invalidategantt="onInvalidate"
                    v-bind:resurlprop="resurl"
                    v-bind:resourceTermprop="resource_term"
                >
                </resource-component>
            </div>
            <div v-bind:class="{'col-xl-6': ruleedit_show}">
                <rule-component
                    v-if="ruleedit_show"
                    @invalidategantt="onInvalidate"
                    @ruleposcalled="onRulePosCalled"
                    v-bind:ruleurlprop="ruleurl"
                    v-bind:ruleTermprop="rule_term"
                >
                </rule-component>
            </div>
            <div v-bind:class="{'col-xl-6': respos_show}">
                <respos-component
                    v-if="respos_show"
                    @invalidategantt="onInvalidate"
                    v-bind:resposurlprop="resposurl"
                    v-bind:resourceTermprop="resource_term"
                >
                </respos-component>
            </div>
            <div v-bind:class="{'col-xl-6': rulepos_show}">
                <rulepos-component
                    v-if="rulepos_show"
                    @invalidategantt="onInvalidate"
                    v-bind:ruleposurlprop="ruleposurl"
                    v-bind:ruleTermprop="rule_term"
                >
                </rulepos-component>
            </div>
            <div class="col-xl-2">
                <!-- the default view which is pushed to the ride if a master data editor has been opened, but remains
                visible -->
                <ganttmenu-component
                    v-bind:navWidth="navWidth"
                    @modechanged="onModeChange"
                >
                </ganttmenu-component>
                <ganttchart-component
                    v-bind:gantt-outdated="ganttchartOutdated"
                    v-bind:actual-mode="actual_mode"
                    @ganttloaded="ganttchartOutdated=false"
                    @initialized="onInitialized"
                    @starteditorCalled="onStarteditor"
                    @reseditorCalled="onReseditor"
                    @ruleeditorCalled="onRuleeditor"
                >
                </ganttchart-component>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                actual_mode: 'Create/Move',
                startedit_show: false,
                resedit_show: false,
                ruleedit_show: false,
                respos_show: false,
                rulepos_show: false,
                resurl: null,
                ruleurl: null,
                starturl: null,
                resposurl: null,
                ruleposurl: null,
                resource_term: 'Resource',
                rule_term: 'Rule',
                navWidth: '800px',
                ganttchartOutdated: false,
            }
        },

        methods: {
            onModeChange: function(newMode) {
                this.actual_mode = newMode;
            },

            onInitialized: function(initData) {
                this.resource_term = initData[0];
                this.rule_term = initData[1];
                this.navWidth = initData[2];
            },

            onStarteditor: function(url) {
                this.starturl = url;
                this.startedit_show = true;
            },

            onReseditor: function(url) {
                this.resurl = url;
                this.resedit_show = true;
            },

            onRuleeditor: function(url) {
                this.ruleurl = url;
                this.ruleedit_show = true;
            },

            onResPosCalled: function (uri) {
                this.resposurl = uri;
                this.resedit_show = false;
                this.respos_show = true;
            },

            onRulePosCalled: function (uri) {
                this.ruleposurl = uri;
                this.ruleedit_show = false;
                this.rulepos_show = true;
            },

            hideMasterDataEditors: function() {
                this.resedit_show = false;
                this.ruleedit_show = false;
                this.startedit_show = false;
                this.respos_show = false;
                this.rulepos_show = false;
            },

            onInvalidate: function() {
                this.hideMasterDataEditors();
                this.ganttchartOutdated = true;
            }
        }
    }
</script>
