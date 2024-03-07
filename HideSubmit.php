<?php namespace INTERSECT\HideSubmit;

use \REDCap as REDCap;

class HideSubmit extends \ExternalModules\AbstractExternalModule {

    function getTags($tag) {
        // This is straight out of Andy Martin's example post on this:
        // https://community.projectredcap.org/questions/32001/custom-action-tags-or-module-parameters.html
        if (!class_exists('INTERSECT\HideSubmit\ActionTagHelper')) include_once('classes/ActionTagHelper.php');
        $action_tag_results = ActionTagHelper::getActionTags($tag);
        return $action_tag_results;
    }

    function redcap_survey_page_top($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id, $repeat_instance) {

        // Get array of fields in current instruments
        $currInstrumentFields = REDCap::getFieldNames($instrument);

        // Begin Submit block
        $hideSubmitTags = array("@HIDESUBMIT","@HIDESUBMIT-SURVEY","@HIDESUBMITONLY","@HIDESUBMITONLY-SURVEY");
        $hideSubmitFields = array();

        foreach ($hideSubmitTags as $tag){
            $fields = $this->getTags($tag);
            if (empty($fields)) continue;
            $fields = array_keys($fields[$tag]);
            $hideSubmitFields = array_merge((array)$hideSubmitFields,(array)$fields); 
        };

        $hideSubmitFields = array_values(array_intersect((array)$hideSubmitFields, (array)$currInstrumentFields));
        // End Submit block

        // Begin Repeat block
        $hideRepeatTags = array("@HIDESUBMIT","@HIDESUBMIT-SURVEY","@HIDEREPEAT","@HIDEREPEAT-SURVEY");
        $hideRepeatFields = array();

        foreach ($hideRepeatTags as $tag){
            $fields = $this->getTags($tag);
            if (empty($fields)) continue;
            $fields = array_keys($fields[$tag]);
            $hideRepeatFields = array_merge((array)$hideRepeatFields,(array)$fields); 
        };

        $hideRepeatFields = array_values(array_intersect((array)$hideRepeatFields, (array)$currInstrumentFields));
        // End Repeat block

        // Decide whether to continue
        if (count($hideSubmitFields) + count($hideRepeatFields) === 0) { 
            return; 
        }

        // Create a JS array to feed into our JS script
        echo "<script type=\"text/javascript\">const hideSubmitFields = [];";
        for ($i = 0; $i < count($hideSubmitFields); $i++){
            // Push each field to the JS array
            echo "hideSubmitFields.push('". $hideSubmitFields[$i] ."');";
        }
        echo "const hideRepeatFields = [];";
        for ($i = 0; $i < count($hideRepeatFields); $i++){
            // Push each field to the JS array
            echo "hideRepeatFields.push('". $hideRepeatFields[$i] ."');";
        }
        echo "$(document).ready(function(){
            $(function(){
                function hideBtn(hideSubmitFields,hideRepeatFields) {
                    hideSubmit = 0;
                    hideRepeat = 0;
                    hideSubmitFields.forEach(function(field) {
                        if ($('#' + field + '-tr').is(':visible')) {
                            hideSubmit += 1;
                        };
                    });
                    hideRepeatFields.forEach(function(field) {
                        if ($('#' + field + '-tr').is(':visible')) {
                            hideRepeat += 1;
                        }
                    });

                        if (hideSubmit > 0) {
                            $('button[name=\"submit-btn-saverecord\"]').hide();
                        } else {
                            $('button[name=\"submit-btn-saverecord\"]').show();
                        };
                        if($('button[name=\"submit-btn-saverepeat\"]').length){
                            if (hideRepeat > 0) {
                                $('button[name=\"submit-btn-saverepeat\"]').hide();
                                $('button[name=\"submit-btn-saverepeat\"]').parent().prev().hide();
                            } else {
                                $('button[name=\"submit-btn-saverepeat\"]').show();
                                $('button[name=\"submit-btn-saverepeat\"]').parent().prev().show();
                            };
                        };
                        if (hideRepeat + hideSubmit == 0) {
                            $('button[name=\"submit-btn-saverepeat\"]').parent().next().show();
                        } else {
                            $('button[name=\"submit-btn-saverepeat\"]').parent().next().hide();
                        };
                    };
                hideBtn(hideSubmitFields,hideRepeatFields);
                const callback = function(mutation, observer) {
                    hideBtn(hideSubmitFields,hideRepeatFields);
                };
                const observer = new MutationObserver(callback);
                targetFields = hideSubmitFields.concat(hideRepeatFields);
                targetFields.forEach(function(field) {
                    const node = document.getElementById(field+'-tr');
                    if (node){
                        observer.observe(node, {attributes: true});
                    }
                });
            });
        });
        </script>";
    }

    function redcap_data_entry_form_top($project_id, $record, $instrument, $event_id, $group_id, $repeat_instance)
    {
        // Get array of fields in current instruments
        $currInstrumentFields = REDCap::getFieldNames($instrument);

        // Begin Submit block
        $hideSubmitTags = array("@HIDESUBMIT","@HIDESUBMIT-FORM","@HIDESUBMITONLY","@HIDESUBMITONLY-FORM");
        $hideSubmitFields = array();

        foreach ($hideSubmitTags as $tag){
            $fields = $this->getTags($tag);
            if (empty($fields)) continue;
            $fields = array_keys($fields[$tag]);
            $hideSubmitFields = array_merge((array)$hideSubmitFields,(array)$fields); 
        };

        $hideSubmitFields = array_values(array_intersect((array)$hideSubmitFields, (array)$currInstrumentFields));
        // End Submit block

        // Begin Repeat block
        $hideRepeatTags = array("@HIDESUBMIT","@HIDESUBMIT-FORM","@HIDEREPEAT","@HIDEREPEAT-FORM");
        $hideRepeatFields = array();

        foreach ($hideRepeatTags as $tag){
            $fields = $this->getTags($tag);
            if (empty($fields)) continue;
            $fields = array_keys($fields[$tag]);
            $hideRepeatFields = array_merge((array)$hideRepeatFields,(array)$fields); 
        };

        $hideRepeatFields = array_values(array_intersect((array)$hideRepeatFields, (array)$currInstrumentFields));
        // End Repeat block

        // Decide whether to continue
        if (count($hideSubmitFields) + count($hideRepeatFields) === 0) { 
            return; 
        }

        // Create a JS array to feed into our JS script
        echo "<script type=\"text/javascript\">const hideSubmitFields = [];";
        for ($i = 0; $i < count($hideSubmitFields); $i++){
            // Push each field to the JS array
            echo "hideSubmitFields.push('". $hideSubmitFields[$i] ."');";
        }
        echo "const hideRepeatFields = [];";
        for ($i = 0; $i < count($hideRepeatFields); $i++){
            // Push each field to the JS array
            echo "hideRepeatFields.push('". $hideRepeatFields[$i] ."');";
        }
        echo "$(document).ready(function(){
            $(function(){
                containsRpt = false;
                if ($('a[id=\"submit-btn-savenextinstance\"]').length + $('button[id=\"submit-btn-savenextinstance\"]').length){
                    containsRpt = true;
                };
                function hideBtn(hideSubmitFields,hideRepeatFields) {
                    hideSubmit = 0;
                    hideSubmitFields.forEach(function(field) {
                        if ($('#' + field + '-tr').is(':visible')) {
                            hideSubmit += 1;
                        };
                    });
                    if (hideSubmit) {
                        $('button[name=\"submit-btn-saverecord\"]').hide();
                        $('button[id=\"submit-btn-savecontinue\"]').hide();
                        $('button[id=\"submit-btn-savenextrecord\"]').hide();
                        $('button[id=\"submit-btn-savenextform\"]').hide();
                        $('button[id=\"submit-btn-savecompresp\"]').hide();
                        $('button[id=\"submit-btn-saveexitrecord\"]').hide();
                        $('button[id=\"submit-btn-placeholder\"]').hide();
                        $('a[id=\"submit-btn-savenextrecord\"]').hide();
                        $('a[id=\"submit-btn-savenextform\"]').hide();
                        $('a[id=\"submit-btn-savecompresp\"]').hide();
                        $('a[id=\"submit-btn-saveexitrecord\"]').hide();
                        $('a[id=\"submit-btn-savecontinue\"]').hide();
                        $('button[id=\"submit-btn-dropdown\"]').hide();
                    } else {
                        $('button[name=\"submit-btn-saverecord\"]').show();
                        $('button[id=\"submit-btn-savecontinue\"]').show();
                        $('button[id=\"submit-btn-savenextrecord\"]').show();
                        $('button[id=\"submit-btn-savenextform\"]').show();
                        $('button[id=\"submit-btn-savecompresp\"]').show();
                        $('button[id=\"submit-btn-saveexitrecord\"]').show();
                        $('button[id=\"submit-btn-placeholder\"]').show();
                        $('a[id=\"submit-btn-savenextrecord\"]').show();
                        $('a[id=\"submit-btn-savenextform\"]').show();
                        $('a[id=\"submit-btn-savecompresp\"]').show();
                        $('a[id=\"submit-btn-saveexitrecord\"]').show();
                        $('a[id=\"submit-btn-savecontinue\"]').show();
                        $('button[id=\"submit-btn-dropdown\"]').show();
                    };
                    if (containsRpt) {
                        hideRepeat = 0;
                        hideRepeatFields.forEach(function(field) {
                            if ($('#' + field + '-tr').is(':visible')) {
                                hideRepeat += 1;
                            };
                        });
                        if (hideRepeat) {
                            // Determine if save and add instance button is active
                            if ($('button[id=\"submit-btn-savenextinstance\"]').length) {
                                $('button[id=\"submit-btn-savenextinstance\"]').hide();
                            } else {
                                $('a[id=\"submit-btn-savenextinstance\"]').hide();
                            };
                        } else {
                            if ($('button[id=\"submit-btn-savenextinstance\"]').length) {
                                $('button[id=\"submit-btn-savenextinstance\"]').show();
                            } else {
                                $('a[id=\"submit-btn-savenextinstance\"]').show();
                                $('button[id=\"submit-btn-dropdown\"]').show();
                            };
                        };
                    };
                };
                hideBtn(hideSubmitFields,hideRepeatFields);
                const callback = function(mutation, observer) {
                    hideBtn(hideSubmitFields,hideRepeatFields);
                };
                const observer = new MutationObserver(callback);
                targetFields = hideSubmitFields.concat(hideRepeatFields);
                targetFields.forEach(function(field) {
                    const node = document.getElementById(field+'-tr');
                    if (node){
                        observer.observe(node, {attributes: true});
                    }
                });
            });
        });
        </script>";
    }
}
