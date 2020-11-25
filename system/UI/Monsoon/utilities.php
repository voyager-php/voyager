<?php

namespace Voyager\UI\Monsoon {

    /**
     * Box-Sizing Utilities.
     */

    Util::set('box-border')->prop('box-sizing', 'border-box');
    Util::set('box-content')->prop('box-content', 'content-box');

    /**
     * Display Utilities.
     */

    Util::set('block')->prop('display', 'block');
    Util::set('inline-block')->prop('display', 'inline-block');
    Util::set('inline')->prop('display', 'inline');
    Util::set('flex')->prop('display', 'flex');
    Util::set('inline-flex')->prop('display', 'inline-flex');
    Util::set('table')->prop('display', 'table');
    Util::set('table-caption')->prop('display', 'table-caption');
    Util::set('table-cell')->prop('display', 'table-cell');
    Util::set('table-column')->prop('display', 'table-column');
    Util::set('table-column-group')->prop('display', 'table-column-group');
    Util::set('table-footer-group')->prop('display', 'table-footer-group');
    Util::set('table-header-group')->prop('display', 'table-header-group');
    Util::set('table-row-group')->prop('display', 'table-row-group');
    Util::set('table-row')->prop('display', 'table-row');
    Util::set('flow-root')->prop('display', 'flow-root');
    Util::set('grid')->prop('display', 'grid');
    Util::set('inline-grid')->prop('display', 'inline-grid');
    Util::set('contents')->prop('display', 'contents');
    Util::set('hidden')->prop('display', 'none');

    /**
     * Floats Utilities.
     */

    Util::set('float-right')->prop('float', 'right');
    Util::set('float-left')->prop('float', 'left');
    Util::set('float-none')->prop('float', 'none');

    /**
     * Clear Utilities.
     */

    Util::set('clear-left')->prop('clear', 'left');
    Util::set('clear-right')->prop('clear', 'right');
    Util::set('clear-both')->prop('clear', 'both');
    Util::set('clear-none')->prop('clear', 'none');

    /**
     * Object-Fit Utilities.
     */

    Util::set('object-contain')->prop('object-fit', 'contain');
    Util::set('object-cover')->prop('object-fit', 'cover');
    Util::set('object-fill')->prop('object-fit', 'fill');
    Util::set('object-none')->prop('object-fit', 'none');
    Util::set('object-scale-down')->prop('object-fit', 'scale-down');

    /**
     * Object Position Utilities.
     */

    Util::set('object-bottom')->prop('object-position', 'bottom');
    Util::set('object-center')->prop('object-position', 'center');
    Util::set('object-left')->prop('object-position', 'left');
    Util::set('object-left-bottom')->prop('object-position', 'left-bottom');
    Util::set('object-left-top')->prop('object-position', 'left-top');
    Util::set('object-right')->prop('object-position', 'right');
    Util::set('object-right-bottom')->prop('object-position', 'right-bottom');
    Util::set('object-right-top')->prop('object-position', 'right-top');
    Util::set('object-top')->prop('object-position', 'top');

    /**
     * Overflow Utilities.
     */

    Util::set('overflow-auto')->prop('overflow', 'auto');
    Util::set('overflow-hidden')->prop('overflow', 'hidden');
    Util::set('overflow-visible')->prop('overflow', 'visible');
    Util::set('overflow-scroll')->prop('overflow', 'scroll');
    Util::set('overflow-x-auto')->prop('overflow-x', 'auto');
    Util::set('overflow-y-auto')->prop('overflow-y', 'auto');
    Util::set('overflow-x-hidden')->prop('overflow-x', 'hidden');
    Util::set('overflow-y-hidden')->prop('overflow-y', 'hidden');
    Util::set('overflow-x-visible')->prop('overflow-x', 'visible');
    Util::set('overflow-y-scroll')->prop('overflow-y', 'scroll');
    Util::set('overflow-x-scroll')->prop('overflow-x', 'scroll');
    Util::set('scrolling-touch')->prop('-webkit-overflow-scrolling', 'touch');
    Util::set('scrolling-auto')->prop('-webkit-overflow-scrolling', 'auto');

    /**
     * Overscroll Behavior Utilities.
     */

