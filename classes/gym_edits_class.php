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
    function get_all_clients_for_edit() 
    {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM sylver_gymmngr.clients");

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
    

}
