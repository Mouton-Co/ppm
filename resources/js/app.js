import jQuery from "jquery";
window.$ = jQuery;

import "./bootstrap";

import Alpine from "alpinejs";

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
import { cocGenerate } from "./projects/coc-generation.js";
cocGenerate();
import { cellEditProject } from "./projects/cell-edit.js";
cellEditProject();
import { emailTriggerForm } from "./email-triggers/form.js";
emailTriggerForm();
import { filters } from "./general/filters.js";
filters();
import { roleTable } from "./roles/role-table.js";
roleTable();
import { roleForm } from "./roles/role-form.js";
roleForm();
import { replacement } from "./submissions/replacement.js";
replacement();
import { settings } from "./general/settings.js";
settings();
import { warehouse } from "./parts/warehouse.js";
warehouse();
