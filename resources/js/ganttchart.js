export default function(baseurl, ctx, canvas, apiurl, errorobject) {
    // this has to be refactored of course, probably into a templateless Vue component

    this.baseurl = baseurl;
    this.ctx = ctx;
    this.canvas = canvas;
    this.apiurl = apiurl;
    this.errorobject = errorobject;

    this.xmax = 40;
    this.leftBorder = 400;
    this.upperBorder = 23;
    this.h = 20;
    this.l = 20;

    this.Bar = function(x = null, y = null, l = null) {
        this.x = x;
        this.y = y;
        this.l = l;
        this.reset = function() {
            this.x = this.y = this.l = null;
        }
    }

    this.old = new this.Bar();
    this.oldMove = new this.Bar();

    this.redraw = function (activities) {
        //has to be called when something of the data changes
        if (this.ctx) {
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.buildMatrix(this.resources, this.rules, activities);
            this.draw(this.resources, this.rules, activities);
        }
    }

    this.initGrid = function (resources, rules) {
        this.resLength = resources.length;
        this.rulesLength = rules.length;
        this.resBorder = this.upperBorder + this.resLength * this.h;
        this.rulesBorder = this.resBorder + this.rulesLength * this.h;
    };

    this.initTime = function (start) {
        Date.prototype.addHours = function (h) {
            this.setHours(this.getHours() + h);
            return this;
        };
        Date.prototype.addDays = function(days) {
            this.setDate(this.getDate() + parseInt(days));
            return this;
        };
        let timePointer = start;
        this.startString = (timePointer.getYear() + 1900) + "-"
            + (timePointer.getMonth() + 1)
            + "-" + timePointer.getDate();
        this.days = new Array(this.xmax);
        this.hours = new Array(this.xmax);
        for (let i = 0; i < this.xmax; i++) {
            this.days[i] = timePointer.getDate();
            this.hours[i] = timePointer.getHours();
            timePointer.addDays(1);
        }
    };

    this.buildMatrix = function(resources, rules, activities) {
        //computes which field of the grid is oppupied by which activity if any
        //further it computes the next free activity id
        let activityGrid = [];
        let ruleGrid = [];
        let activitiesTemp = [];
        let ids = [];
        activities.forEach(function (activity) {
            activity['color'] = 'rgb(' + activity.r + ',' + activity.g + ',' + activity.b + ')';
            if (activityGrid[activity.y] == undefined) {
                activityGrid[activity.y] = [];
            }
            for (let i = activity.x; i < activity.x + activity.duration; i++) {
                activityGrid[activity.y][i] = activity.id;
            }
            activitiesTemp[activity.id] = activity;
            ids.push(activity.id);
        })
        ids.sort((a, b) => a - b);
        this.freeId = ids.length + 1;
        for (let i = 0; i< ids.length; i++) {
            if (ids[i] > i + 1) {
                this.freeId = i + 1;
                break;
            }
        }
        for (let j = 0; j < rules.length; j++) {
            rules[j].color = 'rgb(' + rules[j].r + ',' + rules[j].g + ',' + rules[j].b + ')';
            if (ruleGrid[j] == undefined) {
                ruleGrid[j] = [];
            }
            for (let i = 0; i < rules[j].duration; i++) {
                ruleGrid[j][i] = j;
            }
        }
        this.activityGrid = activityGrid;
        this.ruleGrid = ruleGrid;
        this.activities = activitiesTemp;
        this.rules = rules;
        this.resources = resources;
    }

    this.draw = function (resources, rules, activities) {
        let ctx = this.ctx;
        let xmax = this.xmax;
        let l = this.l;
        let h = this.h;
        let leftBorder = this.leftBorder;
        let upperBorder = this.upperBorder;
        let resLength = this.resLength;
        let rulesLength = this.rulesLength;
        let resBorder = this.resBorder;
        let rulesBorder = this.rulesBorder;
        ctx.font = "13px Nunito";

        //the timeline
        ctx.fillText(this.startString, 6, h - 2);
        for (let i = 0; i < this.days.length; i++) {
            ctx.fillText(this.days[i], leftBorder + i * l + 3, h - 2);
        }

        //all the horizontal and vertical lines
        ctx.beginPath();
        ctx.strokeStyle = "#d0d0d0";
        for (let i = 0; i < xmax; i++) {
            ctx.moveTo(leftBorder + l * i, 0);
            ctx.lineTo(leftBorder + l * i, rulesBorder);
        }
        ctx.stroke();
        ctx.beginPath();
        ctx.strokeStyle = "#000000";
        for (let i = 1; i < resLength + rulesLength; i++) {
            ctx.moveTo(0, upperBorder + h * i);
            ctx.lineTo(leftBorder + l * xmax, upperBorder + h * i);
        }
        ctx.stroke();
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(0, 2);
        ctx.lineTo(leftBorder + l * xmax, 2);
        ctx.moveTo(0, upperBorder);
        ctx.lineTo(leftBorder + l * xmax, upperBorder);
        ctx.moveTo(0, resBorder);
        ctx.lineTo(leftBorder + l * xmax, resBorder);
        ctx.moveTo(0, rulesBorder);
        ctx.lineTo(leftBorder + l * xmax, rulesBorder);
        ctx.moveTo(2, 0);
        ctx.lineTo(2, rulesBorder);
        ctx.moveTo(leftBorder, 0);
        ctx.lineTo(leftBorder, rulesBorder);
        ctx.moveTo(leftBorder + l * xmax, 0);
        ctx.lineTo(leftBorder + l * xmax, rulesBorder);
        ctx.stroke();
        ctx.lineWidth = 0.5;

        //the rules in the lower part of the grid
        for (let i = 0; i < rules.length; i++) {
            ctx.fillText(rules[i].name, 6, resBorder + (i + 1) * h - 4);
            this.drawBar(new this.Bar(0, i + resources.length, rules[i].duration), rules[i].color);
        }

        //the resource names in the upper left part of the grid
        for (let i = 0; i < resources.length; i++) {
            ctx.fillText(resources[i].name, 6, upperBorder + (i + 1) * h - 4);
        }

        //the activities in the upper right (main) part of the grid
        for (let i = 0; i < activities.length; i++) {
            let x = activities[i].x;
            let duration = activities[i].duration;
            if (activities[i].x < 0) {
                duration += x;
                x = 0;
            }
            if (activities[i].x + activities[i].duration > this.xmax)
                continue;
            this.drawBar(new this.Bar(x, activities[i].y, duration), activities[i].color);
        }
    };

    this.drawBar = function (bar, color, move = false) {
        let ctx = this.ctx;
        let lox = this.leftBorder + bar.x * this.l;
        let loy = this.upperBorder + bar.y * this.h + 1;
        let length = this.l * bar.l;
        let width = this.h - 2;
        let oldFillStyle = ctx.fillStyle;
        ctx.fillStyle = color;
        ctx.fillRect(lox, loy, length, width);
        ctx.fillStyle = oldFillStyle;
        if (move) {
            ctx.rect(lox, loy, length, width);
            let oldStrokeStyle = ctx.strokeStyle;
            let oldLineWidth = ctx.lineWidth;
            ctx.strokeStyle = 'black';
            ctx.lineWidth = 4;
            ctx.stroke();
            ctx.strokeStyle = oldStrokeStyle;
            ctx.lineWidth = oldLineWidth;
        }
    };

    this.isFree = function (bar) {
        if (bar.x < 0 || bar.y < 0 || bar.y >= this.resLength || bar.x + bar.l >= this.xmax) {
            return false;
        }
        if (this.activityGrid[bar.y] == undefined) {
            return true;
        }
        for (let i = bar.x; i < bar.x + bar.l; i++) {
            if (this.activityGrid[bar.y][i] != undefined && this.activityGrid[bar.y][i] != this.pressedActivity) {
                return false;
            }
        }
        return true;
    };

    this.matrixMouseDown = function(x, y) {
        this.pressedActivity = null;
        this.old.reset();
        if (this.activityGrid[y] == undefined) {
            return;
        } else if (this.activityGrid[y][x] == undefined) {
            return;
        }
        else {
            this.pressedActivity = this.activityGrid[y][x];
            let pressedActivityObject = this.activities[this.pressedActivity];
            this.pressedOffset = x - pressedActivityObject.x;
            this.old.x = pressedActivityObject.x;
            this.old.y = pressedActivityObject.y;
            this.old.l = pressedActivityObject.duration;
        }
    }

    this.matrixCreateMoveUp = function (x, y) {
        var activitiesTemp;
        if (this.pressedActivity != null) {
            activitiesTemp = this.moveActivity(x, y);
            this.pressedActivity = null;
        } else if (this.pressedRule != null) {
            activitiesTemp = this.createActivity(x, y);
            this.pressedRule = null;
        }
        this.redraw(activitiesTemp);
    }

    this.matrixGrowShrinkUp = function (x, y) {
        let activitiesTemp = this.growActivity(x, y);
        this.pressedActivity = null;
        this.redraw(activitiesTemp);
    }

    this.matrixCreateMoveMove = function (x, y) {
        let target = new this.Bar(
            x - this.pressedOffset,
            y,
            this.pressedActivity ?
                this.activities[this.pressedActivity].duration : this.rules[this.pressedRule].duration);
        let targetColor = this.pressedActivity ?
            this.activities[this.pressedActivity].color :
            this.rules[this.pressedRule].color;
        if (target.x == this.oldMove.x && target.y == this.oldMove.y) {
            return;
        }
        this.oldMove.x = target.x;
        this.oldMove.y = target.y;
        let activitiesTemp = [];
        this.activities.forEach(function (activity) {
            activitiesTemp.push(activity);
        })
        this.redraw(activitiesTemp);
        if (this.ctx) {
            this.drawBar(target, targetColor, true);
        }
    }

    this.matrixGrowShrinkMove = function (x, y) {
        let target = new this.Bar(
            this.activities[this.pressedActivity].x,
            this.activities[this.pressedActivity].y,
            this.activities[this.pressedActivity].duration + x -
                this.activities[this.pressedActivity].x - this.pressedOffset);
        let targetColor = this.pressedActivity ?
            this.activities[this.pressedActivity].color :
            this.rules[this.pressedRule].color;
        if (target.l == this.oldMove.l) {
            return;
        }
        this.oldMove.l = target.l;
        let activitiesTemp = [];
        this.activities.forEach(function (activity) {
            activitiesTemp.push(activity);
        })
        this.redraw(activitiesTemp);
        if (this.ctx) {
            this.drawBar(target, targetColor, true);
        }
    }

    this.technologyMouseDown = function (x, y) {
        this.pressedRule = null;
        if (this.ruleGrid[y] == undefined) {
            return;
        } else if (this.ruleGrid[y][x] == undefined) {
            return;
        }
        this.pressedRule = this.ruleGrid[y][x];
        this.pressedOffset = x;
    }

    this.matrixRemove = function(x, y) {
        if (this.activityGrid[y] == undefined) {
            return;
        } else if (this.activityGrid[y][x] == undefined) {
            return;
        }
        else {
            this.pressedActivity = this.activityGrid[y][x];
            let activitiesTemp = [];
            this.activities.forEach(function (activity) {
                if (this.pressedActivity != activity.id) {
                    activitiesTemp.push(activity);
                }
            }, this)
            let uri = this.apiurl(this.baseurl, 'activity', this.pressedActivity);

            //no .then block necessary because it can just be saved asynchronousely in the background
            axios.delete(uri)
                .catch(reason => {
                    this.errorobject.errors = [['An error occured while trying to delete the actitivity.'],
                        [' Please reload the page and try again.']];
                    this.errorobject.servererror = true;
                });
            this.pressedActivity = null;
            this.redraw(activitiesTemp);
        }
    }

    this.moveActivity = function(x, y) {
        let target = new this.Bar(x - this.pressedOffset, y, this.activities[this.pressedActivity].duration);
        if (this.isFree(target)) {
            this.activities[this.pressedActivity].x = target.x;
            this.activities[this.pressedActivity].y = target.y;
            this.activities[this.pressedActivity].resourceId = this.resources[y].id;
            let uri = this.apiurl(this.baseurl, 'activity', this.pressedActivity);

            //no .then block necessary because it can just be saved asynchronousely in the background
            axios.put(uri, this.activities[this.pressedActivity])
                .catch(reason => {
                    this.errorobject.errors = [['An error occured while trying to save the changed actitivity.'],
                        ['Please reload the page and try again.']];
                    this.errorobject.servererror = true;
                });
        } else {
            this.activities[this.pressedActivity].x = this.old.x;
            this.activities[this.pressedActivity].y = this.old.y;
        }
        this.old.reset();
        let activitiesTemp = [];
        this.activities.forEach(function (activity) {
            activitiesTemp.push(activity);
        })
        return activitiesTemp;
    }

    this.growActivity = function(x, y) {
        let target = new this.Bar(
            this.activities[this.pressedActivity].x,
            this.activities[this.pressedActivity].y,
            this.activities[this.pressedActivity].duration + x -
                this.activities[this.pressedActivity].x - this.pressedOffset
        );
        if (this.isFree(target)) {
            this.activities[this.pressedActivity].duration = target.l;
            this.activities[this.pressedActivity].resourceId = this.resources[y].id;
            let uri = this.apiurl(this.baseurl, 'activity', this.activities[this.pressedActivity].id);

            //no .then block necessary because it can just be saved asynchronousely in the background
            axios.put(uri, this.activities[this.pressedActivity])
                .catch(reason => {
                    this.errorobject.errors = [['An error occured while trying to save the changed actitivity.'],
                        ['Please reload the page and try again.']];
                    this.errorobject.servererror = true;
                });
        } else {
            this.activities[this.pressedActivity].duration = this.old.l;
        }
        this.old.reset();
        let activitiesTemp = [];
        this.activities.forEach(function (activity) {
            activitiesTemp.push(activity);
        })
        return activitiesTemp;
    }

    this.createActivity = function(x, y) {
        let activityNew = new Object();
        let target = new this.Bar(x - this.pressedOffset, y, this.rules[this.pressedRule].duration);
        let activitiesTemp = [];
        this.activities.forEach(function (activity) {
            activitiesTemp.push(activity);
        })
        if (this.isFree(target)) {
            activityNew.x = target.x;
            activityNew.y = target.y;
            activityNew.duration = target.l;
            activityNew.id = this.freeId;
            activityNew.r = this.rules[this.pressedRule].r;
            activityNew.g = this.rules[this.pressedRule].g;
            activityNew.b = this.rules[this.pressedRule].b;
            activityNew.resourceId = this.resources[y].id;
            activityNew.ruleId = this.rules[this.pressedRule].id;
            activitiesTemp.push(activityNew);
            let uri = this.apiurl(this.baseurl, 'activity');

            //no .then block necessary because it can just be saved asynchronousely in the background
            axios.post(uri, activityNew)
                .catch(reason => {
                    this.errorobject.errors = [['An error occured while trying to save the created actitivity.'],
                        ['Please reload the page and try again.']];
                    this.errorobject.servererror = true;
                });
        }
        return activitiesTemp;
    }

}
