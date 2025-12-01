require('./bootstrap');

import { createApp } from 'vue';
import axios from 'axios';

// Komponenten importieren
import ResourceComponent from './components/ResourceComponent.vue';
import RuleComponent from './components/RuleComponent.vue';
import ResPosComponent from './components/ResPosComponent.vue';
import RulePosComponent from './components/RulePosComponent.vue';
import StartEditComponent from './components/StartEditComponent.vue';
import GanttchartComponent from './components/GanttchartComponent.vue';
import GanttmenuComponent from './components/GanttmenuComponent.vue';
import PlanningBoardComponent from './components/PlanningBoardComponent.vue';
import ServerErrorComponent from './components/ServerErrorComponent.vue';

const app = createApp({});

// axios global verfügbar machen
app.config.globalProperties.$axios = axios;

// Komponenten registrieren
app.component('resource-component', ResourceComponent);
app.component('rule-component', RuleComponent);
app.component('respos-component', ResPosComponent);
app.component('rulepos-component', RulePosComponent);
app.component('startedit-component', StartEditComponent);
app.component('ganttchart-component', GanttchartComponent);
app.component('ganttmenu-component', GanttmenuComponent);
app.component('planningboard-component', PlanningBoardComponent);
app.component('servererror-component', ServerErrorComponent);

app.mount('#app');
