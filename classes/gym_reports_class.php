<?php
/**
 * @namespace GymManager.session
 */

/**
 * Handles the loging of sessions in the system. 
 * Requires a PDO database handle 
 * @author: Sylvain Galibert
 */
class gym_reports_class
{
    ### class properties ###
    /**
    * Training session variables
    */
    private $session_id    = ""; // valid format is ses_yyyymmdd_hhmmss
    private $date          = "";
    private $client_id     = ""; // valid format is clt_yyyymmdd_hhmmss
    private $contract_id   = ""; // valid format is ctt_yyyymmdd_hhmmss
    private $trainer_id    = ""; // valid format is tnr_yyyymmdd_hhmmss
    private $type          = "";
    private $comments      = "";
    private $connect       = "";
    
    /**
     * Training package specific variables (a training package is a number of training sessions sold together)
     */
    public $package_id         = "";
    public $package_name       = "";
    public $nb_sessions        = "";
    public $price_per_session  = "";
    public $package_active     = "";
    public $package_discount   = "";
    public $package_comments   = "";
    
    /**
     * Client-specific variables
     */
    public $client_first_name  = "";
    public $client_last_name   = "";
    public $client_address     = "";
    public $client_city        = "";
    public $client_zip         = "";
    public $client_age         = "";
    public $client_sex         = "";
    public $client_phone       = "";
    public $client_email       = "";
    public $client_create_date = "";

    /**
     * Trainer-specific variables
     */
    public $trainer_first_name          = "";
    public $trainer_last_name           = "";
    public $trainer_address             = "";
    public $trainer_age                 = "";
    public $trainer_sex                 = "";
    public $trainer_phone               = "";
    public $trainer_is_active           = "";
    public $trainer_create_date         = "";
    public $trainer_rate_boxing         = "";
    public $trainer_rate_pilates        = "";
    public $trainer_rate_bodyweight     = "";
    public $trainer_rate_yoga           = "";
    public $trainer_rate_trx            = "";
    
    /**
     * contract-specific variables
     */
    public $contract_creation_date      = "";
    public $branch                      = "";
    public $start_date                  = "";
    public $expire_date                 = "";
    public $remaining_sessions          = "";
    public $trainer_rate_modifier       = "";


    ### Constructor ###
    function __construct($dbLink)
    {
        $this->connect  = $dbLink;
    }

