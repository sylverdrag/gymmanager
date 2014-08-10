<?php
/**
 * @namespace GymManager.session
 */

/**
 * Handles the loging of sessions in the system. 
 * Requires a PDO database handle 
 * @author: Sylvain Galibert
 */
class gym_manager_class
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


    ### Class methods ###
    function __construct($dbLink)
    {
        $this->connect  = $dbLink;
    }

### Handle sessions

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
            $type = $this->get_session_type();
            $comments = $this->comments;
            return $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }
    
### Handle packages
    
    /**
    * Check if the package_id exists
    * #Status: not tested
    */	
    function exists_package_id($ID)
    {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM training_packages WHERE package_id = :package_id");
            $stmt->bindParam(':package_id', $package_id);

            $package_id = $ID;
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /**
     * Checks that the data for the new training package makes sense and calls "add_package_to_db()"
     */
    function create_package()
    {
        try
        {
            // If the package_id exists already, make a new one
            if ($this->exists_package_id($this->package_id)){
                $this->package_id = "pkg_" . date("Ymd_Hms");
            }
            
            // quick check to see if the package values make sense
            if (    
                    strlen($this->package_name) > 2 &&
                    $this->nb_sessions > 0 &&
                    $this->price_per_session >0 &&
                    $this->package_discount < 100
                )
            {
                if ($this->add_package_to_db()) { return TRUE; } else { return FALSE; }
            } 
            else 
            {
                return FALSE;
            }            
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }

    }

    function add_package_to_db()
    {
        try {
            $stmt = $this->connect->prepare("INSERT INTO training_packages (package_id, name, type, nb_sessions, price_per_session, active, discount, comments, full_price) " . 
                                  "VALUES (:package_id, :name, :type, :nb_sessions, :price_per_session, :active, :discount, :comments, :full_price)");
            $stmt->bindParam(':package_id', $package_id );
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':nb_sessions', $nb_sessions);
            $stmt->bindParam(':price_per_session', $price_per_session);
            $stmt->bindParam(':active', $active);
            $stmt->bindParam(':discount', $discount);
            $stmt->bindParam(':comments', $comments);
            $stmt->bindParam(':full_price', $full_price);

            $package_id = $this->package_id;
            $name = $this->package_name;
            $type = $this->type;
            $nb_sessions = $this->nb_sessions;
            $price_per_session = $this->price_per_session;
            $active = $this->package_active;
            $discount = $this->package_discount;
            $comments = $this->package_comments;
            $full_price = ($nb_sessions * $price_per_session * (100 - $discount)) / 100;
            return $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

### Handle clients
    
    /**
    * Check if the client exists
    * #Status: not tested
    */	
    function exists_client($last_name, $first_name)
    {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM clients WHERE last_name = :last_name AND first_name = :first_name");
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':first_name', $first_name);

            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /**
     * Checks that the data for the new client makes sense and calls "add_client_to_db()"
     */
    function create_client()
    {
        try
        {
            // If the client_id exists already, make a new one
            if ($this->exists_client_id($this->client_id)){
                $this->set_client_id("clt_" . date("Ymd_Hms"));
            }
            
            // Check if there is already a client by that name
            if ($this->exists_client($this->client_last_name, $this->client_first_name))
            {
                echo "That client already exists in the database.";
                return FALSE;  // Work out how to handle exceptions properly
            }
            
            if ($this->add_client_to_db()) { return TRUE; } else { return FALSE; }
                        
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }

    }

    function add_client_to_db()
    {
        try {
            $stmt = $this->connect->prepare("INSERT INTO clients (client_id, first_name, last_name, address, city, zip, age, sex, phone, email, created_date) " . 
                                  "VALUES (:client_id, :first_name, :last_name, :address, :city, :zip, :age, :sex, :phone, :email, :created_date)");
            $stmt->bindParam(':client_id', $client_id );
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':zip', $zip);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':sex', $sex);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':created_date', $created_date);

            $client_id = $this->client_id;
            $first_name = $this->client_first_name;
            $last_name = $this->client_last_name;
            $address = $this->client_address;
            $city = $this->client_city;
            $zip = $this->client_zip;
            $age = $this->client_age;
            $sex = $this->client_sex;
            $phone =  $this->client_phone;
            $email = $this->client_email;
            $created_date = $this->client_create_date;
            return $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

### Handle trainers
    
    /**
    * Check if the trainer already exists
    * #Status: not tested
    */	
    function exists_trainer($last_name, $first_name)
    {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM trainers WHERE last_name = :last_name AND first_name = :first_name");
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':first_name', $first_name);

            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /**
     * Checks that the data for the new trainer makes sense and calls "add_trainer_to_db()"
     */
    function create_trainer()
    {
        try
        {
            // If the trainer_id exists already, make a new one
            // Shouldn't happen - add a better id generation system if it ever becomes a problem
            if ($this->exists_trainer_id($this->trainer_id)){
                $this->set_trainer_id("tnr_" . date("Ymd_Hms"));
            }
            
            // Check if there is already a trainer by that name
            if ($this->exists_trainer($this->trainer_last_name, $this->trainer_first_name))
            {
                echo "That client already exists in the database.";
                return FALSE;  // Work out how to handle exceptions properly in PHP
            }
            
            if ($this->add_trainer_to_db()) { return TRUE; } else { return FALSE; }
                        
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }

    }

    function add_trainer_to_db()
    {
        try {
            $stmt = $this->connect->prepare("INSERT INTO trainers (trainer_id, first_name, last_name, sex, age, phone, address, active, boxing_rate, pilates_rate, yoga_rate, bodyweight_rate, trx_rate, comments, creation_date) " . 
                                  "VALUES (:trainer_id, :first_name, :last_name, :sex, :age, :phone, :address, :active, :boxing_rate, :pilates_rate, :yoga_rate, :bodyweight_rate, :trx_rate, :comments, :creation_date)");
            $stmt->bindParam(':trainer_id', $trainer_id);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':sex', $sex);
            $stmt->bindParam(':age', $age);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':active', $active);
            $stmt->bindParam(':boxing_rate', $boxing_rate);
            $stmt->bindParam(':pilates_rate', $pilates_rate);
            $stmt->bindParam(':yoga_rate', $yoga_rate);
            $stmt->bindParam(':bodyweight_rate', $bodyweight_rate);
            $stmt->bindParam(':trx_rate', $trx_rate);
            $stmt->bindParam(':comments', $comments);
            $stmt->bindParam(':creation_date', $creation_date);

            $trainer_id = $this->trainer_id;
            $first_name = $this->trainer_first_name;
            $last_name = $this->trainer_last_name;
            $sex = $this->trainer_sex;
            $age = $this->trainer_age;
            $phone = $this->trainer_phone;
            $address = $this->trainer_address;
            $active = $this->trainer_is_active;
            $boxing_rate = $this->trainer_rate_boxing;
            $pilates_rate = $this->trainer_rate_pilates;
            $yoga_rate = $this->trainer_rate_yoga;
            $bodyweight_rate = $this->trainer_rate_bodyweight;
            $trx_rate = $this->trainer_rate_trx;
            $comments = $this->get_comments();
            $creation_date = $this->trainer_create_date;
            
            return $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

### Handle contracts
    
    /**
     * Checks that the data for the new trainer makes sense and calls "add_trainer_to_db()"
     */
    function create_contract()
    {
        try
        {
            // If the contract_id exists already, make a new one
            // Shouldn't happen - add a better id generation system if it ever becomes a problem
            if ($this->exists_contract_id($this->contract_id)){
                $this->set_contract_id("ctt_" . date("Ymd_Hms"));
            }
                        
            if ($this->add_contract_to_db()) { return TRUE; } else { return FALSE; }
                        
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }

    }

    function add_contract_to_db()
    {
        try {
            $stmt = $this->connect->prepare("INSERT INTO contracts (contract_id, client_id, creation_date, branch, training_type, package, nb_sessions, price_per_session, start_date, expire_date, remaining_sessions, trainer_rate_modifier, comments) " . 
                                  "VALUES (:contract_id, :client_id, :creation_date, :branch, :training_type, :package, :nb_sessions, :price_per_session, :start_date, :expire_date, :remaining_sessions, :trainer_rate_modifier, :comments)");
            $stmt->bindParam(':contract_id', $contract_id);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':creation_date', $creation_date);
            $stmt->bindParam(':branch', $branch);
            $stmt->bindParam(':training_type', $training_type);
            $stmt->bindParam(':package', $package);
            $stmt->bindParam(':nb_sessions', $nb_sessions);
            $stmt->bindParam(':price_per_session', $price_per_session);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':expire_date', $expire_date);
            $stmt->bindParam(':remaining_sessions', $remaining_sessions);
            $stmt->bindParam(':trainer_rate_modifier', $trainer_rate_modifier);
            $stmt->bindParam(':comments', $comments);

            $contract_id = $this->get_contract_id();
            $client_id = $this->get_client_id();
            $creation_date = $this->contract_creation_date;
            $branch = $this->branch;
            $training_type = $this->get_session_type();
            $package = $this->package_id;
            $nb_sessions = $this->nb_sessions;
            $price_per_session = $this->price_per_session;
            $start_date = $this->start_date;
            $expire_date = $this->expire_date;
            $remaining_sessions = $this->remaining_sessions;
            $trainer_rate_modifier = $this->trainer_rate_modifier;
            $comments = $this->get_comments();

            return $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    
### Filling forms

    /*
     * Get the list of all clients. This is used to populate the drop down box of clients.
     * Returns an array of arrays [n][client_id], [n][first_name], [n][last_name]
     */
    function get_client_list()
    {
        try {
            $stmt = $this->connect->prepare("SELECT client_id, first_name, last_name FROM clients");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /*
     * Get the list of all active trainers. This is used to populate the drop down box of clients.
     * Returns an array of arrays [n][client_id], [n][first_name], [n][last_name]
     */
    function get_trainer_list()
    {
        try {
            $stmt = $this->connect->prepare("SELECT trainer_id, first_name, last_name FROM trainers WHERE active = \"yes\"");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    /*
     * Get the list of all active trainers. This is used to populate the drop down box of clients.
     * Returns an array of arrays [n][client_id], [n][first_name], [n][last_name]
     */
    function get_packages_list()
    {
        try {
            $stmt = $this->connect->prepare("SELECT * FROM training_packages WHERE active = \"yes\"");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    
    /*
     * Get the list of all contracts with remaining sessions for a specific client_id. 
     * This is used to populate the drop down box of contracts.
     * Returns an array of arrays [n][client_id], [n][first_name], [n][last_name]
     */
    function get_active_contracts_for_client($id)
    {
        try {
            $today = date("Y-m-d H:m:s");
            $stmt = $this->connect->prepare("SELECT * FROM contracts WHERE client_id = :clientid AND expire_date > '$today' AND remaining_sessions > 0");
            $stmt->bindParam(':clientid', $client_id );
            $client_id = $id;
            $stmt->execute();
            $contracts["data"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($contracts["data"]) > 0)
            {
                $contracts["status"] = "ok";
            }
            else 
            {
                $contracts["status"] = "no valid contract found";
            }
            return $contracts;
        } catch (PDOException $e) {
            return '{"status":"PDO database access error." . $e->getMessage() }';
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