    Util::set('overscroll-auto')->prop('overscroll-behavior', 'auto');
    Util::set('overscroll-contain')->prop('overscroll-behavior', 'contain');
    Util::set('overscroll-none')->prop('overscroll-behavior', 'none');
    Util::set('overscroll-y-auto')->prop('overscroll-behavior-y', 'auto');
    Util::set('overscroll-y-contain')->prop('overscroll-behavior-y', 'contain');
    Util::set('overscroll-y-none')->prop('overscroll-behavior-y', 'none');
    Util::set('overscroll-x-auto')->prop('overscroll-behavior-x', 'auto');
    Util::set('overscroll-x-contain')->prop('overscroll-behavior-x', 'contain');
    Util::set('overscroll-x-none')->prop('overscroll-behavior-x', 'none');

    /**
     * Position Utilities.
     */

    Util::set('static')->prop('position', 'static');
    Util::set('fixed')->prop('position', 'fixed');
    Util::set('absolute')->prop('position', 'absolute');
    Util::set('relative')->prop('position', 'relative');
    Util::set('sticky')->prop('position', 'sticky');

    /**
     * Top \ Right \ Bottom \ Left Utilities.
     */

    Util::set('inset')->value('top', 'top')->value('right', 'right')->value('bottom', 'bottom')->value('left', 'left');
    Util::set('inset-y')->value('top', 'top')->value('bottom', 'bottom');
    Util::set('inset-x')->value('right', 'right')->value('left', 'left');
    Util::set('top')->value('top', 'top');
    Util::set('right')->value('right', 'right');
    Util::set('bottom')->value('bottom', 'bottom');
    Util::set('left')->value('left', 'left');

    /**
     * Visibility Utilities.
     */

    Util::set('visible')->prop('visibility', 'visible');
    Util::set('invisible')->prop('visibility', 'hidden');

    /**
     * Z-Index Utilities.
     */

    Util::set('z')->value('z-index', 'z-index');

    /**
     * Flex Direction Utilities.
     */

    Util::set('flex-row')->prop('flex-direction', 'row');
    Util::set('flex-row-reverse')->prop('flex-direction', 'row-reverse');
    Util::set('flex-col')->prop('flex-direction', 'column');
    Util::set('flex-col-reverse')->prop('flex-direction', 'column-reverse');
    
    /**
     * Flex Wrap Utilities.
     */
    
    Util::set('flex-wrap')->prop('flex-wrap', 'wrap');
    Util::set('flex-wrap-reverse')->prop('flex-wrap', 'wrap-reverse');
    Util::set('flex-no-wrap')->prop('flex-wrap', 'nowrap');

    /**
     * Flex Utilities.
     */

    Util::set('flex-1')->prop('flex', '1 1 0%');
    Util::set('flex-auto')->prop('flex', '1 1 auto');
    Util::set('flex-initial')->prop('flex', '0 1 auto');
    Util::set('flex-none')->prop('flex', 'none');

    /**
     * Flex Grow Utilities.
     */

    Util::set('flex-grow-0')->prop('flex-grow', 0);
    Util::set('flex-grow')->prop('flex-grow', 1);

    /**
     * Flex Shrink Utilities.
     */

    Util::set('flex-shrink-0')->prop('flex-shrink', 0);
    Util::set('flex-shrink')->prop('flex-shrink', 1);

    /**
     * Order Utilities.
     */

    Util::set('order')->value('order', 'order');

    /**
     * Grid Template Columns Utilities.
     */

    Util::set('grid-cols')->value('grid-template-columns', 'grid');

    /**
     * Grid Template Rows Utilities.
     */

    Util::set('grid-rows')->value('grid-template-rows', 'grid');

    /**
     * Grid Auto Flow Utilities.
     */

    Util::set('grid-flow-row')->prop('grid-auto-flow', 'row');
    Util::set('grid-flow-col')->prop('grid-auto-flow', 'column');
    Util::set('grid-flow-row-dense')->prop('grid-auto-flow', 'row dense');
    Util::set('grid-flow-col-dense')->prop('grid-auto-flow', 'column dense');

    /**
     * Grid Auto Columns Utilities.
     */

