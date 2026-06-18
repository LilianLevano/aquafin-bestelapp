import { saveToCache } from "./utilities";
import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.saveToCache = saveToCache;

Alpine.start();
window.dispatchEvent(new Event("saveToCacheReady"));
