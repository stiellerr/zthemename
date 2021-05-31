/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";
import domReady from "@wordpress/dom-ready";

/**
 * Internal dependencies
 */
import * as carousel from "./carousel";

import "./index.scss";

/**
 * Function to register an individual block.
 *
 * @param {Object} block The block to be registered.
 *
 */
const registerBlock = (block) => {
    if (!block) {
        return;
    }
    const { metadata, settings, name } = block;

    registerBlockType(name, { ...metadata, ...settings });
};

/**
 * Function to get all the custom blocks in an array.
 */
const getCustomBlocks = () => [carousel];

/**
 * Function to register custom blocks.
 *
 * @param {Array} blocks An optional array of the custom blocks being registered.
 *
 */
const registerCustomBlocks = (blocks = getCustomBlocks()) => {
    blocks.forEach(registerBlock);
};

domReady(() => {
    registerCustomBlocks();
});
