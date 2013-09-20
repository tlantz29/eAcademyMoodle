<?php
 //user defined columns to show or now
function franklin_initialise_colpos(moodle_page $page) {
    user_preference_allow_ajax_update('theme_franklin_chosen_colpos', PARAM_ALPHA);
}

function franklin_get_colpos($default='panelopen') {
    return get_user_preferences('theme_franklin_chosen_colpos', $default);
}


function franklin_process_css($css, $theme) {
	
	
	if (!empty($theme->settings->headercolor)) {
        $headercolor = $theme->settings->headercolor;
    } else {
        $headercolor = null;
    }
    $css = franklin_set_headercolor($css, $headercolor);
	
	
    // Set the link color
    if (!empty($theme->settings->linkcolor)) {
        $linkcolor = $theme->settings->linkcolor;
    } else {
        $linkcolor = null;
    }
    $css = franklin_set_linkcolor($css, $linkcolor);

	// Set the link hover color
    if (!empty($theme->settings->linkhover)) {
        $linkhover = $theme->settings->linkhover;
    } else {
        $linkhover = null;
    }
    $css = franklin_set_linkhover($css, $linkhover);
        

    // Return the CSS
    return $css;
}

/**
 * Sets the link color variable in CSS
 *
 */
 
function franklin_set_headercolor($css, $headercolor) {
    $tag = '[[setting:headercolor]]';
    $replacement = $headercolor;
    if (is_null($replacement)) {
        $replacement = '#E2472F';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
} 
 
function franklin_set_linkcolor($css, $linkcolor) {
    $tag = '[[setting:linkcolor]]';
    $replacement = $linkcolor;
    if (is_null($replacement)) {
        $replacement = '#0b4a5b';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function franklin_set_linkhover($css, $linkhover) {
    $tag = '[[setting:linkhover]]';
    $replacement = $linkhover;
    if (is_null($replacement)) {
        $replacement = '#666666';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

