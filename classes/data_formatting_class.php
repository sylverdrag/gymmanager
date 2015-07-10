<?php
/**
 * @namespace GymManager.session
 */

/**
 * Massage data for display
 * Requires a PDO database handle 
 * @author: Sylvain Galibert
 */
class data_formatting_class
{
    ### class properties ###
// Add if needed later

    ### Constructor ###
    function __construct($dbLink)
    {
        $this->connect  = $dbLink;
    }

    ### Arrays to editable tables ###
    
    /*
     * Display the data of a 1 dimension array into an editable 2 columns table
     */
    function  array_to_editable_2col_table($data_array, $table_name)
    {
        $data_tbl = "<table class='". $table_name ." one_dim'><tr>";
        foreach ($data_array as $key => $value)
        {
        ob_start(); ?>
<td contenteditable="false" id="" class="<?= $table_name; ?>_col_header tbl_cell"><?= trim($key) ?></td>
<td contenteditable="true" data-colname ="<?=  trim($key); ?>" id="<?=  trim($key); ?>" class="<?= $table_name; ?>_detail tbl_cell"><?= trim($value) ?></td>
        <?php
        $data_tbl .= ob_get_clean() . "</tr><tr>";
        }
        $data_tbl .= "</tr></table>";
        return $data_tbl;
    }
    
    /*
     * Display a 2 dimensional data array into a table with 1 row per record
     */
    function  twodim_array_to_editable_table($data_array, $table_name)
    {
        $data_tbl = "<table class='". $table_name ." two_dim'>\n<tr>";
        $b_is_first_row = TRUE;
        foreach ($data_array as $key0 => $value0)
        {
            if ($b_is_first_row)
            {
                foreach ($value0 as $key => $value)
                {
                    if ($key === "session_id")
                    {
                        continue;
                    }
                    ob_start(); ?>
                        <th contenteditable="true" id="" class="<?= $table_name; ?>_detail tbl_cell"><?= trim($key) ?></th>
                    <?php
                    $data_tbl .= ob_get_clean();
                }
                $data_tbl .= "</tr>";
                $b_is_first_row = FALSE;
            }
            $data_tbl .= "<tr class='session'>";
            $session_id = "";
            foreach ($value0 as $key => $value)
            {
                if ($key === "session_id")
                {
                    $session_id = $value;
                    continue;
                }
                ob_start(); ?>
                    <td contenteditable="true" id="<?=  trim($key); ?>"  data-colname ="<?=  trim($key); ?>" data-sess_id ="<?=  trim($session_id); ?>" class="<?= $table_name; ?>_detail tbl_cell"><?= trim($value) ?></td>
                <?php
                $data_tbl .= ob_get_clean();
            }
            $data_tbl .= "</tr>\n";
        }
        $data_tbl .= "</table>";
        return $data_tbl;
    }
}