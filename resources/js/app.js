import jQuery from "jquery";
window.$ = jQuery;

import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { dismiss } from "./general/dismiss.js";
dismiss();
import { designerFormSubmission } from "./designer/form-submission.js";
designerFormSubmission();
import { dashboard } from "./layouts/dashboard.js";
dashboard();
import { cellEdit } from "./parts/cell-edit.js";
cellEdit();
import { modals } from "./general/modals.js";
modals();
import { processTypes } from "./general/process-types.js";
processTypes();