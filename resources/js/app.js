import jQuery from "jquery";
window.$ = jQuery;

import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { dismiss } from "./general/dismiss.js";
dismiss();