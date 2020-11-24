/**
 * Put this into a script and use someting like tampermonkey
 */
// ==UserScript==
// @name         Dark Stack
// @namespace
// @version      0.1
// @description  Make Stack Dark!
// @author       You
// @match        https://UserName.stackstorage.com/*
// @grant        none
// ==/UserScript==

(function() {
    'use strict';

    let style = document.createElement('style');
    style.innerHTML=`
.header, 
.footer__help, 
.footer__usage a {
    display: none;
}

body, 
.content, 
.node-drag__container, 
.node--selected, 
.infobar, 
.sidebar__content {
    background-color: #252525; color: #EEEEEE;
}

.breadcrumb__text, 
.node__column--modified, 
.node__extension, 
.navbar__link, 
.navbar__link svg path, 
.navbar__link svg polygon[fill] {
    color: #EEEEEE; fill: #EEEEEE !important;
}

.navbar__link:hover, 
.navbar__link:hover .navbar__label, 
.navbar__link:hover svg path, 
.navbar__link:hover svg polygon[fill], 
.navbar--primary .navbar__item--active .navbar__link, 
.navbar--primary .navbar__item:hover .navbar__link {
    color: #a9a7ff; fill: #a9a7ff !important;
}

.navbar, 
.actions, 
.footer__usage {
    background-color: #343434;
}

.navbar__item {
    border: 0;
}

.nodes {
    background-color: #343434;
}

.node {
    border-bottom: 1px solid #6d6d6d;
}

.sidebar__content img {
    filter: brightness(1.75);
}

.node__column--light, .node__extension {
    opacity: 1;
}

::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: #252525;
}

::-webkit-scrollbar-thumb {
    background: #343434;
}

::-webkit-scrollbar-thumb:hover {
    background: #343434;
}`;
    document.head.appendChild(style);
})();