<?php

/**
 * @namespace GymManager.session
 */

/**
 * Handles the loging of sessions in the system. 
 * Requires a PDO database handle 
 * @author: Sylvain Galibert
 */
class gym_edits_class extends gym_manager_class 
{
    /*
     * Get the details of all the clients to allow editing
     * note: Consider pagination through LIMIT if it gets too much for one page
     */
    function get_all_clients_for_edit($order) 
    {
        try {
            $stmt = $this->connect->prepare("
                    SELECT 
                        * 
                    FROM 
                        sylver_gymmngr.clients
                    ORDER BY $order
                    ;");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Get the details of the clients created after a given date for record editing purposes
     * note: Consider pagination through LIMIT if it gets too much for one page
     */
    function get_clients_for_edit_since($date, $order) 
    {
        try {
            $stmt = $this->connect->prepare("
                    SELECT 
                        * 
                    FROM 
                        sylver_gymmngr.clients
                    WHERE
                        DATE(created_date) >= DATE(:date)
                    ORDER BY :order
                    ;");
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':order', $order);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Get the details of the clients created after a given date for record editing purposes
     * note: Consider pagination through LIMIT if it gets too much for one page
     */
    function get_clients_for_edit_by_name($name, $order) 
    {
        try {
            $stmt = $this->connect->prepare("
                    SELECT 
                        * 
                    FROM 
                        sylver_gymmngr.clients
                    WHERE
                        (last_name LIKE :name) OR
                        (first_name LIKE :name)
                    ORDER BY :order
                    ;");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':order', $order);
            $name = "%" . $name . "%";
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /**
     * Checks that the data for the client makes sense and calls "update_client_to_db()"
     */
    function update_client($client_data)
    {
        try
        {
            // If the client_id doesn't exists abort the update process
            if (!$this->exists_client_id($client_data['client_id']))
            {
                return false;
            }
            else
            {
                if ($this->update_client_to_db($client_data)) { return TRUE; } else { return FALSE; }
            }        
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }

    }

    /*
    * Update a client's record
    * Takes a POST array with all the updated client's data
    */
    function update_client_to_db($client_data)
    {
        try {
            $stmt = $this->connect->prepare(
                        "UPDATE clients SET " 
                            . "`first_name` = :first_name,"
                            . "`last_name` = :last_name,"
                            . "`address` = :address,"
                            . "`city` = :city,"
                            . "`zip` = :zip,"      
                            . "`age` = :age,"
                            . "`sex` = :sex,"
                            . "`phone` = :phone,"
                            . "`email` = :email " .
                        "WHERE "
                            . "`client_id` = :client_id"
                        );
            
            $stmt->bindParam(':client_id', $client_data["client_id"] );
            $stmt->bindParam(':first_name', $client_data["first_name"]);
            $stmt->bindParam(':last_name', $client_data["last_name"]);
            $stmt->bindParam(':address', $client_data["address"]);
            $stmt->bindParam(':city', $client_data["city"]);
            $stmt->bindParam(':zip', $client_data["zip"]);
            $stmt->bindParam(':age', $client_data["age"]);
            $stmt->bindParam(':sex', $client_data["sex"]);
            $stmt->bindParam(':phone', $client_data["phone"]);
            $stmt->bindParam(':email', $client_data["email"]);
            
            $result = $stmt->execute();
            return $result;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    
### Edit Trainers     
    /*
     * Get the details of all the trainers to allow editing
     * note: Consider pagination through LIMIT if it gets too much for one page
     */
    function get_all_trainers_for_edit($order) 
    {
        try {
            $stmt = $this->connect->prepare("
                    SELECT 
                        * 
                    FROM 
                        sylver_gymmngr.trainers
                    ORDER BY $order
                    ;");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Get the details of the trainers created after a given date for record editing purposes
     * note: Consider pagination through LIMIT if it gets too much for one page
     */
    function get_trainers_for_edit_since($date, $order) 
    {
        try {
            $stmt = $this->connect->prepare("
                    SELECT 
                        * 
                    FROM 
                        sylver_gymmngr.trainers
                    WHERE
                        DATE(creation_date) >= DATE(:date)
                    ORDER BY :order
                    ;");
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':order', $order);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Get the details of the trainers created after a given date for record editing purposes
     * note: Consider pagination through LIMIT if it gets too much for one page
     */
    function get_trainers_for_edit_by_name($name, $order) 
    {
        try {
            $stmt = $this->connect->prepare("
                    SELECT 
                        * 
                    FROM 
                        sylver_gymmngr.trainers
                    WHERE
                        (last_name LIKE :name) OR
                        (first_name LIKE :name)
                    ORDER BY :order
                    ;");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':order', $order);
            $name = "%" . $name . "%";
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /**
     * Checks that the data for the trainer makes sense and calls "update_client_to_db()"
     */
    function update_trainer($trainer_data)
    {
        try
        {
            // If the trainer_id doesn't exists, abort the update process
            if (!$this->exists_trainer_id($trainer_data['trainer_id']))
            {
                return false;
            }
            else
            {
                if ($this->update_trainer_to_db($trainer_data)) { return TRUE; } else { return FALSE; }
            }        
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }

    }

    /*
    * Update a trainer's record
    * Takes a POST array with all the updated trainer's data
    */
    function update_trainer_to_db($trainer_data)
    {
        try {
            $stmt = $this->connect->prepare(
                        "UPDATE trainers SET " 
                            . "`first_name` = :first_name,"
                            . "`last_name` = :last_name,"
                            . "`sex` = :sex,"
                            . "`age` = :age,"
                            . "`phone` = :phone, "  
                            . "`address` = :address,"
                            . "`active` = :active, "  
                            . "`boxing_rate` = :boxing_rate, "  
                            . "`pilates_rate` = :pilates_rate, "  
                            . "`yoga_rate` = :yoga_rate, "  
                            . "`bodyweight_rate` = :bodyweight_rate, "  
                            . "`trx_rate` = :trx_rate,"
                            . "`comments` = :comments "  
                      . "WHERE "
                            . "`trainer_id` = :trainer_id"
                        );
            
            $stmt->bindParam(':trainer_id', $trainer_data["trainer_id"] );
            $stmt->bindParam(':first_name', $trainer_data["first_name"]);
            $stmt->bindParam(':last_name', $trainer_data["last_name"]);
            $stmt->bindParam(':sex', $trainer_data["sex"]);
            $stmt->bindParam(':age', $trainer_data["age"]);
            $stmt->bindParam(':phone', $trainer_data["phone"]);
            $stmt->bindParam(':address', $trainer_data["address"]);
            $stmt->bindParam(':active', $trainer_data["active"]);
            $stmt->bindParam(':boxing_rate', $trainer_data["boxing_rate"]);
            $stmt->bindParam(':pilates_rate', $trainer_data["pilates_rate"]);
            $stmt->bindParam(':yoga_rate', $trainer_data["yoga_rate"]);
            $stmt->bindParam(':bodyweight_rate', $trainer_data["bodyweight_rate"]);
            $stmt->bindParam(':trx_rate', $trainer_data["trx_rate"]);
            $stmt->bindParam(':comments', $trainer_data["comments"]);
            
            $result = $stmt->execute();
            return $result;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

### training Packages   
    
    
    /*
     * Get the list of all packages in order "$order".
     * Returns an array of arrays [n][package_id], ...
     */
    function get_all_packages_for_edit($order)
    {
        try {
            $stmt = $this->connect->prepare("
                    SELECT 
                        * 
                    FROM 
                        training_packages
                    ORDER BY :order
                    ;");
            $stmt->bindParam(':order', $order);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Get the list of packages in order "$order" since a specific date
     * Returns an array of arrays [n][package_id], ...
     */
    function get_all_packages_for_edit_since($date, $order)
    {
        try {
            $stmt = $this->connect->prepare("
                    SELECT 
                        * 
                    FROM 
                        training_packages
                    WHERE
                        DATE(creation_date) >= DATE(:date)
                    ORDER BY :order
                    ;");
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':order', $order);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Get the list of packages in order "$order" since a specific date
     * Returns an array of arrays [n][package_id], ...
     */
    function get_packages_for_edit_by_name($name, $order)
    {
        try {
            $stmt = $this->connect->prepare("
                    SELECT 
                        * 
                    FROM 
                        training_packages
                    WHERE
                        name LIKE :name
                    ORDER BY :order
                    ;");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':order', $order);
            $name = "%" . $name . "%";

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Get the list of a specific type of packages in order "$order"
     * Returns an array of arrays [n][package_id], ...
     */
    function get_packages_for_edit_by_type($type, $order)
    {
        try {
            $stmt = $this->connect->prepare("
                    SELECT 
                        * 
                    FROM 
                        training_packages
                    WHERE
                        type LIKE :type
                    ORDER BY :order
                    ;");
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':order', $order);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /**
     * Checks that the data for the package makes sense and calls "update_package_to_db()"
     * In the future, add more check, notably to see if there are contracts using this package
     */
    function update_package($package_data)
    {
        try
        {
            // If the trainer_id doesn't exists, abort the update process
            if (!$this->exists_package_id($package_data['package_id']))
            {
                return false;
            }
            else
            {
                if ($this->update_package_to_db($package_data)) { return TRUE; } else { return FALSE; }
            }        
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }

    }

    /*
    * Update a package's record
    * Takes a POST array with all the updated package's data
    */
    function update_package_to_db($package_data)
    {
        try {
            $stmt = $this->connect->prepare(
                        "UPDATE training_packages SET " 
                            . "`name` = :name,"
                            . "`type` = :type,"
                            . "`nb_sessions` = :nb_sessions,"
                            . "`price_per_session` = :price_per_session,"
                            . "`active` = :active, "  
                            . "`discount` = :discount,"
                            . "`comments` = :comments, "  
                            . "`full_price` = :full_price"  
                      . " WHERE "
                            . "`package_id` = :package_id"
                        );
            
            $stmt->bindParam(':package_id', $package_data["package_id"] );
            $stmt->bindParam(':name', $package_data["name"]);
            $stmt->bindParam(':type', $package_data["type"]);
            $stmt->bindParam(':nb_sessions', $package_data["nb_sessions"]);
            $stmt->bindParam(':price_per_session', $package_data["price_per_session"]);
            $stmt->bindParam(':active', $package_data["active"]);
            $stmt->bindParam(':discount', $package_data["discount"]);
            $stmt->bindParam(':comments', $package_data["comments"]);
            $stmt->bindParam(':full_price', $full_price);
            
            $full_price = $package_data["nb_sessions"] * $package_data["price_per_session"] * ((100-$package_data["discount"])/100);
            $result = $stmt->execute();
            return $result;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }



### Formating    
    /*
     * On page formating of edit table
     */
    function format_edit_table($data_arr, $primary_key_name, $db_table_name, $hide_cols)
    {
        $edit_table  = "";
        $edit_table .= '<input type="hidden" value="'.$db_table_name .'" id="db_table_name" name="db_table_name" />';
        $edit_table .= "<div id='client_edit_table' class='edit_table'>\n
                                    <div class='tbl_body'>\n";
        for ($i = 0; $i < count($data_arr); $i++)
        {
            $primary_key = $data_arr[$i][$primary_key_name];
            if ($i%2 == 0)
            {
                $records .=  "\n<fieldset id='".$primary_key."' class='record record_even clearfix'>\n" .
                                "\n<div id='record_$i' class='record_data'>";
            }
            else 
            {
                $records .= "\n<fieldset id='".$primary_key."' class='record record_odd clearfix'>\n" .
                                "\n<div id='record_$i' class='record_data'>";
            }


            foreach ($data_arr[$i] as $key => $value)
            {
                if (!in_array($key, $hide_cols))
                {
                    switch ($key)
                    {
                        case "sex":
                            $enum_values = array("male", "female");
                            $options = "<option value=\"" . $value . "\">" . $value . "</option>";
                            foreach ($enum_values as $enum_key => $enum_value)
                            {
                                if ($enum_value !== $value)
                                {
                                    $options .= "<option value=\"" . $enum_value . "\">" . $enum_value . "</option>";
                                }
                            }
                            $selector = "<select class='edit_input $primary_key' size=\"1\" id=\"$key\" name=\"$key\">" . $options . "</select>";
                            $record .= "\n<div class='$key record_entry'>\n" . 
                                "<label class='lbl_regular'>" .$key . "</label> " .
                                $selector .
                                "\n</div>\n";
                            break;

                        default :
                        $record .= "<div class='$key record_entry'>\n" . 
                                "<label class='lbl_regular'>" .$key . "</label> " .
                                '<input class="edit_input ' . $primary_key .'" type="text" name="'. $key .'" id="'. $key .'" value="'. $value .'" class=""/> ' . 
                                "\n</div>\n";
                            break;
                    }
                }
                else 
                {
                    $record .= "<div class='$key record_entry' style='display:none'>\n" . 
                            '<input class="edit_input '.$primary_key.'" type="text" name="'. $key .'" id="'. $key .'" value="'. $value .'" class=""/> ' .  
                            "\n</div>\n";
                }
            }
// No delete function for now. Consider an option to make a client inactive.                   
//                    $record_delete =    "<div id='".$clients_arr[$i]["client_id"]."' class='bt_delete'>". 
//                                        "<img src='images/operation_delete.png' alt='Delete'></div>"; 
            $record_update =    "\n<div id='" . $primary_key ."' class='bt_update'>\n" .
                                "<img src='images/operation_update.png' alt='Update'></div>";
            $records .= $record . "\n</div>\n" . 
//                                $record_delete . 
                        $record_update;
            $records .= "\n</fieldset>\n";
            $record = "";
        }
        $edit_table .= $records . "\n</div>\n<caption>$caption</caption>\n</div>\n";
        return $edit_table;
    }

}
