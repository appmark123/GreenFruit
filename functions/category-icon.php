<?php
/*-----------------------------------------------------------------------------------*/
# Category Icon
/*-----------------------------------------------------------------------------------*/
add_action('admin_head', 'cm_tax_icon_head');
add_action('edit_term', 'cm_save_tax_icon', 10, 2);
add_action('create_term', 'cm_save_tax_icon', 10, 2);
function cm_tax_icon_head() {
    $taxonomies = get_taxonomies();

    if (is_array($taxonomies)) {
        foreach ($taxonomies as $cm_taxonomy) {
            add_action($cm_taxonomy . '_add_form_fields', 'cm_tax_icon_field');
            add_action($cm_taxonomy . '_edit_form_fields', 'cm_tax_icon_field');
        }
    }
}

function cm_tax_icon_field($taxonomy) {

    if(empty($taxonomy)) {
        echo '<div class="form-field">
        <label for="cm_tax_icon">分类图标</label>
        <input type="text" style="width:95%" name="cm_tax_icon" id="cm_tax_icon" value=" " /><br/><p>关键词将作为分类存档页面的 Keywords </p>';
        echo '</div>';
    }
    else{
        $cm_tax_icon_value = get_option('cm_tax_icon' . $taxonomy->term_id);
        echo '<tr class="form-field">
        <th scope="row" valign="top"><label for="cm_tax_icon">分类图标</label></th>
        <td><input type="text" style="width:95%" name="cm_tax_icon" id="cm_tax_icon" value="'.$cm_tax_icon_value.'" /><br /><p class="description">关键词将作为分类存档页面的 Keywords </p>';
            echo '</td></tr>';
        }
    }

// save our taxonomy image while edit or save term
    function cm_save_tax_icon($term_id) {
        if (isset($_POST['cm_tax_icon']))
            update_option('cm_tax_icon' . $term_id, $_POST['cm_tax_icon']);
    }

// output taxonomy image url for the given term_id (NULL by default)
    function cm_tax_icon_value($term_id = NULL) {
        if ($term_id)
            return get_option('cm_tax_icon' . $term_id);
        elseif (is_category())
            return get_option('cm_tax_icon' . get_query_var('cat')) ;
        elseif (is_tax()) {
            $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            return get_option('cm_tax_icon' . $current_term->term_id);
        }
    }
    /*-----------------------------------------------------------------------------------*/
# Category Keywords
    /*-----------------------------------------------------------------------------------*/
    add_action('admin_head', 'cm_tax_keywords_head');
    add_action('edit_term', 'cm_save_tax_keywords', 10, 2);
    add_action('create_term', 'cm_save_tax_keywords', 10, 2);
    function cm_tax_keywords_head() {
        $taxonomies = get_taxonomies();
    //$taxonomies = array('category'); // uncomment and specify particular taxonomies you want to add image feature.
        if (is_array($taxonomies)) {
            foreach ($taxonomies as $cm_taxonomy) {
                add_action($cm_taxonomy . '_add_form_fields', 'cm_tax_keywords_field');
                add_action($cm_taxonomy . '_edit_form_fields', 'cm_tax_keywords_field');
            }
        }
    }

// add image field in add form
    function cm_tax_keywords_field($taxonomy) {

        if(empty($taxonomy)) {
            echo '<div class="form-field">
            <label for="cm_tax_keywords">关键词</label>
            <textarea type="text" rows="5" style="width:95%" name="cm_tax_keywords" id="cm_tax_keywords"></textarea><br/>
            <p>关键词将作为分类存档页面的 Keywords </p>';
            echo '</div>';
        }
        else{
            $cm_tax_keywords_value = get_option('cm_tax_keywords' . $taxonomy->term_id);
            echo '<tr class="form-field">
            <th scope="row" valign="top"><label for="cm_tax_keywords">关键词</label></th>
            <td><textarea type="text" rows="5" style="width:95%" name="cm_tax_keywords" id="cm_tax_keywords">'.$cm_tax_keywords_value.'</textarea><br /><p class="description">关键词将作为分类存档页面的 Keywords </p>';
                echo '</td></tr>';
            }
        }

// save our taxonomy image while edit or save term
        function cm_save_tax_keywords($term_id) {
            if (isset($_POST['cm_tax_keywords']))
                update_option('cm_tax_keywords' . $term_id, $_POST['cm_tax_keywords']);
        }

// output taxonomy image url for the given term_id (NULL by default)
        function cm_tax_keywords_value($term_id = NULL) {
            if ($term_id)
                return get_option('cm_tax_keywords' . $term_id);
            elseif (is_category())
                return get_option('cm_tax_keywords' . get_query_var('cat')) ;
            elseif (is_tax()) {
                $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                return get_option('cm_tax_keywords' . $current_term->term_id);
            }
        }