    Util::set('auto-cols-auto')->prop('grid-auto-columns', 'auto');
    Util::set('auto-cols-min')->prop('grid-auto-columns', 'min-content');
    Util::set('auto-cols-max')->prop('grid-auto-columns', 'max-content');
    Util::set('auto-cols-fr')->prop('grid-auto-columns', 'minmax(0, 1fr)');
    
    /**
     * Grid Auto Rows Utilities.
     */

    Util::set('auto-rows-auto')->prop('grid-auto-rows', 'auto');
    Util::set('auto-rows-min')->prop('grid-auto-rows', 'min-content');
    Util::set('auto-rows-max')->prop('grid-auto-rows', 'max-content');
    Util::set('auto-rows-fr')->prop('grid-auto-rows', 'minmax(0, 1fr)');

    /**
     * Gap Utilities.
     */

    Util::set('gap')->value('gap', 'gap');
    Util::set('gap-x')->value('column-gap', 'gap');
    Util::set('gap-y')->value('row-gap', 'gap');

    /**
     * Justify Content Utilities.
     */

    Util::set('justify-start')->prop('justify-content', 'flex-start');
    Util::set('justify-end')->prop('justify-content', 'flex-end');
    Util::set('justify-center')->prop('justify-content', 'center');
    Util::set('justify-between')->prop('justify-content', 'space-between');
    Util::set('justify-around')->prop('justify-content', 'space-around');
    Util::set('justify-evenly')->prop('justify-content', 'space-evenly');

    /**
     * Justify Items Utilities.
     */

    Util::set('justify-items-auto')->prop('justify-items', 'auto');
    Util::set('justify-items-start')->prop('justify-items', 'start');
    Util::set('justify-items-end')->prop('justify-items', 'end');
    Util::set('justify-items-center')->prop('justify-items', 'center');
    Util::set('justify-items-stretch')->prop('justify-items', 'stretch');

    /**
     * Justify Self Utilities.
     */

    Util::set('justify-self-auto')->prop('justify-self', 'auto');
    Util::set('justify-self-start')->prop('justify-self', 'start');
    Util::set('justify-self-end')->prop('justify-self', 'end');
    Util::set('justify-self-center')->prop('justify-self', 'center');
    Util::set('justify-self-stretch')->prop('justify-self', 'stretch');

    /**
     * Align Content Utilities.
     */

    Util::set('content-center')->prop('align-content', 'center');
    Util::set('content-start')->prop('align-content', 'flex-start');
    Util::set('content-end')->prop('align-content', 'flex-end');
    Util::set('content-between')->prop('align-content', 'space-between');
    Util::set('content-around')->prop('align-content', 'space-around');
    Util::set('content-evenly')->prop('align-content', 'space-evenly');

    /**
     * Align Items Utilities.
     */

    Util::set('items-start')->prop('align-items', 'flex-start');
    Util::set('items-end')->prop('align-items', 'flex-end');
    Util::set('items-center')->prop('align-items', 'center');
    Util::set('items-baseline')->prop('align-items', 'baseline');
    Util::set('itemn-stretch')->prop('align-items', 'stretch');

    /**
     * Align Self Utilities.
     */

    Util::set('self-auto')->prop('align-self', 'auto');
    Util::set('self-start')->prop('align-self', 'flex-start');
    Util::set('self-end')->prop('align-self', 'flex-end');
    Util::set('self-center')->prop('align-self', 'center');
    Util::set('self-stretch')->prop('align-self', 'strecth');

    /**
     * Place Content Utilities.
     */

    Util::set('place-content-center')->prop('place-content', 'center');
    Util::set('place-content-start')->prop('place-content', 'start');
    Util::set('place-content-end')->prop('place-content', 'end');
    Util::set('place-content-between')->prop('place-content', 'space-between');
    Util::set('place-content-around')->prop('place-content', 'space-around');
    Util::set('place-content-evenly')->prop('place-content', 'space-evenly');
    Util::set('place-content-stretch')->prop('place-content', 'stretch');

    /**
     * Place Items Utilities.
     */

    Util::set('place-items-auto')->prop('place-items', 'auto');
    Util::set('place-items-start')->prop('place-items', 'start');
    Util::set('place-items-end')->prop('place-items', 'end');
    Util::set('place-items-center')->prop('place-items', 'center');
    Util::set('place-items-stretch')->prop('place-items', 'stretch');

