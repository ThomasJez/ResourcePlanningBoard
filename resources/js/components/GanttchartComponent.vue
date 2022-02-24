<template>
    <div>
        <canvas
            ref="board"
            width="1600"
            height="600"
            @mousedown.prevent="mousedown"
            @mouseup.prevent="mouseup"
            @mousemove.prevent="mousemove"
        >
        </canvas>
        <servererror-component
            v-if="errorobject.servererror"
            v-bind:errorsprop="errorobject.errors"
        >
        </servererror-component>
    </div>
</template>

<script>
    import GanttChart from '../ganttchart';

    export default {
        data() {
            return {
                baseurl: null,
                gC: null,
                canvas: null,
                ctx: null,
                navWidth: '800px', //width of the menu, after loading of the ganttchart, this value will be adapted
                plan: [],
                errorobject: {
                    servererror: false,
                    errors: []
                }
            }
        },

        watch: {
            ganttOutdated: function(val, oldVal) {
                if (this.ganttOutdated == true) {
                    this.reloadGantt();
                }
            }
        },

        mounted() {
            this.canvas = this.$refs.board;
            this.baseurl = this.canvas.baseURI;
            this.ctx = this.canvas.getContext('2d');
            this.gC = new GanttChart(this.baseurl, this.ctx, this.canvas, this.apiUrl, this.errorobject);
            var navWidth = (this.gC.leftBorder + this.gC.xmax * this.gC.l).toString() + 'px';

            this.loadGantt('init', navWidth);
        },

        props: ['gantt-outdated', 'actual-mode'],

        methods: {
            loadGantt: function(calledFrom, navWidth = null) {
                let uri = this.apiUrl(this.baseurl, 'getall');
                axios.get(uri)
                    .then(response => {
                        this.plan = response.data;

                        this.getAndDraw(this.plan, this.axios);
                        if (calledFrom == 'init') {
                            this.$emit(
                                'initialized',
                                [this.plan.resource_term, this.plan.rule_term, navWidth]
                            );
                        } else {
                            this.$emit('ganttloaded');
                        }
                    })
                    .catch(err => {
                        this.errorobject.errors = [['An error occured while trying to load the ganttchart']];
                        this.errorobject.servererror = true;
                    })
            },

            reloadGantt: function() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                this.loadGantt('reload');
            },

            apiUrl: function(baseurl, routename, param=null) {
                baseurl += '/'
                var url = baseurl.replace(/\?XDEBUG.*/, '').replace('#', '');
                url += 'api/' + routename;
                if (param) {
                    url += '/' + param;
                }
                url = url.replace('//api', '/api')
                return url;
            },

            getAndDraw: function (data, axios) {
                var start = new Date();
                start.setTime(data.start * 1000);
                if (this.ctx) {
                    this.gC.initGrid(data.resources, data.rules);
                    this.gC.initTime(start);
                    this.gC.buildMatrix(data.resources, data.rules, data.activities);
                    this.gC.draw(data.resources, data.rules, data.activities);
                }
            },

            mousedown: function (e) {
                // computes the grid coordinates and distributes the action
                let p = this.canvas.getBoundingClientRect();
                let x = Math.floor((e.pageX - p.left - this.gC.leftBorder) / this.gC.l);
                let y = Math.floor((e.pageY - p.top - this.gC.upperBorder) / this.gC.h);
                let resLength = this.gC.resLength;
                let rulesLength = this.gC.rulesLength;
                if (x >= this.gC.xmax || y >= resLength + rulesLength) {
                    return;
                } else if (x < 0 && y < 0) {
                    //the startdate is clicked the respective editor opens
                    let url = this.apiUrl(this.baseurl, 'start');
                    this.$emit('starteditorCalled', url)
                    return;
                } else if (x < 0 && y < resLength && !this.ruleedit_show) {
                    //one of the resources is clicked, the resource editor opens
                    let url = this.apiUrl(this.baseurl, 'resource', this.gC.resources[y].id);
                    this.$emit('reseditorCalled', url)
                    return;
                } else if (x < 0 && y >= resLength && !this.resedit_show) {
                    //one of the rules is clicked, the rules editor opens
                    let url = this.apiUrl(this.baseurl, 'rule', this.gC.rules[y - resLength].id)
                    this.$emit('ruleeditorCalled', url)
                    return;
                } else if (y < resLength) {
                    //the upper part of the real grid is clicked: we hand over the action to the ganttchart object
                    if (['Create/Move', 'Grow/Shrink'].includes(this.actualMode)) {
                        this.gC.matrixMouseDown(x, y);
                    } else if (this.actualMode == 'Remove') {
                        this.gC.matrixRemove(x, y);
                    }
                } else {
                    //the lower part of the real grid is clicked: we hand over the action to the ganttchart object
                    this.gC.technologyMouseDown(x, y - resLength);
                }
            },

            mouseup: function (e) {
                // same as with moudsedown: computes the grid coordinates and distributes the action
                if (!['Create/Move', 'Grow/Shrink'].includes(this.actualMode)) {
                    return;
                }
                if (this.gC.pressedActivity == null && this.gC.pressedRule == null) {
                    return;
                }
                let p = this.canvas.getBoundingClientRect();
                let x = Math.floor((e.pageX - p.left - this.gC.leftBorder) / this.gC.l);
                let y = Math.floor((e.pageY - p.top - this.gC.upperBorder) / this.gC.h);
                if (this.actualMode == 'Create/Move') {
                    this.gC.matrixCreateMoveUp(x, y)
                } else if (this.actualMode == 'Grow/Shrink') {
                    if (this.gC.pressedActivity == null) {
                        return;
                    }
                    this.gC.matrixGrowShrinkUp(x, y);
                }
            },

            mousemove: function (e) {
                // same as with moudsedown: computes the grid coordinates and distributes the action
                if (!['Create/Move', 'Grow/Shrink'].includes(this.actualMode)) {
                    return;
                }
                if (this.gC.pressedActivity == null && this.gC.pressedRule == null) {
                    return;
                }
                let p = this.canvas.getBoundingClientRect();
                let x = Math.floor((e.pageX - p.left - this.gC.leftBorder) / this.gC.l);
                let y = Math.floor((e.pageY - p.top - this.gC.upperBorder) / this.gC.h);
                if (this.actualMode == 'Create/Move') {
                    this.gC.matrixCreateMoveMove(x, y);
                } else if (this.actualMode == 'Grow/Shrink') {
                    if (this.gC.pressedActivity == null) {
                        return;
                    }
                    this.gC.matrixGrowShrinkMove(x, y);
                }
            },

        }
    }
</script>