    ### Reporting methods - Getting data for reports ###
    /*
     * Calculate the GI from a from date to a to date, inclusive, 
     * based on the contracts' creation date. The assumption here is that contracts are always paid when they are entered in the computer.
     * Returns the GI as a non formatted numerical string.
     */
    function calculate_GI($from_date, $to_date)
    {
        try {
            $stmt = $this->connect->prepare("/* Calculates the GI since a specific date */
                    SELECT 
                        SUM(nb_sessions * price_per_session) AS 'GI'
                    FROM 
                        sylver_gymmngr.contracts
                    WHERE
                        creation_date >= DATE(:from_date) AND
                        creation_date <= DATE(:to_date)
                    ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                return $stmt->fetch()["GI"];
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /*
     * Calculate the VSD from a from date to a to date, inclusive, 
     * based on the sessions date. 
     * Returns the GI as a non formatted numerical string.
     */
    function calculate_VSD($from_date, $to_date)
    {
        try {
            $stmt = $this->connect->prepare("/* Calculates the GI since a specific date */
                    SELECT  
                        SUM(contracts.price_per_session) AS 'VSD'
                    FROM 
                        sylver_gymmngr.sessions
                    JOIN 
                        sylver_gymmngr.contracts
                    ON 
                        sessions.contract_id = contracts.contract_id
                    WHERE
                        DATE(sessions.date) >= DATE(:from_date) AND
                        DATE(sessions.date) <= DATE(:to_date)
                    ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                return $stmt->fetch()["VSD"];
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /*
     * List contracts sold over a period and the name of the clients who bought them. Accepts a limit variable.
     * Returns an array containing the date of the contract, the full name of the client, the contract ID, 
     * the total value of the contract and the type of training.
     */
    function get_sales($from_date, $to_date, $limit)
    {
        try {
                $stmt = $this->connect->prepare("/* List contracts sold over a period and the name of the clients who bought them */
                        SELECT  
                            CONCAT (DATE(sylver_gymmngr.contracts.creation_date), ' ' ,TIME(sylver_gymmngr.contracts.creation_date)) AS 'Date',
                            CONCAT (sylver_gymmngr.clients.first_name, ' ', sylver_gymmngr.clients.last_name) AS 'Client',
                            sylver_gymmngr.contracts.contract_id AS 'Contract ID',
                            nb_sessions * price_per_session AS 'Amount',
                            training_type AS 'Training type'
                        FROM 
                            sylver_gymmngr.contracts
                        JOIN sylver_gymmngr.clients
                        ON sylver_gymmngr.clients.client_id = sylver_gymmngr.contracts.client_id
                        WHERE
                            creation_date >= DATE(:from_date) AND
                            creation_date <= DATE(:to_date)
                        $limit
                        ;");
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            if ($stmt->execute())
            {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            else 
            {
                return false;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
### Formatting results ###
    function results_to_table ($results_array, $report_name, $caption = "")
    {
        $table = "<table id='". $report_name ."' class='tbl_report'>\n
                    <thead>\n
                        <tr>\n";
        $table_headers = "";
        if (is_array($results_array[0]))
        {
            
            foreach ($results_array[0] as $key => $value)
            {
                $table_headers .= "<td id='$key' class='tbl_header_cell'>" . $key . "</td>\n";
            }
            $table .= $table_headers . "</tr>\n</thead>\n<tbody>\n";
            for ($i = 0; $i < count($results_array); $i++)
            {
                $table_rows .= "<tr>\n";
                foreach ($results_array[$i] as $key => $value)
                {
                    if ($i%2 == 0)
                    {
                        $row .= "<td class='$key tbl_data_cell_even'>" . $value . "</td>";
                    }
                    else 
                    {
                        $row .= "<td class='$key tbl_data_cell_odd'>" . $value . "</td>";
                    }
                }
                $table_rows .= $row . "\n</tr>\n";
                $row = "";
            }
            $table .= $table_rows . "\n</tbody>\n<caption>$caption</caption>\n</table>\n";
            return $table;
        }
        else 
        {
            return false;
        }
    }


### Utilities
        
/**
* Check if the string is a valid ID (like ses_20140725_183521)
*/
private function is_valid_id($sInput)
{
    $pattern = "/^.{3}_\d{8}_\d{6}$/";
    if (preg_match($pattern, $sInput) ==1){
            return true;
    } else {
            return false;
    }
}


### Getters and Setters ###
	
    /**
     * Getters
     */
    public function get_session_id()
    {
        return $this->session_id;
    }
    public function get_session_date()
    {
        return $this->date;
    }
    public function get_client_id()
    {
        return $this->client_id;
    }
    public function get_contract_id()
    {
        return $this->contract_id;
    }
    public function get_trainer_id()
    {
        return $this->trainer_id;
    }
    public function get_session_type()
    {
        return $this->type;
    }
    public function get_comments()
    {
        return $this->comments;
    }

    /**
     * Setters 
     */
    public function set_session_id($ID)
    {
    	if ($this->is_valid_id($ID))
        {
            $this->session_id = $ID;	
            return true;		
        }else { 
            return false;
        }        
    }
    public function set_session_date($date)
    {
        $this->date = $date;
    }
    public function set_client_id($ID)
    {
        if ($this->is_valid_id($ID))
        {
            $this->client_id = $ID;
            return true;		
        }else { 
            return false;
        }    
    }
    public function set_contract_id($ID)
    {
        if ($this->is_valid_id($ID))
        {
            $this->contract_id = $ID;
            return true;		
        }else { 
            return false;
        }   
    }
    public function set_trainer_id($ID)
    {
        if ($this->is_valid_id($ID))
        {
            $this->trainer_id = $ID;
            return true;		
        }else { 
            return false;
        }   
    }
    public function set_session_type($training_type)
    {
        $this->type = $training_type;
    }
    public function set_comments($session_comments)
    {
        $this->comments = $session_comments;
    }
}