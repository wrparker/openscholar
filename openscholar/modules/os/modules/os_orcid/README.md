## Synopsis
This module will provide users a way to import their ORCID publications in Open Scholar.

## Installation
Enable this module and add the following code the <i>cp_toolbar.module</i>, around the lines that add the appearance and settings (approximately line 226). <br />
<pre>
    $l = menu_get_item('cp/orcid');
    if ($l['access']) {
        $links['orcid'] = $l + array(
            '#paths' => array(),
        );
    }
</pre>

## Contributors
University of Waterloo Web Development team.