    /**
     * Place Self Utilities.
     */

    Util::set('place-self-auto')->prop('place-self', 'auto');
    Util::set('place-self-start')->prop('place-self', 'start');
    Util::set('place-self-end')->prop('place-self', 'end');
    Util::set('place-self-center')->prop('place-self', 'center');
    Util::set('place-self-stretch')->prop('place-self', 'stretch');

    /**
     * Padding Utilities.
     */

    Util::set('p')->value('padding', 'padding', 'px');
    Util::set('py')->value('padding-bottom', 'padding', 'px')->value('padding-top', 'padding', 'px');
    Util::set('px')->value('padding-right', 'padding', 'px')->value('padding-left', 'padding', 'px');
    Util::set('pt')->value('padding-top', 'padding', 'px');
    Util::set('pb')->value('padding-bottom', 'padding', 'px');
    Util::set('pr')->value('padding-right', 'padding', 'px');
    Util::set('pl')->value('padding-left', 'padding', 'px');

    /**
     * Margin Utilities.
     */

    Util::set('m')->value('margin', 'margin', 'px');
    Util::set('my')->value('margin-bottom', 'margin', 'px')->value('margin-top', 'margin', 'px');
    Util::set('mx')->value('margin-right', 'margin', 'px')->value('margin-left', 'margin', 'px');
    Util::set('mt')->value('margin-top', 'margin', 'px');
    Util::set('mb')->value('margin-bottom', 'margin', 'px');
    Util::set('mr')->value('margin-right', 'margin', 'px');
    Util::set('ml')->value('margin-left', 'margin', 'px');

    /**
     * Width Utilities.
     */

    Util::set('w')->value('width', 'width', '%');
    Util::set('min-w')->value('min-width', 'min-width', '%');
    Util::set('max-w')->value('max-width', 'max-width', '%');
    Util::set('h')->value('height', 'height', '%');
    Util::set('min-h')->value('min-height', 'min-height', '%');
    Util::set('max-h')->value('max-height', 'max-height', '%');

    /**
     * Font Family Utilities.
     */

