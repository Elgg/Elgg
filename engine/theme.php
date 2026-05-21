<?php

/**
 * Core CSS variables
 */

return [
	'default' => [
		// layout and shell
		'body-background-color' => '#f3f3f3',
		'layout-background-color' => 'white',
		
		'page-section-max-width' => '90rem',
		'maintenance-background-image' => 'url(graphics/maintenance.jpg)',
		'walled-garden-background-image' => 'url(graphics/walled_garden.jpg)',
		'walled-garden-background-image-brightness' => '1',
		
		// Typography
		'font-size' => '16px', // global font size
		'font-bold-weight' => '600', // weight of <strong> and <b> elements
		'font-family' => 'BlinkMacSystemFont, -apple-system, "Segoe UI",' .
			'"Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans",' .
			'"Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif', // global font family
		'font-family-font-awesome' => 'Font Awesome\ 7 Free',
		'anchor-color' => '#0078ac',
		'anchor-color-hover' => '#2d3047',
		'h-font-family' => null, // defaults to 'font-family'
		'h1-font-size' => '1.8rem',
		'h2-font-size' => '1.5rem',
		'h3-font-size' => '1.2rem',
		'h4-font-size' => '1.0rem',
		'h5-font-size' => '0.9rem',
		'h6-font-size' => '0.8rem',
		
		// element colors
		'text-color-soft' => '#969696',
		'text-color-mild' => '#7d7d7d',
		'text-color-strong' => '#2d3047',
		'text-color-highlight' => '#0078ac',
	
		'background-color-soft' => '#fafafa',
		'background-color-mild' => '#f3f3f3',
		'background-color-strong' => '#cfcfd2',
		'background-color-highlight' => '#0078ac',
		'background-color-input' => '#ffffff',
	
		'border-color-soft' => '#e6e6ea',
		'border-color-mild' => '#dcdcdc',
		'border-color-strong' => '#cfcfd2',
		'border-color-highlight' => '#0078ac',
		
		'border-radius-small' => '2px',
		'border-radius-medium' => '4px',
		'border-radius-large' => '8px',
	
		// messages and notices
		'state-success-font-color' => '#397f2e',
		'state-success-background-color' => '#eaf8e8',
		'state-success-border-color' => '#aadea2',
	
		'state-danger-font-color' => '#b94a48',
		'state-danger-background-color' => '#f8e8e8',
		'state-danger-border-color' => '#e5b7b5',
	
		'state-notice-font-color' => '#3b8bc9',
		'state-notice-background-color' => '#e7f1f9',
		'state-notice-border-color' => '#b1d1e9',
	
		'state-warning-font-color' => '#6b420f',
		'state-warning-background-color' => '#fcf8e4',
		'state-warning-border-color' => '#eddc7d',

		'state-header-background-color' => 'rgba(255,255,255,0.7)',
		
		// buttons
		'button-submit-background-color' => '#2d3047',
		'button-submit-font-color' => '#ffffff',
		'button-submit-background-color-hover' => '#0078ac',
		'button-submit-font-color-hover' => '#ffffff',
	
		'button-action-background-color' => '#0078ac',
		'button-action-font-color' => '#ffffff',
		'button-action-background-color-hover' => '#2d3047',
		'button-action-font-color-hover' => '#ffffff',
	
		'button-cancel-background-color' => '#e6e6ea',
		'button-cancel-font-color' => '#2d3047',
		'button-cancel-background-color-hover' => '#cfcfd2',
		'button-cancel-font-color-hover' => '#2d3047',
	
		'button-delete-background-color' => '#e6e6ea',
		'button-delete-font-color' => '#2d3047',
		'button-delete-background-color-hover' => '#d33f49',
		'button-delete-font-color-hover' => '#ffffff',

		'button-disabled-background-color' => '#f3f3f3',
		'button-disabled-font-color' => '#7d7d7d',
	
		'input-switch-checked-background-color' => '#397f2e',
		'input-switch-unchecked-background-color' => '#b94a48',
		'input-switch-disabled-background-color' => '#cfcfd2',
	
		// topbar
		'topbar-background-color' => '#0078ac',
		'topbar-indicator' => '#cfcfd2',
	],
	'dark' => [
		'body-background-color' => '#1f2123',
		'layout-background-color' => '#181a1b',
		'topbar-background-color' => '#004f71',
		'topbar-indicator' => '#323639',

		'walled-garden-background-image-brightness' => '0.4',
		
		'anchor-color' => '#0078ac',
		'anchor-color-hover' => '#c3beb6',

		// element colors
		'text-color-soft' => '#a69e92',
		'text-color-mild' => '#999083',
		'text-color-strong' => '#c3beb6',
		'text-color-highlight' => '#0078ac',

		'background-color-soft' => '#1b1d1e',
		'background-color-mild' => '#1f2123',
		'background-color-strong' => '#323639',
		'background-color-highlight' => '#004f71',
		'background-color-input' => '#323639',

		'border-color-soft' => '#363b3d',
		'border-color-mild' => '#2c2f31',
		'border-color-strong' => '#323639',
		'border-color-highlight' => '#004f71',

		// messages and notices
		'state-success-font-color' => '#8cd181',
		'state-success-background-color' => '#1d3510',
		'state-success-border-color' => '#2d6725',

		'state-danger-font-color' => '#c05c5a',
		'state-danger-background-color' => '#351010',
		'state-danger-border-color' => '#622522',

		'state-notice-font-color' => '#4e98ce',
		'state-notice-background-color' => '#202325',
		'state-notice-border-color' => '#1d4767',

		'state-warning-font-color' => '#eec087',
		'state-warning-background-color' => '#2f2805',
		'state-warning-border-color' => '#817012',

		'state-header-background-color' => 'rgba(0,0,0,0.3)',
				
		// buttons
		'button-submit-background-color' => '#0078ac',
		'button-submit-font-color' => '#ffffff',
		'button-submit-background-color-hover' => '#2d3047',
		'button-submit-font-color-hover' => '#ffffff',
		
		'button-action-background-color' => '#2d3047',
		'button-action-font-color' => '#ffffff',
		'button-action-background-color-hover' => '#0078ac',
		'button-action-font-color-hover' => '#ffffff',

		'button-cancel-background-color' => '#e6e6ea',
		'button-cancel-font-color' => '#2d3047',
		'button-cancel-background-color-hover' => '#cfcfd2',
		'button-cancel-font-color-hover' => '#2d3047',

		'button-delete-background-color' => '#e6e6ea',
		'button-delete-font-color' => '#2d3047',
		'button-delete-background-color-hover' => '#d33f49',
		'button-delete-font-color-hover' => '#ffffff',
	],
];
