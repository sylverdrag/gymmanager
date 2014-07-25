<?php
/**
 * @namespace GymManager.session
 */

/**
 * Handles the loging of sessions in the system. 
 * Requires a PDO database handle 
 * @author: Sylvain Galibert
 */
class session
{
    ### class properties ###
    /**
    * @var $ID: loginID from members-logintbl
    */
    private $session_id    = ""; // valid format is ses_yyyymmdd_hhmmss
    private $date          = "";
    private $client_id     = ""; // valid format is clt_yyyymmdd_hhmmss
    private $contract_id   = ""; // valid format is ctt_yyyymmdd_hhmmss
    private $trainer_id    = ""; // valid format is tnr_yyyymmdd_hhmmss
    private $type          = "";
    private $comments      = "";
    private $connect       = "";

    ### Class methods ###
    function __construct($dbLink)
    {
        $this->connect  = $dbLink;
    }

    /**
     * Check if the session_id exists
     * #Status: not tested
     */	
    function exists_session_id($ID)
    {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM sessions WHERE session_id = :session_id");
            $stmt->bindParam(':session_id', $session_id );

            $session_id = $ID;
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    
     /**
     * Check if the client exists
     * #Status: not tested
     */	
    function exists_client_id($ID)
    {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM clients WHERE client_id = :client_id");
            $stmt->bindParam(':client_id', $client_id );

            $client_id = $ID;
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /**
    * Check if the contract_id exists
    * #Status: not tested
    */	
    function exists_contract_id($ID)
    {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM contracts WHERE contract_id = :contract_id");
            $stmt->bindParam(':contract_id', $contract_id );

            $contract_id = $ID;
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /**
    * Check if the trainer_id exists
    * #Status: not tested
    */	
    function exists_trainer_id($ID)
    {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM trainers WHERE trainer_id = :trainer_id");
            $stmt->bindParam(':trainer_id', $trainer_id);

            $trainer_id = $ID;
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Gets the number of remaining sessions in the contract based on the contract's id
     */
    function get_nb_remaining_sessions_in_contract($id)
    {
        try {
            $stmt = $this->connect->prepare("SELECT remaining_sessions FROM contracts WHERE contract_id = :contract_id");
            $stmt->bindParam(':contract_id', $contract_id);

            $contract_id = $id;
            $stmt->execute();
            return $stmt->fetch()[0];
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    /*
     * Gets the number of remaining sessions in the contract based on the contract's id
     */
    function decrement_remaining_sessions_in_contract($id)
    {
        try {
            $stmt = $this->connect->prepare("UPDATE contracts SET remaining_sessions = remaining_sessions - 1 WHERE contract_id = :contract_id");
            $stmt->bindParam(':contract_id', $contract_id);

            $contract_id = $id;
            if ($stmt->execute())
            {
                return $this->get_nb_remaining_sessions_in_contract($id);
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

    
    /**
     * Log the details of a session in the database
     * #Status: not tested
     */	
    function log_session()
    {
        try
        {
            // If the session_id exists already, make a new one
            if ($this->exists_session_id($this->session_id)){
                $this->set_session_id("ses_" . date("Ymd_Hms"));
            }
            
            // Check if the session is logged with existing client, with valid contracts and a valid trainer_id
            if (    
                    $this->exists_client_id($this->client_id) &&
                    $this->exists_contract_id($this->contract_id) &&
                    $this->exists_trainer_id($this->trainer_id)       
                )
            {
                // Check if there are remaining sessions in the contract
                $nb_remaining_sessions = $this->get_nb_remaining_sessions_in_contract($this->contract_id);
                /* @var $nb_remaining_sessions type int */
                if ($nb_remaining_sessions > 0)
                {
                    // time to log the effing session
                    if ($this->add_session_to_db())
                    {
                        // deduct one session from the contract.
                        $this->decrement_remaining_sessions_in_contract($this->contract_id);
                        return TRUE;
                    }
                    else
                    {
                        return FALSE;
                    }
                }
                else
                {
                    echo 'There are no sessions remaining in this contract. Please select another contract';
                }
            } 
            else 
            {
                echo "Some details don't match. Please make sure to select a valid client, contract and trainer";
            }
            
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
    function add_session_to_db()
    {
        try {
            $stmt = $this->connect->prepare("INSERT INTO sessions (session_id, date, client_id, contract_id, trainer_id, type, comments) " . 
                                  "VALUES (:session_id, :date, :client_id, :contract_id, :trainer_id, :type, :comments)");
            $stmt->bindParam(':session_id', $session_id );
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':contract_id', $contract_id);
            $stmt->bindParam(':trainer_id', $trainer_id);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':comments', $comments);

            $session_id = $this->session_id;
            $date = $this->date;
            $client_id = $this->client_id;
            $contract_id = $this->contract_id;
            $trainer_id = $this->trainer_id;
            $type = $this->type;
            $comments = $this->comments;
            return $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
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

?>