    Util::set('font-sans')->prop('font-family', 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"');
    Util::set('font-serif')->prop('font-family', 'Georgia, Cambria, "Times New Roman", Times, serif');
    Util::set('font-mono')->prop('font-family', 'Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace');

    /**
     * Font Size Utilities.
     */

    Util::set('text')->value('font-size', 'font-size', 'rem');

    /**
     * Font Smoothing Utilities.
     */

    Util::set('antialiased')->prop('-webkit-font-smoothing', 'antialiased')->prop('-moz-osx-font-smoothing', 'grayscale');
    Util::set('subpixel-antialiased')->prop('-webkit-font-smoothing', 'auto')->prop('-moz-osx-font-smoothing', 'auto');

    /**
     * Font Style Utilities.
     */

    Util::set('italic')->prop('font-style', 'italic');
    Util::set('not-italic')->prop('font-style', 'normal');

    /**
     * Font Weight Utilities.
     */

    Util::set('font-hairline')->prop('font-weight', 100);
    Util::set('font-thin')->prop('font-weight', 200);
    Util::set('font-light')->prop('font-weight', 300);
    Util::set('font-normal')->prop('font-weight', 400);
    Util::set('font-medium')->prop('font-weight', 500);
    Util::set('font-semibold')->prop('font-weight', 600);
    Util::set('font-bold')->prop('font-weight', 700);
    Util::set('font-extrabold')->prop('font-weight', 800);
    Util::set('font-black')->prop('font-weight', 900);

    /**
     * Font Variant Numeric Utilities.
     */

    Util::set('normal-nums')->prop('font-variant-numeric', 'normal');
    Util::set('ordinal')->prop('font-variant-numeric', 'ordinal');
    Util::set('slashed-zero')->prop('font-variant-numeric', 'slashed-zero');
    Util::set('lining-nums')->prop('font-variant-numeric', 'lining-nums');
    Util::set('oldstyle-nums')->prop('font-variant-numeric', 'oldstyle-nums');
    Util::set('proportional-nums')->prop('font-variant-numeric', 'proportional-nums');
    Util::set('tubular-nums')->prop('font-variant-numeric', 'tubular-nums');
    Util::set('diagonal-fractions')->prop('font-variant-numeric', 'diagonal-fractions');
    Util::set('stacked-fractions')->prop('font-variant-numeric', 'stacked-fractions');

    /**
     * Letter Spacing Utilities.
     */

    Util::set('tracking-tighter')->prop('letter-spacing', '-0.05em');
    Util::set('tracking-tight')->prop('letter-spacing', '-0.025em');
    Util::set('tracking-normal')->prop('letter-spacing', '0');
    Util::set('tracking-wide')->prop('letter-spacing', '0.025em');
    Util::set('tracking-wider')->prop('letter-spacing', '0.5em');
    Util::set('tracking-widest')->prop('letter-spacing', '0.1em');

    /**
     * Line Height Utilities.
     */

    Util::set('leading')->value('line-height', 'line-height');

    /**
     * List Style Type Utilities.
     */

    Util::set('list-none')->prop('list-style-type', 'none');
    Util::set('list-disc')->prop('list-style-type', 'disc');
    Util::set('list-decimal')->prop('list-style-type', 'decimal');

    /**
     * List Style Position Utilities.
     */

    Util::set('list-inside')->prop('list-style-position', 'inside');
    Util::set('list-outside')->prop('list-style-position', 'outside');

    /**
     * Text Align Utilities.
     */

    Util::set('text-left')->prop('text-align', 'left');
    Util::set('text-center')->prop('text-align', 'center');
    Util::set('text-right')->prop('text-align', 'right');
    Util::set('text-justify')->prop('text-align', 'justify');

    /**
     * Text Color Utilities.
     */

    Util::set('text-transparent')->prop('color', 'transparent');
    Util::set('text-black')->prop('color', '#000000');
    Util::set('text-white')->prop('color', '#ffffff');
    Util::set('text-gray')->color('color', 'gray');
    Util::set('text-red')->color('color', 'red');
    Util::set('text-orange')->color('color', 'orange');
    Util::set('text-yellow')->color('color', 'yellow');
    Util::set('text-green')->color('color', 'green');
    Util::set('text-teal')->color('color', 'teal');
    Util::set('text-blue')->color('color', 'blue');
    Util::set('text-indigo')->color('color', 'indigo');
    Util::set('text-purple')->color('color', 'purple');
    Util::set('text-pink')->color('color', 'pink');

    /**
     * Text Decoration Utilities.
     */

    Util::set('underline')->prop('text-decoration', 'underline');
    Util::set('line-through')->prop('text-decoration', 'line-through');
    Util::set('no-underline')->prop('text-decoration', 'none');

    /**
     * Text Transform Utilities.
     */

    Util::set('uppercase')->prop('text-transform', 'uppercase');
    Util::set('lowercase')->prop('text-transform', 'lowercase');
    Util::set('capitalize')->prop('text-transform', 'capitalize');
    Util::set('normal-case')->prop('text-transform', 'none');

    /**
     * Vertical Align Utilities.
     */

    Util::set('align-baseline')->prop('vertical-align', 'baseline');
    Util::set('align-top')->prop('vertical-align', 'top');
    Util::set('align-middle')->prop('vertical-align', 'middle');
    Util::set('align-bottom')->prop('vertical-align', 'bottom');
    Util::set('align-text-top')->prop('vertical-align', 'text-top');
    Util::set('align-text-bottom')->prop('vertical-align', 'text-bottom');

    /**
     * Whitespace Utilities.
     */

    Util::set('whitespace-normal')->prop('white-space', 'normal');
    Util::set('whitespace-no-wrap')->prop('white-space', 'nowrap');
    Util::set('whitespace-pre')->prop('white-space', 'pre');
    Util::set('whitespace-pre-line')->prop('white-space', 'pre-line');
    Util::set('whitespace-pre-wrap')->prop('white-space', 'pre-wrap');

    /**
     * Word Break Utilities.
     */

    Util::set('break-normal')->prop('overflow-wrap', 'normal')->prop('word-break', 'normal');
    Util::set('break-words')->prop('overflow-wrap', 'break-word');
    Util::set('break-all')->prop('word-break', 'break-all');
    Util::set('truncate')->prop('overflow', 'hidden')->prop('text-overflow', 'ellipsis')->prop('white-space', 'nowrap');

    /**
     * Background Attachment Utilities.
     */

    Util::set('bg-fixed')->prop('background-attachment', 'fixed');
    Util::set('bg-local')->prop('background-attachment', 'local');
    Util::set('bg-scroll')->prop('background-attachment', 'scroll');

    /**
     * Background Clip Utilities.
     */

    Util::set('bg-clip-border')->prop('background-clip', 'border-box');
    Util::set('bg-clip-padding')->prop('background-clip', 'padding-box');
    Util::set('bg-clip-content')->prop('background-clip', 'content-box');
    Util::set('bg-clip-text')->prop('background-clip', 'text');

    /**
     * Background Color Utilities.
     */

    Util::set('bg')->color('background-color', 'null');
    Util::set('bg-gray')->color('background-color', 'gray');
    Util::set('bg-red')->color('background-color', 'red');
    Util::set('bg-orange')->color('background-color', 'orange');
    Util::set('bg-yellow')->color('background-color', 'yellow');
    Util::set('bg-green')->color('background-color', 'green');
    Util::set('bg-teal')->color('background-color', 'teal');
    Util::set('bg-blue')->color('background-color', 'blue');
    Util::set('bg-indigo')->color('background-color', 'indigo');
    Util::set('bg-purple')->color('background-color', 'purple');
    Util::set('bg-pink')->color('background-color', 'pink');

    /**
     * Background Position Utilities.
     */

    Util::set('bg-bottom')->prop('background-position', 'bottom');
    Util::set('bg-center')->prop('background-position', 'center');
    Util::set('bg-left')->prop('background-position', 'left');
    Util::set('bg-left-bottom')->prop('background-position', 'left bottom');
    Util::set('bg-left-top')->prop('background-position', 'left top');
    Util::set('bg-right')->prop('background-position', 'right');
    Util::set('bg-right-bottom')->prop('background-position', 'right bottom');
    Util::set('bg-right-top')->prop('background-position', 'right top');
    Util::set('bg-top')->prop('background-position', 'top');

    /**
     * Background Repeat Utilities.
     */

    Util::set('bg-repeat')->prop('background-repeat', 'repeat');
    Util::set('bg-no-repeat')->prop('background-repeat', 'no-repeat');
    Util::set('bg-repeat-x')->prop('background-repeat', 'repeat-x');
    Util::set('bg-repeat-y')->prop('background-repeat', 'repeat-y');
    Util::set('bg-repeat-round')->prop('background-repeat', 'round');
    Util::set('bg-repeat-space')->prop('background-repeat', 'space');

    /**
     * Background Size Utilities.
     */

    Util::set('bg-auto')->prop('background-size', 'auto');
    Util::set('bg-cover')->prop('background-size', 'cover');
    Util::set('bg-contain')->prop('background-size', 'contain');

    /**
     * Border Radius Utilities.
     */

    Util::set('rounded')->value('border-radius', 'border-radius', 'px', '0.25rem');
    Util::set('rounded-t')->value('border-top-left-radius', 'border-radius', 'px')->value('border-top-right-radius', 'border-radius', 'px');
    Util::set('rounded-r')->value('border-top-right-radius', 'border-radius', 'px')->value('border-bottom-right-radius', 'border-radius', 'px');
    Util::set('rounded-b')->value('border-bottom-right-radius', 'border-radius', 'px')->value('border-bottom-left-radius', 'border-radius', 'px');
    Util::set('rounded-l')->value('border-top-left-radius', 'border-radius', 'px')->value('border-bottom-left-radius', 'border-radius', 'px');

    /**
     * Border Width Utilities.
     */

    Util::set('border')->value('border-width', 'border-width', 'px', '1px');
    Util::set('border-t')->value('border-top-width', 'border-width', 'px');
    Util::set('border-r')->value('border-right-width', 'border-width', 'px');
    Util::set('border-b')->value('border-bottom-width', 'border-width', 'px');
    Util::set('border-l')->value('border-left-width', 'border-width', 'px');

    /**
     * Border Color Utilities.
     */

    Util::set('border-transparent')->prop('border-color', 'transparent');
    Util::set('border-black')->prop('border-color', '#000000');
    Util::set('border-white')->prop('border-color', '#ffffff');
    Util::set('border-gray')->color('border-color', 'gray');
    Util::set('border-red')->color('border-color', 'red');
    Util::set('border-orange')->color('border-color', 'orange');
    Util::set('border-yellow')->color('border-color', 'yellow');
    Util::set('border-green')->color('border-color', 'green');
    Util::set('border-teal')->color('border-color', 'teal');
    Util::set('border-blue')->color('border-color', 'blue');
    Util::set('border-indigo')->color('border-color', 'indigo');
    Util::set('border-purple')->color('border-color', 'purple');
    Util::set('border-pink')->color('border-color', 'pink');

    /**
     * Border Style Utilities.
     */

    Util::set('border-solid')->prop('border-style', 'solid');
    Util::set('border-dashed')->prop('border-style', 'dashed');
    Util::set('border-dotted')->prop('border-style', 'dotted');
    Util::set('border-double')->prop('border-style', 'double');
    Util::set('border-none')->prop('border-style', 'none');

    /**
     * Border Collapse Utilities.
     */

    Util::set('border-collapse')->prop('border-collapse', 'collapse');
    Util::set('border-separate')->prop('border-collapse', 'separate');

    /**
     * Table Layout Utilities.
     */

    Util::set('table-layout')->prop('table-layout', 'auto');
    Util::set('table-fixed')->prop('table-layout', 'fixed');

    /**
     * Box Shadow Utilities.
     */

    Util::set('shadow-xs')->prop('box-shadow', '0 0 0 1px rgba(0, 0, 0, 0.05)');
    Util::set('shadow-sm')->prop('box-shadow', '0 1px 2px 0 rgba(0, 0, 0, 0.05)');
    Util::set('shadow')->prop('box-shadow', '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)');
    Util::set('shadow-md')->prop('box-shadow', '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)');
    Util::set('shadow-lg')->prop('box-shadow', '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)');
    Util::set('shadow-xl')->prop('box-shadow', '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)');
    Util::set('shadow-2xl')->prop('box-shadow', '0 25px 50px -12px rgba(0, 0, 0, 0.25)');
    Util::set('shadow-inner')->prop('box-shadow', 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)');
    Util::set('shadow-outline')->prop('box-shadow', '0 0 0 3px rgba(66, 153, 225, 0.5)');
    Util::set('shadow-none')->prop('box-shadow', 'none');

    /**
     * Opacity Utilities.
     */

    Util::set('opacity')->value('opacity', 'opacity');

    /**
     * Appearance Utilities.
     */

    Util::set('appearance-none')->prop('appearance', 'none');

    /**
     * Cursor Utilities.
     */

    Util::set('cursor-auto')->prop('cursor', 'auto');
    Util::set('cursor-default')->prop('cursor', 'default');
    Util::set('cursor-pointer')->prop('cursor', 'pointer');
    Util::set('cursor-wait')->prop('cursor', 'wait');
    Util::set('cursor-text')->prop('cursor', 'text');
    Util::set('cursor-move')->prop('cursor', 'move');
    Util::set('cursor-not-allowed')->prop('cursor', 'not-allowed');

    /**
     * Outline Utilities.
     */

    Util::set('outline-none')->prop('outline', '2px solid transparent')->prop('outline-offset', '2px');
    Util::set('outline-white')->prop('outline', '2px dotted white')->prop('outline-offset', '2px');
    Util::set('outline-black')->prop('outline', '2px dotted black')->prop('outline-offset', '2px');

    /**
     * Pointer Event Utilities.
     */

    Util::set('pointer-events-none')->prop('pointer-events', 'none');
    Util::set('pointer-events-auto')->prop('pointer-events', 'auto');

    /**
     * Resize Utilities.
     */

    Util::set('resize-none')->prop('resize', 'none');
    Util::set('resize-y')->prop('resize', 'vertical');
    Util::set('resize-x')->prop('resize', 'horizontal');
    Util::set('resize')->prop('resize', 'both');

    /**
     * User Select Utilities.
     */

    Util::set('select-none')->prop('user-select', 'none');
    Util::set('select-text')->prop('user-select', 'text');
    Util::set('select-all')->prop('user-select', 'all');
    Util::set('select-auto')->prop('user-select', 'auto');

}